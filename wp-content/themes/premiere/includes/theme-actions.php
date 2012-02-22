<?php 

/*-----------------------------------------------------------------------------------

TABLE OF CONTENTS

- Add custom styling to HEAD
- Add custom typograhpy to HEAD
- Add layout to body_class output

-----------------------------------------------------------------------------------*/


add_action('woo_head','woo_custom_styling');			// Add custom styling to HEAD
add_action('woo_head','woo_custom_typography');			// Add custom typography to HEAD
add_filter('body_class','woo_layout_body_class');		// Add layout to body_class output


/*-----------------------------------------------------------------------------------*/
/* Add Custom Styling to HEAD */
/*-----------------------------------------------------------------------------------*/
if (!function_exists('woo_custom_styling')) {
	function woo_custom_styling() {
	
		global $woo_options;
		
		$output = '';
		// Get options
		$body_color = $woo_options['woo_body_color'];
		$body_img = $woo_options['woo_body_img'];
		$body_repeat = $woo_options['woo_body_repeat'];
		$body_position = $woo_options['woo_body_pos'];
		$link = $woo_options['woo_link_color'];
		$hover = $woo_options['woo_link_hover_color'];
		$button = $woo_options['woo_button_color'];
			
		// Add CSS to output
		if ($body_color)
			$output .= 'body {background:'.$body_color.'}' . "\n";
			
		if ($body_img)
			$output .= 'body {background-image:url('.$body_img.')}' . "\n";

		if ($body_img && $body_repeat && $body_position)
			$output .= 'body {background-repeat:'.$body_repeat.'}' . "\n";

		if ($body_img && $body_position)
			$output .= 'body {background-position:'.$body_position.'}' . "\n";

		if ($link)
			$output .= 'a:link, a:visited {color:'.$link.'}' . "\n";

		if ($hover)
			$output .= 'a:hover, .post-more a:hover, .post-meta a:hover, .post p.tags a:hover {color:'.$hover.'}' . "\n";

		if ($button) {
			$output .= 'a.button, a.comment-reply-link, #commentform #submit, #contact-page .submit {background:'.$button.';border-color:'.$button.'}' . "\n";
			$output .= 'a.button:hover, a.button.hover, a.button.active, a.comment-reply-link:hover, #commentform #submit:hover, #contact-page .submit:hover {background:'.$button.';opacity:0.9;}' . "\n";
		}
		
		// Output styles
		if (isset($output) && $output != '') {
			$output = strip_tags($output);
			$output = "<!-- Woo Custom Styling -->\n<style type=\"text/css\">\n" . $output . "</style>\n";
			echo $output;
		}
			
	}
} 

/*-----------------------------------------------------------------------------------*/
/* Add custom typograhpy to HEAD */
/*-----------------------------------------------------------------------------------*/
if (!function_exists('woo_custom_typography')) {
	function woo_custom_typography() {
	
		// Get options
		global $woo_options;
				
		// Reset	
		$output = '';
		
		// Add Text title and tagline if text title option is enabled
		if ( $woo_options['woo_texttitle'] == "true" ) {		
			
			if ( $woo_options['woo_font_site_title'] )
				$output .= '#logo .site-title a {'.woo_generate_font_css($woo_options['woo_font_site_title']).'}' . "\n";	
			if ( $woo_options['woo_font_tagline'] )
				$output .= '#tagline .site-description {'.woo_generate_font_css($woo_options['woo_font_tagline'] ).'; line-height:28px; }' . "\n";	
		}

		if ( $woo_options['woo_typography'] == "true") {
			
			if ( $woo_options['woo_font_body'] )
				$output .= 'body { '.woo_generate_font_css($woo_options['woo_font_body'], '1.5').' }' . "\n";	

			if ( $woo_options['woo_font_nav'] )
				$output .= '#navigation .nav a { '.woo_generate_font_css($woo_options['woo_font_nav'] ).' }' . "\n";	

			if ( $woo_options['woo_font_post_title'] )
				$output .= '.post .title { '.woo_generate_font_css($woo_options['woo_font_post_title']).' }' . "\n";	
				$output .= '#feat-title .title { font-family:'.stripslashes($woo_options['woo_font_post_title']['face']).'; }' . "\n";	
		
			if ( $woo_options['woo_font_post_meta'] )
				$output .= '.post-meta { '.woo_generate_font_css($woo_options['woo_font_post_meta']).' }' . "\n";	

			if ( $woo_options['woo_font_post_entry'] )
				$output .= '.entry, .entry p { '.woo_generate_font_css($woo_options['woo_font_post_entry'], '1.5').' } h1, h2, h3, h4, h5, h6 { font-family:'.stripslashes($woo_options['woo_font_post_entry']['face']).'}'  . "\n";	

			if ( $woo_options['woo_font_widget_titles'] )
				$output .= '.widget h3 { '.woo_generate_font_css($woo_options['woo_font_widget_titles']).' }'  . "\n";	

		// Add default typography Google Font
		} else {
		
			$woo_options['woo_just_face'] = array('face' => 'PT Sans');
			$output .= 'h1, h2, h3, h4, h5, h6, .widget h3, .post .title, .nav a, .section .post .title, .archive_header, .site-description, #tabs-home ul.wooTabs li a { '.woo_generate_font_css($woo_options['woo_just_face']).' }' . "\n";			
		}
		
		// Output styles
		if (isset($output) && $output != '') {
		
			// Enable Google Fonts stylesheet in HEAD
			if (function_exists('woo_google_webfonts')) woo_google_webfonts();
			
			$output = "<!-- Woo Custom Typography -->\n<style type=\"text/css\">\n" . $output . "</style>\n\n";
			echo $output;
			
		}
			
	}
} 

// Returns proper font css output
if (!function_exists( 'woo_generate_font_css')) {
	function woo_generate_font_css($option, $em = '1') {

		// Test if font-face is a Google font
		global $google_fonts;
		foreach ( $google_fonts as $google_font ) {
					
			// Add single quotation marks to font name and default arial sans-serif ending
			if ( $option[ 'face' ] == $google_font[ 'name' ] )
				$option[ 'face' ] = "'" . $option[ 'face' ] . "', arial, sans-serif";		
		
		} // END foreach
		
		if ( !@$option["style"] && !@$option["size"] && !@$option["unit"] && !@$option["color"] )
			return 'font-family: '.stripslashes($option["face"]).';'; 
		else
			return 'font:'.$option["style"].' '.$option["size"].$option["unit"].'/'.$em.'em '.stripslashes($option["face"]).';color:'.$option["color"].';';
	}
}


// Output stylesheet and custom.css after custom styling
remove_action('wp_head', 'woothemes_wp_head');
add_action('woo_head', 'woothemes_wp_head');


/*-----------------------------------------------------------------------------------*/
/* Add layout to body_class output */
/*-----------------------------------------------------------------------------------*/
if (!function_exists('woo_layout_body_class')) {
	function woo_layout_body_class($classes) {
		
		global $woo_options;
		$layout = $woo_options['woo_site_layout'];

		// Set main layout on post or page
		if ( is_singular() ) {
			global $post;
			$single = get_post_meta($post->ID, '_layout', true);
			if ( $single != "" AND $single != "layout-default" ) 
				$layout = $single;
		}
		
		// Add layout to $woo_options array for use in theme
		$woo_options['woo_layout'] = $layout;
		
		// Add classes to body_class() output 
		$classes[] = $layout;
		return $classes;						
					
	}
}

/*-----------------------------------------------------------------------------------*/
/* Slider JS Header code */
/*-----------------------------------------------------------------------------------*/
add_filter('woo_head', 'woo_slider_options');
function woo_slider_options() { 
	
	global $woo_options;
	
	if ($woo_options[ 'woo_slider' ] == 'true' && is_home() && !is_paged()): ?>
	
		<script type="text/javascript">
			jQuery(document).ready(function(){
			
				jQuery("#slides").slides({
					preload: true, 
					preloadImage: '<?php echo get_template_directory_uri() . '/images/loading.gif'; ?>', 
					autoHeight: true,
					effect: '<?php echo $woo_options[ 'woo_slider_effect' ]; ?>',
					<?php if ($woo_options[ 'woo_slider_random' ] == "true"): ?>			
					randomize: true,
					<?php endif; ?>
					<?php if ($woo_options[ 'woo_slider_hover' ] == "true"): ?>			
					hoverPause: true,
					<?php endif; ?>
					<?php if ($woo_options[ 'woo_slider_auto' ] == "true"): ?>
					play: <?php echo $woo_options[ 'woo_slider_interval' ] * 1000; ?>,
					<?php endif; ?>			
					slideSpeed: <?php echo $woo_options[ 'woo_slider_speed' ] * 1000; ?>,
					crossfade: true,
					<?php if ($woo_options[ 'woo_slider_nextprev' ] == "true"): ?>
					generateNextPrev: true,
					<?php endif; ?>
					<?php if ($woo_options[ 'woo_slider_pagination' ] == "true"): ?>
					generatePagination: true, 
					<?php endif; ?>
					<?php if ($woo_options[ 'woo_slider_pagination' ] == "false"): ?>
					generatePagination: false, 
					<?php endif; ?>
					slidesLoaded: function () { jQuery( '#slides .slides_control' ).css( 'height', jQuery( '#slides .slides_control .slide:first' ).height() ); }
				});
				
			});
		</script>
				
	<?php endif;

}

/*-----------------------------------------------------------------------------------*/
/* END */
/*-----------------------------------------------------------------------------------*/
?>