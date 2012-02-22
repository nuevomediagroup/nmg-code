<?php get_header(); ?>
       
    <div id="content-wrap">   
    <div id="content" class="col-full">
    	<?php if ( $woo_options[ 'woo_breadcrumbs_show' ] == 'true' ) { woo_breadcrumbs(); } ?>
		<div id="main" class="col-left">
			<?php if (have_posts()) { $count = 0; ?>
            
            <span class="archive_header"><?php echo __( 'Search results:', 'woothemes' ) . ' '; printf( the_search_query() );?></span>
                
            <?php while (have_posts()) { the_post(); $count++; ?>
                                                                        
            <!-- Post Starts -->
            <div class="post">
            
                <h2 class="title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute( array( 'echo' => 0 ) ); ?>"><?php the_title(); ?></a></h2>
                
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
                    <?php the_excerpt(); ?>
                </div><!-- /.entry -->
                
                <div class="post-more">      
				    <span class="comments fl"><?php comments_popup_link( __( 'Leave a comment', 'woothemes' ), __( '1 Comment', 'woothemes' ), __( '% Comments', 'woothemes' ) ); ?></span>
                    <?php if ( $woo_options['woo_post_content'] == 'excerpt' ) { ?>
                	<span class="read-more fr"><a href="<?php the_permalink(); ?>" title="<?php _e('Continue Reading &rarr;','woothemes'); ?>"><?php _e( 'Continue Reading &rarr;','woothemes' ); ?></a></span>
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

        <?php get_sidebar(); ?>

    </div><!-- /#content -->
	</div><!-- /#content-wrap -->
		
<?php get_footer(); ?>