<?php
/*
Plugin Name: WordPress Multilingual Sitemap
Plugin URI: http://code.google.com/p/wp-multilingual-sitemap/
Description: Adds an HTML (Not XML) sitemap of your blog pages ([wpms-pages]), posts ([wpms-posts] and posts order by category ([wpms-categories-posts]).
Version: 0.1
Author: Álvaro Díaz Pescador
Author URI: http://www.agalip.es/
Change Log: See readme.txt for complete change log
	
Copyright 2009-2010 Álvaro Díaz Pescador

License: GPL2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt)
*/

/*  Copyright 2010  Álvaro Díaz Pescador  (email : alvaro@agalip.es)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Load the custom Category Walker: Sitemap_Category class
require_once( plugin_dir_path(__FILE__) . 'class.posts-walker.php' );

/**
 * Retrieve the pages' sitemap when shortcode [wpms-pages] is found
 *
 * @param $args - arguments or attributes specified in the shortcode tag. ([wpms-pages child_of=xx ...])
 * @param $content - not used
 * @return the pages sitemap or empty string if not applicable.
 */
function wpms_list_pages($args, $content = null) {
	// If current page query is feed URL.
	if ( is_feed() )
		return '';

	// Force 'echo' param to 0; wp_list_pages returns the list as an HTML text string to be used in PHP
	$args['echo'] = 0;

	// If 'child_of' param is set to 'CURRENT', set it to current post id
	if ( isset($args['child_of']) && $args['child_of'] == 'CURRENT' )
		$args['child_of'] = get_the_ID();
	// If 'child_of' param is set to 'PARENT', set it to current post parent id
	elseif ( isset($args['child_of']) && $args['child_of'] == 'PARENT' ) {
		// Get parent post object
		$post = &get_post( get_the_ID() );
		// If it exists
		if ( $post->post_parent )
			$args['child_of'] = $post->post_parent;
		// Else, delete param
		else
			unset( $args['child_of'] );
	}

	//Get pages list HTML code
	$html = wp_list_pages( $args );

	// Remove the classes added by WordPress
	$html = preg_replace( '/( class="[^"]+")/is', '', $html );

	// If there are no pages
	if ( empty($html) )
		return $html;
	else
		return '<ul>' . $html . '</ul>';
}

/**
 * Retrieve the posts list's sitemap when shortcode [wpms-posts] is found
 *
 * @param $args - arguments or attributes specified in the shortcode tag. ([wpms-posts child_of=xx ...])
 * @param $content - not used
 * @return the pages sitemap or empty string if not applicable
 */
function wpms_list_posts($args, $content = null) {
	// If current page query is feed URL.
	if ( is_feed() )
		return '';

	if ( is_array( $args ) )
		$r = $args;
	else
		// Convert string 'category=x&order=...' to array
		parse_str( $args, $r );

	$defaults = array (
		'numberposts' => -1,			// Retrieve all posts
		'category' => 0, 				// Set to 0 in order to get posts from all categories
		'suppress_filters' => 0			// Apply filters - WPML
	);

	// Overwrite defaults with custom values
	$args = array_merge( $defaults, $r );

	// Get all posts
	$posts = get_posts( $args );

	$html = '';

	// Additional params
	$title_li = ( isset($args['title_li']) ) ? $args['title_li'] : __( 'Posts', 'wpms' );
	$style = ( isset($args['style']) ) ? $args['style'] : 'list';

	// If there are posts
	if ( ! empty($posts) ) {
		// If there is a title for the list
		if ( ! empty($title_li) ) {
			if ( $style == 'list' )
				$html .= '<li>' . $title_li . '<ul>';
			else
				$html .= '<h2>' . $title_li . '</h2>';
		}

		// Loop through $posts to build list
		foreach ( $posts as $post ) {
			if ( $style == 'list' )
				$html .= '<li><a href="' . get_permalink( $post->ID ) . '" title="'.$post->post_title.'">' . $post->post_title . '</a></li>';
			else
				$html .= '<a href="' . get_permalink( $post->ID ) . '" title="' . $post->post_title . '">' . $post->post_title . '</a><br />';
		}

		// If there is a title for the list
		if ( ! empty($title_li) ) {
			if ( $style == 'list' )
				$html .= '</ul></li>';
		}

		if ( $style == 'list' )
			return '<ul>' . $html . '</ul>';
		else
			return $html;
	}
	else
		return $html;
}

/**
 * Retrieve the posts list by categories' sitemap when shortcode [wpms-categories-posts] is found
 *
 * @param $args - arguments or attributes specified in the shortcode tag. ([wpms-categories-posts child_of=xx ...])
 * @param $content - not used
 * @return the pages sitemap or empty string if not applicable
 */
function wpms_list_categories_posts($args, $content = null) {
	// If current page query is feed URL.
	if ( is_feed() )
		return '';

	if ( is_array( $args ) )
		$r = &$args;
	else
		// Convert string 'category=x&order=...' to array $r
		parse_str( $args, $r );

	$defaults = array(
		'type'				 => 'post',						// get_categories() param
		'show_option_all'    => '',
		'orderby'            => 'name',
		'order'              => 'ASC',
		'show_last_update'   => 0,
		'style'              => 'list',
		'show_count'         => 0,
		'hide_empty'         => 1,
		'use_desc_for_title' => 1,
		'child_of'           => 0,
		'feed'               => '',
		'feed_type'          => '',
		'feed_image'         => '',
		'exclude'            => '',
		'exclude_tree'       => '',
		'include'            => '',
		'hierarchical'       => true,
		'title_li'           => __( 'Categories', 'wpms' ),
		'number'             => NULL,
		'echo'               => 0,							// Keep it in a variable
		'depth'              => 0,
		'current_category'   => 0,
		'pad_counts'         => 0,
		'taxonomy'           => 'category',
		'walker'             => 'Walker_Category_Posts'		// Custom Category Walker
	);

	// Overwrite defaults with custom values
	$r = array_merge( $defaults, $r );	

	if ( ! isset($r['pad_counts']) && $r['show_count'] && $r['hierarchical'] )
		$r['pad_counts'] = true;

	if ( isset($r['show_date']) )
		$r['include_last_update_time'] = $r['show_date'];

	// Extract $args to variables
	extract( $r );

	$categories = get_categories( $r );

	$html = '';

	// If there are categories
	if ( ! empty($categories) ) {
		global $wp_query;

		if ( is_category() )
			$r['current_category'] = $wp_query->get_queried_object_id();

		if ( $hierarchical )
			$depth = 0;  // Walk the full depth.
		else
			$depth = -1; // Flat.

		$html .= sitemap_walk_category_tree( $categories, $depth, $r );
	}

	if ( $style == 'list' )
		$html = '<ul><li>' . __( 'Posts by categories', 'wpms' ) . '<ul>' . $html . '</ul></li></ul>';
	else
		$html = '<h2>' . __( 'Categories and posts', 'wpms' ) . '</h2>' . $html;

	return apply_filters( 'wp_list_categories', $html );
}

/**
 * Gets the HTML list of categories and posts
 *
 * @return the formatted output of posts grouped by categories
 */
function sitemap_walk_category_tree() {
	$walker = new Walker_Category_Posts;

	// Get params ($categories, $depth, $r)
	$args = func_get_args();

	return call_user_func_array( array(&$walker, 'walk'), $args );
}

/**
 * Load textdomain for translations.
 */
function wpms_load_translations() {
	load_plugin_textdomain( 'wpms', null, basename( dirname(__FILE__) ) . '/languages' );
}

// Register a shortcode handler for showing sitemap pages
add_shortcode( 'wpms-pages', 'wpms_list_pages' );
// Register a shortcode handler for showing sitemap posts
add_shortcode( 'wpms-posts', 'wpms_list_posts' );
// Register a shortcode handler for showing sitemap posts grouped by categories
add_shortcode( 'wpms-categories-posts', 'wpms_list_categories_posts' );
// Init translations
add_action( 'init', 'wpms_load_translations' );
?>