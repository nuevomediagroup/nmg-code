<?php get_header(); ?>
<?php global $woo_options; ?>

	<?php if (have_posts()) : the_post(); ?>

    <div id="feat-title" class="col-full">        
        <h1 class="title"><?php the_title(); ?></h1>
    </div>

    <div id="feat-video" class="col-full">
    
    	<?php if ( $woo_options[ 'woo_breadcrumbs_show' ] == 'true' ) { woo_breadcrumbs(); } ?>

	    <div class="video fl">	
			<?php echo woo_embed('width=600&height=337&class=single-video'); ?>
			<ul class="share">
				<li class="twitter">
					<a href="http://twitter.com/home?status=Currently reading <?php the_permalink(); ?>"><img src="<?php bloginfo('template_directory'); ?>/images/ico-share-twitter.png" alt="ico" /><?php _e( 'Tweet this Video', 'woothemes' ); ?></a>
				</li>
				<li class="facebook-share">
					<a href="http://www.facebook.com/sharer.php?u=<?php the_permalink();?>&t=<?php the_title_attribute(); ?>"><img src="<?php bloginfo('template_directory'); ?>/images/ico-share-facebook.png" alt="ico" /><?php _e( 'Share on Facebook', 'woothemes' ); ?></a>
				</li>
				<?php if( function_exists( 'woo_email_share_link' ) ) { echo '<li class="email">' . woo_email_share_link() . '</li>'; } ?>
			</ul>
	    </div>

	    <div class="meta fr">
	    <div id="adaptvcompanion"></div>
<div id="spacer"></div>
	    	<div class="video-excerpt entry">
	    		<?php the_excerpt(); ?>
	    	</div>
	    	
            <?php woo_post_meta(); ?>
			<?php if ( $woo_options['woo_ad_panel'] == 'true' ) { ?>
		    <div id="adpanel fr">
				<?php 
					if ( $woo_options['woo_ad_panel_adsense'] != '' ) { 
						echo stripslashes($woo_options['woo_ad_panel_adsense']); 
					} else {
				?>
					<a href="<?php echo $woo_options['woo_ad_panel_url']; ?>"><img src="<?php echo $woo_options['woo_ad_panel_image']; ?>" width="234" height="60" alt="advert" /></a>
					
				<?php } ?>		   	
			</div><!-- /#adpanel -->
		    <?php } ?>
	    </div>

	    <div class="clear"></div>

	</div><!-- #feat-video -->

    <div id="content-wrap">   
    <div id="content" class="col-full">

		<div class="col-left">

			<div id="main">

				<div <?php post_class('post'); ?>>
	
					<?php if ( $woo_options['woo_thumb_single'] == 'true' && ! woo_embed( '' ) ) woo_image('width='.$woo_options['woo_single_w'].'&height='.$woo_options['woo_single_h'].'&class=thumbnail '.$woo_options['woo_thumb_single_align']); ?>
	                <div class="entry">
	                	<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'woothemes' ), 'after' => '</div>' ) ); ?>
					</div>
											                                
	            </div><!-- .post -->
	
				<?php if ( $woo_options['woo_post_author'] == 'true' ) { ?>
				<div id="post-author">
					<div class="profile-image"><?php echo get_avatar( get_the_author_meta( 'ID' ), '70' ); ?></div>
					<div class="profile-content">
						<h3 class="title"><?php printf( esc_attr__( 'About %s', 'woothemes' ), get_the_author() ); ?></h3>
						<?php the_author_meta( 'description' ); ?>
						<div class="profile-link">
							<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
								<?php printf( __( 'View all posts by %s <span class="meta-nav">&rarr;</span>', 'woothemes' ), get_the_author() ); ?>
							</a>
						</div><!-- #profile-link	-->
					</div><!-- .post-entries -->
					<div class="fix"></div>
				</div><!-- #post-author -->
				<?php } ?>
				<?php $original_post = $post; /* Save the original post data to make sure we display it correctly after related posts. */ ?>
				<div class="related-videos">
				
					<?php
						// Custom related videos.
						echo '<h3 class="title">' . __( 'Related Videos', 'woothemes' ) . '</h3>' . "\n" . do_shortcode( '[related_posts_custom limit="3" image="180" height="114" post_type="woo_video"]' )
					?>
					
					<div class="fix"></div>
					
				</div><!-- /.related-videos -->
				<?php $post = $original_post; /* Make sure the correct post is used to display the comments. */ ?>
				<?php woo_subscribe_connect(); ?>
	
		        <div id="post-entries">
		            <div class="nav-prev fl"><?php previous_post_link( '%link', '<span class="meta-nav">&larr;</span> %title' ); ?></div>
		            <div class="nav-next fr"><?php next_post_link( '%link', '%title <span class="meta-nav">&rarr;</span>' ); ?></div>
		            <div class="fix"></div>
		        </div><!-- #post-entries -->
	            
	            <?php
	            	$comm = $woo_options['woo_comments'];
	            	if ( ( $comm == 'post' || $comm == 'both' ) ) {
	            		comments_template('', true);
	            	}
	            ?>
	                                                        
			</div><!-- #main -->

		</div><!-- /.col-left -->
		
<?php else: ?>
	<div class="post">
		<p><?php _e('Sorry, no posts matched your criteria.', 'woothemes'); ?></p>
	</div><!-- .post -->             
<?php endif; ?>  
		
        <?php get_sidebar(); ?>

    </div><!-- #content -->
	</div><!-- /#content-wrap -->
		
<?php get_footer(); ?>