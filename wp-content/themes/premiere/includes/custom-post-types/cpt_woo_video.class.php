<?php
/*-----------------------------------------------------------------------------------

CLASS INFORMATION

Description: Custom post type-specific functions for the WooThemes "woo_video" post type.
Date Created: 2011-02-02.
Author: Matty.
Since: 3.0


TABLE OF CONTENTS

- var $singular_label
- var $token
- var $rewrite_path
- var $description

- var $plugin_path
- var $plugin_url

- var $posts
- var $terms
- var $first_post

- function Woo_PostType_Video (constructor)
- function init ()
- function register_post_type ()
- function updated_messages ()
- function add_help_text ()
- function register_custom_columns_filters ()
- function add_custom_column_headings ()
- function add_custom_column_data ()
- function enqueue_scripts ()
- function register_enqueues ()
- function ajax_functions ()
-----------------------------------------------------------------------------------*/

	class Woo_PostType_Video {
		
		/*----------------------------------------
	 	  Class Variables
	 	  ----------------------------------------
	 	  
	 	  * Setup of variable placeholders, to be
	 	  * populated when the constructor runs.
	 	----------------------------------------*/
		
		var $singular_label;
		var $token;
		var $rewrite_path;
		var $description;
		
		var $tax_singular_label;
		var $tax_token;
		var $tax_rewrite_path;
		var $tax_description;
		var $tax_post_types_supported;
		
		var $plugin_path;
		var $plugin_url;
		var $plugin_prefix;
		
		/*----------------------------------------
	 	  Woo_PostType_Video()
	 	  ----------------------------------------
	 	  
	 	  * Constructor function.
	 	  * Sets up the class and registers
	 	  * variable action hooks.
	 	  
	 	  * Params:
	 	  * - String $plugin_path
	 	  * - String $plugin_url
	 	  * - String $plugin_prefix
	 	----------------------------------------*/
		
		function Woo_PostType_Video ( $plugin_path, $plugin_url, $plugin_prefix ) {
		
			$this->plugin_path = $plugin_path;
			$this->plugin_url = $plugin_url;
			$this->plugin_prefix = $plugin_prefix;
		
			$this->init();
			
		} // End Woo_PostType_Video()
		
		/*----------------------------------------
	 	  init()
	 	  ----------------------------------------
	 	  
	 	  * This guy runs the show.
	 	  * Rocket boosters... engage!
	 	----------------------------------------*/
		
		function init () {
			
			// Custom Post Type.
			
			$this->singular_label = __( 'Video', 'woothemes' );
			$this->plural = __( 'Videos', 'woothemes' );
			$this->token = 'woo_video';
			$this->rewrite_path = 'videos';
			$this->description = __( 'An archive of videos posted to your website.', 'woothemes' );
			
			// Custom Taxonomy.
			$this->tax_singular_label = __( 'Video Category', 'woothemes' );
			$this->tax_plural = __( 'Video Categories', 'woothemes' );
			$this->tax_token = 'woo_video_category';
			$this->tax_rewrite_path = 'video-archive';
			$this->tax_description = __( 'A taxonomy used to archive videos on your website.', 'woothemes' );
			$this->post_types_supported = array( 'woo_video' );
			
			// $this->register_post_type();
			add_action( 'init', array( &$this, 'register_post_type' ) );
			$this->register_custom_taxonomies();
			
			// Administration action and filter hooks for the administration area
			add_filter( 'post_updated_messages', array( &$this, 'updated_messages' ) );
			add_action( 'contextual_help', array( &$this, 'add_help_text' ), 10, 3 );
			
			add_action( 'admin_print_styles', array( &$this, 'enqueue_styles' ) );
			
			// Add woo_video posts to `post_tag` archives
			add_filter( 'pre_get_posts', array( &$this, 'show_posts_in_tag_archive' ) );
			
			// Register custom columns on the `List` screen			
			$this->register_custom_columns_filters();
			
		} // End init()
		
		/*----------------------------------------
	 	  Utility Functions
	 	  ----------------------------------------
	 	  
	 	  * These functions are used within this
	 	  * class as helpers for other functions.
	 	----------------------------------------*/
	 	
	 	/*----------------------------------------
	 	  register_post_type()
	 	  ----------------------------------------
	 	  
	 	  * A helper function to register our
	 	  * custom post type.
	 	----------------------------------------*/
		
		function register_post_type () {
			
			$labels = array(
						'name' => sprintf( _x( '%s', 'post type general name' ), $this->plural ),
						'singular_name' => sprintf( _x( '%s', 'post type singular name' ), $this->singular_label ),
						'add_new' => _x( 'Add New', 'woothemes' ),
						'add_new_item' => sprintf( __( 'Add New %s', 'woothemes' ), $this->singular_label ),
						'edit_item' => sprintf( __( 'Edit %s', 'woothemes' ), $this->singular_label ),
						'new_item' => sprintf( __( 'New %s', 'woothemes' ), $this->singular_label ),
						'view_item' => sprintf( __( 'View %s', 'woothemes' ), $this->singular_label ),
						'search_items' => sprintf( __( 'Search %s', 'woothemes' ), $this->plural ),
						'not_found' =>  sprintf( __('No %s found', 'woothemes' ), strtolower( $this->plural ) ),
						'not_found_in_trash' => sprintf( __('No %s found in Trash', 'woothemes' ), strtolower( $this->plural ) ), 
						'parent_item_colon' => sprintf( __( 'Parent %s:', 'woothemes' ), $this->singular_label ),
						'menu_name' => $this->plural
					);
					
			$args = array(
						'labels' => $labels,
						'public' => true,
						'publicly_queryable' => true,
						'show_ui' => true, 
						'show_in_menu' => true, 
						'exclude_from_search' => false,
						'description' => __( $this->description, 'woothemes' ),
						'menu_position' => 20,
						'menu_icon' => trailingslashit( $this->plugin_url ) . 'functions/images/option-icon-media' . '.png', 
						'query_var' => true,
						'rewrite' => array( 'slug' => $this->rewrite_path, 'with_front' => false ),
						// 'capability_type' => $this->capability_type,
						// 'capabilities' => $this->capabilities,
						'has_archive' => true, 
						'hierarchical' => false,
						'menu_position' => null, 
						'supports' => array(
										'title', 
										'editor', 
										'author', 
										'thumbnail', 
										'excerpt', 
										'comments', 
										// 'custom-fields'
										), 
						'taxonomies' => array( 'woo_video_category', 'post_tag' ), 
						'can_export' => true, 
						'show_in_nav_menus' => true, 
						//'register_meta_box_cb' => 'your_callback_function_name', // Custom callback function for after the meta boxes have been set up.
						'permalink_epmask' => EP_PERMALINK
					); 
			
			register_post_type( $this->token, $args );

		} // End register_post_type()
		
		/*----------------------------------------
	 	  register_custom_taxonomies()
	 	  ----------------------------------------
	 	  
	 	  * A helper function to call the
	 	  * `register_single_custom_taxonomy`
	 	  * function potentially multiple times.
	 	----------------------------------------*/
		
		function register_custom_taxonomies () {

	 		$this->register_single_custom_taxonomy ( $this->tax_token, $this->tax_singular_label, $this->tax_plural, $this->tax_post_types_supported, true, array( 'slug' => 'video-category' ) );
	 		
	 	} // End register_custom_taxnomies()
	 	
	 	/*----------------------------------------
	 	  register_single_custom_taxonomy()
	 	  ----------------------------------------
	 	  
	 	  * A wrapper function to call register
	 	  * a custom taxonomy.
	 	----------------------------------------*/
	 	
	 	function register_single_custom_taxonomy ( $token, $single, $plural, $post_types = array('woo_video'), $hierarchical = true, $rewrite = true ) {	
	 	
	 		register_taxonomy(
								$token, 
								$post_types, 
								array(
										"hierarchical" => $hierarchical, 
										"rewrite" => $rewrite, 
										"query_var" => true, 
										"public" => true,
										"labels" => array(
											'name' => __( $plural, 'woothemes' ), 
											'singular_name' => __( $single, 'woothemes' ), 
											'search_items' => __( 'Search ' . $plural, 'woothemes' ), 
											'popular_items' => __( 'Popular ' . $plural, 'woothemes' ), 
											'all_items' => __( 'All ' . $plural, 'woothemes' ), 
											'parent_item' => __( 'Parent ' . $single, 'woothemes' ), 
											'parent_item_colon' => __( 'Parent ' . $single . ':', 'woothemes' ), 
											'edit_item' => __( 'Edit ' . $single, 'woothemes' ), 
											'update_item' => __( 'Update ' . $single, 'woothemes' ), 
											'add_new_item' => __( 'Add New ' . $single, 'woothemes' ), 
											'new_item_name' => __( 'New ' . $single, 'woothemes' )
											
										),
										// "capabilities" => array()
									)
							);
	 		
	 	} // End register_single_custom_taxonomy()
		
		/*----------------------------------------
	 	  updated_messages()
	 	  ----------------------------------------
	 	  
	 	  * Customise the update messages for our
	 	  * custom post type.
	 	----------------------------------------*/
		
		function updated_messages ( $messages ) {
		
			$messages[$this->token] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => $this->singular_label . ' updated. <a href="' . esc_url( get_permalink($post_ID) ) . '">View ' . $this->singular_label . '</a>',
			2 => __( 'Custom field updated.', 'woothemes' ),
			3 => __( 'Custom field deleted.', 'woothemes' ),
			4 => __( $this->singular_label . ' updated.', 'woothemes' ),
			/* translators: %s: date and time of the revision */
			5 => isset($_GET['revision']) ? $this->singular_label . ' restored to revision from ' . wp_post_revision_title( (int) $_GET['revision'], false ) : false,
			6 => $this->singular_label . ' published.',
			7 => __( $this->singular_label . ' saved.', 'woothemes'),
			8 => $this->singular_label . ' submitted. <a target="_blank" href="' . esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) . '">Preview ' . strtolower( $this->singular_label ) . '</a>',
			9 => $this->singular_label . ' scheduled for: <strong>' . date_i18n( __( 'M j, Y @ G:i' , 'woothemes'), strtotime( $post->post_date ) ) . '</strong>. <a target="_blank" href="' . esc_url( get_permalink($post_ID) ) . '">Preview ' .strtolower( $this->singular_label ) . '</a>',
			10 => $this->singular_label . ' draft updated. <a target="_blank" href="' . esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) . '">Preview ' . strtolower( $this->singular_label ) . '</a>'
			);
			
			return $messages;
			
		} // End updated_messages()
		
		/*----------------------------------------
	 	  add_help_text()
	 	  ----------------------------------------
	 	  
	 	  * Add contextual help text on our
	 	  * custom post type's `posts list` and
	 	  * `add/edit` screens.
	 	----------------------------------------*/
		
		function add_help_text ( $contextual_help, $screen_id, $screen ) { 
		  
		  // $contextual_help .= var_dump($screen); // use this to help determine $screen->id
		  
		  if ( $this->token == $screen->id ) {
		  
		    $contextual_help =
		      '<p>' . __('Things to remember when adding or editing a video:', 'woothemes') . '</p>' .
		      '<ul>' .
		      '<li>' . __('Specify the name of the video in the `Title` field.', 'woothemes') . '</li>' .
		      '<li>' . __('Paste the video\'s embed code in the "Embed Code" field.', 'woothemes') . '</li>' .
		      '</ul>';
		  
		  } elseif ( 'edit-' . $this->token == $screen->id ) {
		  
		    $contextual_help = 
		      '<p>' . __('This screen shows a list of all videos that have been added.', 'woothemes') . '</p>' . 
		      '<p>' . __('To edit a video, click it\'s name.', 'woothemes') . '</p>';
		  
		  } // End IF Statement
		  
		  return $contextual_help;
		  
		} // End add_help_text()
		
		/*----------------------------------------
	 	  register_custom_columns_filters()
	 	  ----------------------------------------
	 	  
	 	  * Register our custom post type's
	 	  * custom column headings and data hooks.
	 	----------------------------------------*/
		
		function register_custom_columns_filters () {
		
			add_filter( 'manage_edit-' . $this->token . '_columns', array( __CLASS__, 'add_custom_column_headings' ), 10, 1 );
			add_action( 'manage_posts_custom_column', array( __CLASS__, 'add_custom_column_data' ), 10, 2);
			
		} // End register_custom_columns_filters()
		
		/*----------------------------------------
	 	  add_custom_column_headings()
	 	  ----------------------------------------
	 	  
	 	  * Add custom column headings on
	 	  * the `posts list` page of our custom
	 	  * post type.
	 	----------------------------------------*/
		
		function add_custom_column_headings ( $defaults ) {
			
			$new_columns['cb'] = '<input type="checkbox" />';
 			// $new_columns['id'] = __( 'ID' );
  			$new_columns['title'] = _x( 'Video Title', 'column name' );
			$new_columns['video-categories'] = __( 'Video Categories', 'woothemes' );
			$new_columns['tags'] = _x('Video Tags', 'column name');
			$new_columns['author'] = __( 'Video Posted By', 'woothemes' );
	 		$new_columns['date'] = _x('Posted On', 'column name');
	 
			return $new_columns;
			
		} // End add_custom_column_headings()
		
		/*----------------------------------------
	 	  add_custom_column_data()
	 	  ----------------------------------------
	 	  
	 	  * Add data for our custom columns on
	 	  * the `posts list` page of our custom
	 	  * post type.
	 	----------------------------------------*/
		
		function add_custom_column_data ( $column_name, $id ) {
		
			global $wpdb, $post;
			
			$custom_values = get_post_custom( $id );
			
			switch ($column_name) {
			
				case 'id':
				
					echo $id;
				
				break;
				
				case 'video-categories':
				
					$terms = get_the_term_list( $post->ID, 'woo_video_category', '', ', ', '' );
					
					if ( $terms ) { echo $terms; } else { echo __( 'No Video Categories', 'woothemes' ); }
				
				break;
				
				default:
				break;
			
			} // End SWITCH Statement
			
		} // End add_custom_column_data()
		
		/*----------------------------------------
	 	  enqueue_styles()
	 	  ----------------------------------------
	 	  
	 	  * Enqueue various CSS files.
	 	----------------------------------------*/
		
		function enqueue_styles () {
			
			global $pagenow;
			
			$allowed_pages = array( 'edit.php', 'post-new.php', 'edit-tags.php' );
			
			if ( in_array( $pagenow, $allowed_pages ) && @$_GET['post_type'] == $this->token ) {
			
				wp_register_style( 'woo-video-admin', get_template_directory_uri() . '/includes/custom-post-types/css/woo_video.css' );
			
				wp_enqueue_style( 'woo-video-admin' );
			
			}
			
		} // End enqueue_styles()
	 	
	 	/*----------------------------------------
	 	  get_vimeo_info()
	 	  ----------------------------------------
	 	  
	 	  * Video info from Vimeo for a video.
	 	----------------------------------------*/
	 	
		function get_vimeo_info ( $id, $info = 'thumbnail_large' ) {
		
		    if (!function_exists('curl_init')) {
		    
		    	return null;
		    
		    } else {
			
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, "http://vimeo.com/api/v2/video/$id.php");
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_TIMEOUT, 10);
				$output = unserialize(curl_exec($ch));
				$output = $output[0][$info];
				curl_close($ch);
				return $output;
				
		    } // End IF Statement
		    
		} // End get_vimeo_info()

		/*----------------------------------------
	 	  show_posts_in_tag_archive()
	 	  ----------------------------------------
	 	  
	 	  * Make sure these posts display in
	 	  * `post_tag` archives as well as other
	 	  * post types.
	 	----------------------------------------*/

		function show_posts_in_tag_archive ( $query ) {
		
			if ( ! is_admin() && $query->is_tag ) { $query->set( 'post_type', array( 'post', $this->token ) ); }
		
			return $query;
		
		} // End show_posts_in_tag_archive()
		
	} // End Class Woo_PostType_Video
?>