<?php get_header(); ?>
<?php global $woo_options; ?>

    <?php 
    	// Featured Video
    	if ( get_option('woo_slider') != "true" AND get_option('woo_exclude') )
    		update_option("woo_exclude", ""); 
    	
    	if ( !$paged && get_option('woo_slider') == 'true' ) 
    		get_template_part('includes/featured' ); 
	?>

	<?php   
		// Exclude stored duplicates 
		$exclude = get_option('woo_exclude'); 
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1; 
		$args = array(	'post__not_in' => $exclude, 
						'paged'=> $paged );
						
		$args['post_type'] = 'woo_video';
		$args['posts_per_page'] = $woo_options['woo_recent_posts'];
		
		query_posts($args);			
	?>

    <div id="content-wrap">   

 		<div id="tabs-home" class="col-full">
           
            <ul class="wooTabs">
                <li class="latest"><a href="#home-tab-latest"><?php _e( 'Latest Videos', 'woothemes' ); ?></a></li>
                <li class="popular"><a href="#home-tab-pop"><?php _e( 'Most Popular', 'woothemes' ); ?></a></li>
                <li class="tags"><a href="#home-tab-tags"><?php _e( 'Popular Keywords', 'woothemes' ); ?></a></li>
            </ul>
            
            <div class="clear"></div>
            
            <div class="boxes box inside">
                        
                <div id="home-tab-latest" class="list">

				<?php
		            $counter = 0;            
        
		            if ( have_posts() ) {
		            	while ( have_posts() ) {
		            	the_post(); $counter++;
		        ?>  
		                                                                  
		            <div class="post block">
		
		                <div class="tab-image-block">
		                	<?php
		                		$args = 'key=image&width=' . $woo_options['woo_thumb_w'] . '&height=' . $woo_options['woo_thumb_h'] . '&class=thumbnail&link=img&return=true&id=' . get_the_ID();
		                		
		                		echo '<a href="' . get_permalink( get_the_ID() ) . '" title="' . the_title_attribute( array( 'echo' => 0 ) ) . '">' . woo_image_vimeo( $args ) . '</a>';
		                	?>
		                </div> 
		                
		                <h2 class="title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
		                
		                <span class="date"><?php the_time( get_option( 'date_format' ) ); ?></span>
		                                     
		            </div><!-- /.post -->
		            
		            
		            <?php if($counter % 4 == 0) { ?>
		            	<div class="fix"></div>
		            <?php } ?>
		                                                
		        <?php
			        	} // End WHILE Loop
			        	echo '<div class="fix"></div>';
			        	woo_pagination();
			        } else {
		        ?>
        
		            <div class="post">
		                <p><?php _e('Sorry, no posts matched your criteria.', 'woothemes'); ?></p>
		            </div><!-- /.post -->
		        
		        <?php } // End IF Statement ?>

					<div class="clear"></div>

				</div><!-- #tab-latest -->
	    				
		        <div id="home-tab-pop" class="list">            
					<?php
						global $post;
						$count = 0;
						
						$args = array(
										'post_type' => 'woo_video', 
										'orderby' => 'comment_count', 
										'order' => 'DESC', 
										'posts_per_page' => $woo_options['woo_popular_entries']
									 );
									 
						add_filter('pre_get_posts', 'woo_popular_bycomments' );
						
						function woo_popular_bycomments ( $query ) {
						
							$query->set('orderby', 'comment_count');
							$query->set('order', 'DESC');
							
							$query->parse_query();
							
							return $query;
						
						} // End woo_popular_bycomments()
						
						$popular = get_posts( $args );
						
						remove_filter('pre_get_posts', 'woo_popular_bycomments' );
						
						foreach($popular as $post) {
							setup_postdata($post);
							$count++;
					?>
		            <div class="post block">
		
		                <div class="tab-image-block">
		                	<?php
		                		$args = 'key=image&width=' . $woo_options['woo_thumb_w'] . '&height=' . $woo_options['woo_thumb_h'] . '&class=thumbnail&link=img&return=true&id=' . get_the_ID();
		                		
		                		echo '<a href="' . get_permalink( get_the_ID() ) . '" title="' . the_title_attribute( array( 'echo' => 0 ) ) . '">' . woo_image_vimeo( $args ) . '</a>';
		                	?>
		                </div>
		                
		                <h2 class="title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
		                
		                <span class="date"><?php the_time( get_option( 'date_format' ) ); ?></span>
		                                     		
		           </div><!-- /.post -->
				
					<?php if ( $count == 4 ) { $count = 0; echo '<div class="clear"></div>'; } ?>
			
					<?php } // End FOREACH Loop ?>
		
					<div class="clear"></div>
			
				</div><!-- #tab-pop -->
				
		        <div id="home-tab-tags" class="list">
		        	<?php
		        		$tags_to_include = woo_get_tags_by_post_type( 'woo_video' );
		        		$args = array( 'number' => 0, 'smallest' => 12, 'largest' => 28 );
		        		
		        		if ( $tags_to_include ) { $args['include'] = $tags_to_include; } 
		        	?>
		            <?php wp_tag_cloud( $args ); ?>
		        </div><!-- #tab-tags -->				
                  
	    	</div><!-- /.boxes -->
				
	    </div><!-- /wooTabs -->

	</div><!-- #content-wrap -->

<script type="text/javascript">
jQuery(document).ready(function(){
	// UL = .wooTabs
	// Tab contents = .inside
	
	var tag_cloud_class = '#tagcloud'; 
	
	//Fix for tag clouds - unexpected height before .hide() 
	var tag_cloud_height = jQuery('#tagcloud').height();
	
	jQuery('.inside ul li:last-child').css('border-bottom','0px'); // remove last border-bottom from list in tab content
	jQuery('.wooTabs').each(function(){
		jQuery(this).children('li').children('a:first').addClass('selected'); // Add .selected class to first tab on load
	});
	jQuery('.inside > *').hide();
	jQuery('.inside > *:first-child').show();
	
	jQuery('.wooTabs li a').click(function(evt){ // Init Click funtion on Tabs
	
		var clicked_tab_ref = jQuery(this).attr('href'); // Strore Href value
		
		jQuery(this).parent().parent().children('li').children('a').removeClass('selected'); //Remove selected from all tabs
		jQuery(this).addClass('selected');
		jQuery(this).parent().parent().parent().children('.inside').children('*').hide();
		
		jQuery('.inside ' + clicked_tab_ref).fadeIn(500);
		 
		 evt.preventDefault();
	
	})
})
</script>

		
<?php get_footer(); ?>