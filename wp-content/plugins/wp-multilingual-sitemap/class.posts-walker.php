<?php
/**
 * Create HTML list of categories and posts.
 *
 * @package WPMS
 * @since 0.1
 * @uses Walker
 */
class Walker_Category_Posts extends Walker {
	/**
	 * @see Walker::$tree_type
	 * @since 2.1.0
	 * @var string
	 */
	var $tree_type = 'category';

	/**
	 * @see Walker::$db_fields
	 * @since 2.1.0
	 * @todo Decouple this
	 * @var array
	 */
	var $db_fields = array ('parent' => 'parent', 'id' => 'term_id');

	/**
	 * @see Walker::start_lvl()
	 * @since 2.1.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of category. Used for tab indentation.
	 * @param array $args Will only append content if style argument value is 'list'.
	 */
	function start_lvl(&$output, $depth, $args) {
		if ( 'list' != $args['style'] )
			return;

		$indent = str_repeat("\t", $depth);
		$output .= "$indent<ul class='children'>\n";
	}

	/**
	 * @see Walker::end_lvl()
	 * @since 2.1.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of category. Used for tab indentation.
	 * @param array $args Will only append content if style argument value is 'list'.
	 */
	function end_lvl( &$output, $depth, $args ) {
		if ( 'list' != $args['style'] )
			return;

		$indent = str_repeat( "\t", $depth );
		$output .= "$indent</ul>\n";
	}

	/**
	 * @see Walker::start_el()
	 * @since 2.1.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $category Category data object.
	 * @param int $depth Depth of category in reference to parents.
	 * @param array $args
	 */
	function start_el( &$output, $category, $depth, $args ) {
		extract( $args );

		// Category name
		$cat_name = esc_attr( $category->name );
		$cat_name = apply_filters( 'list_cats', $cat_name, $category );

		// Category link
		$link = '<a href="' . get_term_link( $category, $category->taxonomy ) . '" ';
		if ( $use_desc_for_title == 0 || empty($category->description) )
			$link .= 'title="' . sprintf( __( 'View all posts filed under %s', 'wpms' ), $cat_name ) . '"';
		else
			$link .= 'title="' . esc_attr( strip_tags( apply_filters( 'category_description', $category->description, $category ) ) ) . '"';
		$link .= '>';
		$link .= $cat_name . '</a>';

		if ( (! empty($feed_image)) || (! empty($feed)) ) {
			$link .= ' ';

			if ( empty($feed_image) )
				$link .= '(';

			$link .= '<a href="' . get_term_feed_link( $category->term_id, $category->taxonomy, $feed_type ) . '"';

			if ( empty($feed) )
				$alt = ' alt="' . sprintf( __( 'Feed for all posts filed under %s', 'wpms' ), $cat_name ) . '"';
			else {
				$title = ' title="' . $feed . '"';
				$alt = ' alt="' . $feed . '"';
				$name = $feed;
				$link .= $title;
			}

			$link .= '>';

			if ( empty($feed_image) )
				$link .= $name;
			else
				$link .= '<img src="' . $feed_image . '"' . $alt . $title . ' />';
			$link .= '</a>';
			if ( empty($feed_image) )
				$link .= ')';
		}

		if ( isset($show_count) && $show_count )
			$link .= ' (' . intval( $category->count ) . ')';

		if ( isset($show_date) && $show_date ) {
			$link .= ' ' . gmdate( 'Y-m-d', $category->last_update_timestamp );
		}

		// Output the Parents (Articles (seperator) Sub Cat (seperator) Another Sub Cat)
		$parents = explode( '|', get_category_parents( $category->term_id, true, '|', false ) );
		$catParents = '';
		for ( $i = 0; $i < sizeof( $parents )-1; $i++ ) {
			$catParents .= $parents[$i];
			if ( $i + 2 != sizeof( $parents ) ) {
				$catParents .= " &raquo; ";
			}
		}
		if ( 'list' == $args['style'] )
			$parent = "\t<li><h3>" . $catParents . "</h3>\n";
		else
			$parent = "\t<h3>" . $catParents . "</h3>\n";

		$parentPosts = "";
		$parentPostsCount = 0;

		// Get all children 
		$children = get_term_children( $category->term_id, 'category' );

		// Get all posts of current category
		$posts = get_posts( 'suppress_filters=0&numberposts=-1&category=' . $category->term_id );

		if ( sizeof($posts) > 0 ) {
			foreach ( $posts as $p ) {
				// Exclude children which are assigned to a 'deeper' category
				$postPresent = false;
				if ( sizeof($children) > 0 ) {
					if ( in_category( $children, $p ) ) { $postPresent = true; }
				}
				if ( $postPresent ) { continue; }
				$parentPostsCount += 1;
				if ( 'list' == $args['style'] )
					$parentPosts .= '<li><a href="' . get_permalink( $p->ID ) . '">' . $p->post_title . '</a></li>';
				else
					$parentPosts .= '<a href="' . get_permalink( $p->ID ) . '">' . $p->post_title . '</a>';
			}
			if ( $parentPostsCount > 0 ) {
				if ( 'list' == $args['style'] )
					$output .= $parent . "\n<ul>" . $parentPosts . "\n</ul>";
				else
					$output .= $parent . "\n" . $parentPosts . "\n";
			}
		}
	}

	/**
	 * @see Walker::end_el()
	 * @since 2.1.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $page Not used.
	 * @param int $depth Depth of category. Not used.
	 * @param array $args Only uses 'list' for whether should append to output.
	 */
	function end_el(&$output, $page, $depth, $args) {
		if ( 'list' != $args['style'] )
			return;

		$output .= "</li>\n";
	}

}
?>