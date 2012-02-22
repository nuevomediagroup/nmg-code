<?php get_header(); ?>
    
    <div id="content-wrap">   
    <div id="content" class="col-full">
		<div class="col-left">
		
			<?php if ( $woo_options[ 'woo_breadcrumbs_show' ] == 'true' ) { woo_breadcrumbs(); } ?>

			<div id="main">
			<?php if (have_posts()) { $count = 0; ?>
	        
	            <?php if (is_category()) { ?>
	        	<span class="archive_header"><span class="fl cat"><?php _e('Archive', 'woothemes'); ?> | <?php echo single_cat_title(); ?></span> <span class="fr catrss"><?php $cat_obj = $wp_query->get_queried_object(); $cat_id = $cat_obj->cat_ID; echo '<a href="'; get_category_rss_link(true, $cat, ''); echo '">'; _e("RSS feed for this section", "woothemes"); echo '</a>'; ?></span></span>        
	        
	            <?php } elseif (is_day()) { ?>
	            <span class="archive_header"><?php _e('Archive', 'woothemes'); ?> | <?php the_time( get_option( 'date_format' ) ); ?></span>
	
	            <?php } elseif (is_month()) { ?>
	            <span class="archive_header"><?php _e('Archive', 'woothemes'); ?> | <?php the_time('F, Y'); ?></span>
	
	            <?php } elseif (is_year()) { ?>
	            <span class="archive_header"><?php _e('Archive', 'woothemes'); ?> | <?php the_time('Y'); ?></span>
	
	            <?php } elseif (is_author()) { ?>
	            <span class="archive_header"><?php _e('Archive by Author', 'woothemes'); ?></span>
	
	            <?php } elseif (is_tag()) { ?>
	            <span class="archive_header"><?php _e('Tag Archives:', 'woothemes'); ?> <?php echo single_tag_title('', true); ?></span>
	            
	            <?php } ?>
	            <div class="fix"></div>
	        
	        <?php while (have_posts()) { the_post(); $count++; ?>
	                                                                    
	            <!-- Post Starts -->
	            <div class="post">
					<?php
	                	if ( $woo_options['woo_post_content'] != 'content' ) {
	                		echo '<a href="' . get_permalink( get_the_ID() ) . '" rel="bookmark" title="' . the_title_attribute( array( 'echo' => 0 ) ) . '">' . woo_image_vimeo('width=' . $woo_options['woo_thumb_w'] . '&height='.$woo_options['woo_thumb_h'] . '&class=thumbnail '.$woo_options['woo_thumb_align'] . '&link=img&return=true') . '</a>';
	                	}
	                ?>
                
                	<h2 class="title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
                
                	<p class="post-meta">
	    			    <span class="post-date"><span class="small"><?php _e( 'Posted on', 'woothemes' ); ?></span> <?php the_time( get_option( 'date_format' ) ); ?></span>
	    			    <span class="post-author"><span class="small"><?php _e( 'by', 'woothemes' ); ?></span> <?php the_author_posts_link(); ?></span>
	    			    <?php
	    			    	if ( get_post_type() == 'woo_video' ) {
	    			    		echo get_the_term_list( $post->ID, 'woo_video_category', '<span class="post-category"><span class="small">' . __( 'in', 'woothemes' ) . '</span> ', ', ', '</span>' );
	    			    	} else {
	    			    ?>
	    			    	<span class="post-category"><span class="small"><?php _e('in', 'woothemes'); ?></span> <?php the_category(', '); ?></span>
	    			    <?php } ?>
	    			    <?php edit_post_link( __('{ Edit }', 'woothemes'), '<span class="small">', '</span>' ); ?>
					</p>
                
                	<div class="entry">
						<?php global $more; $more = 0; ?>	                                        
                    	<?php if ( $woo_options['woo_post_content'] == "content" ) the_content(__('Read More...', 'woothemes')); else the_excerpt(); ?>
                	</div>
    				
    				<div class="fix"></div>
    			
               		<div class="post-more">      
						<span class="comments fl"><?php comments_popup_link(__('Leave a comment', 'woothemes'), __('1 Comment', 'woothemes'), __('% Comments', 'woothemes')); ?></span>
                		<?php if ( $woo_options['woo_post_content'] == "excerpt" ) { ?>
                    	<span class="read-more fr"><a href="<?php the_permalink() ?>" title="<?php _e('Continue Reading &rarr;','woothemes'); ?>"><?php _e('Continue Reading &rarr;','woothemes'); ?></a></span>
                   	 <?php } ?>
                   	 	<div class="fix"></div>
               		</div>   
	
	            </div><!-- /.post -->
	            
	        <?php
	        		}
	        	} else {
	        ?>
	        
	            <div class="post">
	                <p><?php _e( 'Sorry, no posts matched your criteria.', 'woothemes' ); ?></p>
	            </div><!-- /.post -->
	        
	        <?php } ?>  
	    
				<?php woo_pagenav(); ?>
                
			</div><!-- /#main -->

		</div><!-- /.col-left -->

        <?php get_sidebar(); ?>

    </div><!-- /#content -->
	</div><!-- /#content-wrap -->
		
<?php get_footer(); ?>