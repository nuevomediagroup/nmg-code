<?php
if (!is_admin()) add_action( 'wp_print_scripts', 'woothemes_add_javascript' );

function woothemes_add_javascript() {

	wp_enqueue_script('jquery');    
	wp_enqueue_script( 'superfish', get_template_directory_uri() . '/includes/js/superfish.js', array( 'jquery' ) );
	wp_enqueue_script( 'menu', get_template_directory_uri() . '/includes/js/menu.js', array( 'jquery' ) );
	wp_enqueue_script( 'general', get_template_directory_uri() . '/includes/js/general.js', array( 'jquery' ) );
	wp_enqueue_script( 'slides', get_template_directory_uri() . '/includes/js/slides.min.jquery.js', array( 'jquery' ) );
	
	// Load video sorting JavaScript on custom taxonomy and post type archive screens.
	if ( is_tax() || is_post_type_archive() ) {
		wp_enqueue_script( 'woo-video-sorting', get_template_directory_uri() . '/includes/js/video-sorting.js', array( 'jquery' ) );
	}
	
} // End woothemes_add_javascript()
?>