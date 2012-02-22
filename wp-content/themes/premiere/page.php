<?php get_header(); ?>
<?php global $woo_options; ?>

    <div id="content-wrap">
    <div id="content" class="col-full">

		<div class="col-left">
		
			<?php if ( $woo_options[ 'woo_breadcrumbs_show' ] == 'true' ) { woo_breadcrumbs(); } ?>

			<div id="main">
	
	            <?php if (have_posts()) { $count = 0; ?>
	            <?php while (have_posts()) { the_post(); $count++; ?>
	
	                <div <?php post_class('post'); ?>>
	
					    <h1 class="title"><?php the_title(); ?></h1>
	
	                    <div class="entry">
		                	<?php the_content(); ?>
	
							<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'woothemes' ), 'after' => '</div>' ) ); ?>
		               	</div><!-- /.entry -->
	
						<?php edit_post_link( __('{ Edit }', 'woothemes'), '<span class="small">', '</span>' ); ?>
	
	                </div><!-- /.post -->
	
	                <?php $comm = $woo_options['woo_comments']; if ( ( $comm == 'page' || $comm == 'both' ) ) { ?>
	                    <?php comments_template(); ?>
	                <?php } ?>
	
				<?php
					}
				} else {
				?>
					<div class="post">
	                	<p><?php _e( 'Sorry, no posts matched your criteria.', 'woothemes' ); ?></p>
	                </div><!-- /.post -->
	            <?php } ?>
	
			</div><!-- /#main -->

		</div><!-- /.col-left -->
		
        <?php get_sidebar(); ?>

    </div><!-- /#content -->
    </div><!-- /#content-wrap -->
    
<?php get_footer(); ?>