<?php
/*
Template Name: Tags
*/
?>

<?php get_header(); ?>
       
    <div id="content-wrap">   
    <div id="content" class="col-full">

		<div class="col-left">

			<?php if ( $woo_options[ 'woo_breadcrumbs_show' ] == 'true' ) woo_breadcrumbs(); ?>
	
			<div id="main">
	                                                                                    
	                <div class="post">
	
					    <h1 class="title"><?php the_title(); ?></h1>
	                    
			            <?php if (have_posts()) : the_post(); ?>
		            	<div class="entry">
		            		<?php the_content(); ?>
		            	</div>	            	
			            <?php endif; ?>  
			            
	                    <div class="tag_cloud">
	            			<?php wp_tag_cloud('number=0'); ?>
	        			</div>
	
	                </div><!-- /.post -->
	        
			</div><!-- /#main -->
		
		</div><!-- /.col-left -->

        <?php get_sidebar(); ?>

    </div><!-- /#content -->
	</div><!-- /#content-wrap -->
		
<?php get_footer(); ?>