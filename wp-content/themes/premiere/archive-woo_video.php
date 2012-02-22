<?php
	get_header();
	$archive_permalink = get_post_type_archive_link( 'woo_video' );
	global $woo_options;
?>    
    <div id="content-wrap">   
    <div id="content" class="col-full">
		<div class="col-left">
		
			<?php if ( $woo_options[ 'woo_breadcrumbs_show' ] == 'true' ) { woo_breadcrumbs(); } ?>

			<div id="main" class="video-archive">
			<?php if (have_posts()) { $count = 0; ?>
	            
	        	<span class="archive_header"><?php _e('All videos archive', 'woothemes') ?></span>
	        	
	        	<div class="fix"></div>
	        	<?php
	            	// Bar displaying video sorting functionality.
	            	echo woo_sorting_bar( $archive_permalink );
	            ?>    
	        
	        <?php while (have_posts()) { the_post(); $count++; ?>
	                                                                    
	            <!-- Post Starts -->
	            <div class="post <?php if($count % 3 == 0) { echo "last"; } ?>">
	               <?php
	                	if ( $woo_options['woo_post_content'] != 'content' ) {
	                		echo '<a href="' . get_permalink( get_the_ID() ) . '" rel="bookmark" title="' . the_title_attribute( array( 'echo' => 0 ) ) . '">' . woo_image_vimeo('width=180&height=' . $woo_options['woo_thumb_h'] . '&class=thumbnail&link=img&return=true') . '</a>';
	                	}
	                ?>
	                <h2 class="title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute( array( 'echo' => 0 ) ); ?>"><?php the_title(); ?></a></h2>
	                <span class="date"><?php the_time( get_option( 'date_format' ) ); ?></span>
	
	            </div><!-- /.post -->
	            
	            <?php if($count % 3 == 0) { ?>
					<div class="fix"></div>
		        <?php } ?>
	            
	        <?php
	        		}
	        	} else {
	        ?>
	        
	            <div class="post">
	                <p><?php _e( 'Sorry, no posts matched your criteria.', 'woothemes' ); ?></p>
	            </div><!-- /.post -->
	        
	        <?php } ?>  
	    		
	    		<div class="fix"></div>
	    		
				<?php woo_pagenav(); ?>
                
			</div><!-- /#main -->

		</div><!-- /.col-left -->

        <?php get_sidebar(); ?>

    </div><!-- /#content -->
	</div><!-- /#content-wrap -->
		
<?php get_footer(); ?>