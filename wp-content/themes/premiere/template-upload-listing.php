<?php
	/*
		Template Name: Upload A Video
		Template Author: Matty @ WooThemes
		Template Description:
		
		This template displays a dynamically generated form for users
		to easily upload their own listings to your listings website.
		
	*/
?>
<?php
	// Load the file to call the required JavaScripts.
	get_template_part( 'includes/upload-listing/load_js' );
?>
<?php get_header(); ?>
<?php global $woo_options; ?>

    <div id="content-wrap">
    <div id="content" class="col-full">

		<div class="col-left">
		
			<?php if ( $woo_options[ 'woo_breadcrumbs_show' ] == 'true' ) woo_breadcrumbs(); ?>

           <div id="main">
	
	            <?php if (have_posts()) : $count = 0; ?>
	            <?php while (have_posts()) : the_post(); $count++; ?>
	
	                <div id="upload-video" <?php post_class('post'); ?>>
				
                    <h1 class="title cufon">
                    	<?php the_title(); ?>
                    	
                    	<?php
                    	
                    	// Display the logout link if the user is logged in.
	
							if ( is_user_logged_in() ) {
								
								$redirect_url = get_permalink( get_the_ID() );
								
								echo '<span class="logout-link">';
								wp_loginout( $redirect_url );
								echo '</span><!--/.logout-link-->' . "\n";
							
							} // End IF Statement
						
						?>
                    	
                    </h1>

                    <div class="entry">
	                	<?php the_content(); ?>

						<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'woothemes' ), 'after' => '</div>' ) ); ?>
						<?php
							// Load the "Upload a listing" form,
							// allowing users to override the form in their child theme.

							$templates = array( 'includes/woo-upload-listing-form.php' );
							$form = locate_template( $templates, true );
						?>
	               	</div><!-- /.entry -->
                    
                </div><!-- /.post -->
                
                <?php $comm = $woo_options['woo_comments']; if ( ($comm == "page" || $comm == "both") ) : ?>
                    <?php comments_template(); ?>
                <?php endif; ?>
                                                    
			<?php endwhile; else: ?>
				<div class="post">
                	<p><?php _e('Sorry, no posts matched your criteria.', 'woothemes') ?></p>
                </div><!-- /.post -->
            <?php endif; ?>  

			</div><!-- /#main -->

		</div><!-- /.col-left -->
		
        <?php get_sidebar(); ?>

    </div><!-- /#content -->
    </div><!-- /#content-wrap -->
    
<?php get_footer(); ?>