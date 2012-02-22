<?php 
	global $woo_options;
	$featposts = $woo_options['woo_slider_entries']; // Number of featured entries to be shown
	$woo_featured_tags = $woo_options['woo_slider_tags'];
?>
<div id="slides">
	<?php if ( ( $woo_featured_tags != '' ) && ( isset($woo_featured_tags) ) ) { ?>
	<div class="slides_container"<?php if ( $featposts > 1 ) echo ' style="display:none;"'; ?>>
	<?php
		$feat_tags_array = explode(',',$woo_featured_tags); // Tags to be shown
        $tag_array = array();
        foreach ($feat_tags_array as $tags){ 
			$tag = get_term_by( 'name', trim($tags), 'post_tag', 'ARRAY_A' );
			if ( $tag['term_id'] > 0 )
				$tag_array[] = $tag['term_id'];
		} // End For Loop
   	?>
	<?php $args = array( 'post_type' => 'woo_video', 'numberposts' => $featposts, 'post_status' => 'publish', 'tag__in' => $tag_array, 'suppress_filters' => false );  ?>
    <?php $slides = get_posts( $args ); ?>
    <?php if (!empty($slides)) { ?>
    
    	<?php foreach($slides as $post) { setup_postdata($post); ?>
    	
    		<?php // Post Meta
    		$content = get_the_excerpt();
    		$layout = get_post_meta($post->ID, 'slide_layout', true);
    		$output_type = get_post_meta($post->ID, 'slider_output', true);
    		if ( $output_type == '') { $output_type = 'image'; } 
    		if ( $output_type == 'video' ) { $output_data = get_post_meta($post->ID, 'embed', true); } else { $output_data = get_post_meta($post->ID, 'image', true); } 
			if ( $layout == "" ) { $layout == "left"; }
			// Output Classes
			if ( $output_data == '' && $content != '' ) { 
				$slide_content_class = 'text no-image';
			} elseif ( $content == '' && $output_data != '' ) {
				$slide_content_class = 'text no-content';
			} else {
				$slide_content_class = 'text';
			} 
			
			if ($layout == "right") {
				$slide_content_class .= ' right';
			} elseif ($layout == "top") {
				$slide_content_class .= ' top';
			} elseif ($layout == "bottom") {
				$slide_content_class .= ' bottom';
			} else {
			
			}
			
			?>
			<?php if ( $output_type == 'video' && $content == '' && $output_data != '' ) { ?>
			<div class="slide <?php if ( $featposts == 1 ) { echo 'single-slide'; } ?>">
    			
    			<?php if ($woo_options[ 'woo_slider_title' ] == "true" ) { ?>
    			
    			<div class="<?php echo $slide_content_class; ?>">
    				<h2><a href="<?php echo get_permalink($post->ID); ?>"><?php the_title(); ?></a></h2>
    				<a class="slide-overlay-toggle">#</a>
    			</div>
    			
    		  	<?php } ?>
    			
    			<div class="image">
    	
    				<?php echo apply_filters( 'the_content', woo_embed( 'key=embed&width=760' ) ); ?>
    	
    			</div>
      
    		</div>
			<?php } elseif ( $output_data == '' && $content != '' ) { ?>
			<div class="slide <?php if ( $featposts == 1 ) { echo 'single-slide'; } ?>">
				
				<?php if ($woo_options[ 'woo_slider_title' ] == "true" || $woo_options[ 'woo_slider_content' ] == "true" ) { ?>
				
				<div class="<?php echo $slide_content_class; ?>">
    				<?php if ($woo_options[ 'woo_slider_title' ] == "true" ) { ?><h2><a href="<?php echo get_permalink($post->ID); ?>"><?php the_title(); ?></a></h2><?php } ?>
    		  		<?php if ($woo_options[ 'woo_slider_content' ] == "true" ) { ?><p><?php echo $content; ?></p><?php } ?>
    			</div>
    			<?php } ?>
    			
    		</div>
			<?php } elseif ( $content != '' && $output_data != '' && $output_data == 'image' ) { ?>
    		<div class="slide <?php if ( $featposts == 1 ) { echo 'single-slide'; } ?>">
    			
    			<?php if ($woo_options[ 'woo_slider_title' ] == "true" || $woo_options[ 'woo_slider_content' ] == "true" ) { ?>
				
    			<div class="<?php echo $slide_content_class; ?>">
    				<?php if ($woo_options[ 'woo_slider_title' ] == "true" ) { ?><h2><a href="<?php echo get_permalink($post->ID); ?>"><?php the_title(); ?></a></h2><?php } ?>
    		  		<?php if ($woo_options[ 'woo_slider_content' ] == "true" ) { ?><p><?php echo $content; ?></p><?php } ?>
    		  		<a class="slide-overlay-toggle">#</a>
    			</div>
    			
    			<?php } ?>
    			
    			<div class="image">
    			
    				<?php echo woo_image_vimeo('key=image&width=760&id=' . $post->ID); ?>
    			
    			</div>
    		  
    		</div>
    		<?php } elseif ( $output_data != '' && $output_type == 'video' ) { ?>
    		<div class="slide <?php if ( $featposts == 1 ) { echo 'single-slide'; } ?>">
    		
    			<?php if ($woo_options[ 'woo_slider_title' ] == "true" || $woo_options[ 'woo_slider_content' ] == "true" ) { ?>
				<div class="<?php echo $slide_content_class; ?>">
    				<?php if ($woo_options[ 'woo_slider_title' ] == "true" ) { ?><h2><a href="<?php echo get_permalink($post->ID); ?>"><?php the_title(); ?></a></h2><?php } ?>
    		  		<?php if ($woo_options[ 'woo_slider_content' ] == "true" ) { ?><p><?php echo $content; ?></p><?php } ?>
    		  		<a class="slide-overlay-toggle">#</a>
    			</div>
    			<?php } ?>
    	
    			<div class="image">
    	
    				<?php echo woo_embed( 'key=embed&width=760' ); ?>
    	
    			</div>
    		  
    		</div>
    		<?php } else { ?>
    		<div class="slide <?php if ( $featposts == 1 ) { echo 'single-slide'; } ?>">
    		
    			<?php if ($woo_options[ 'woo_slider_title' ] == "true" || $woo_options[ 'woo_slider_content' ] == "true" ) { ?>
				
    			<div class="<?php echo $slide_content_class; ?>">
    				<?php if ($woo_options[ 'woo_slider_title' ] == "true" ) { ?><h2><a href="<?php echo get_permalink($post->ID); ?>"><?php the_title(); ?></a></h2><?php } ?>
    		  		<?php if ($woo_options[ 'woo_slider_content' ] == "true" ) { ?><p><?php echo $content; ?></p><?php } ?>
    		  		<a class="slide-overlay-toggle">#</a>
    			</div>
    			
    			<?php } ?>
    			
    			<div class="image">
    			
    				<?php echo woo_image_vimeo('key=image&width=760&id=' . $post->ID); ?>
    			
    			</div>
    		  
    		</div>
    		<?php } ?>
    		
    	<?php } // End For Loop ?>
    
    <?php } // End If Statement ?>
	</div>
	 <?php } else { ?>    
		<p class="woo-sc-box note no-slide"><?php _e('Please setup Slider tag(s) in your options panel. You must setup tags that are used on active video posts.','woothemes'); ?></p>
	<?php } ?>
</div>