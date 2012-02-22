<?php
/*
Template Name: Archives Page
*/
?>
<?php get_header(); ?>
       
    <div id="content-wrap">   
    <div id="content" class="page col-full">

		<?php if ( $woo_options[ 'woo_breadcrumbs_show' ] == 'true' ) woo_breadcrumbs(); ?>

		<div id="main" class="col-left">
    	            			
			<div class="post">
			    
			    <h1 class="title"><?php the_title(); ?></h1>
			    
			    <div class="entry">
			    
		            <?php if (have_posts()) : the_post(); ?>
                	<?php the_content(); ?>
		            <?php endif; ?>  
				    
				    <div id="last-30-posts">
					    <h3><?php _e( 'The Last 30 Posts', 'woothemes' ); ?></h3>
																		  
					    <ul>											  
					        <?php query_posts( 'showposts=30' ); ?>		  
					        <?php if ( have_posts() ) { while ( have_posts() ) { the_post(); ?>
					            <?php $wp_query->is_home = false; ?>	  
					            <li>
					            	<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> - <?php the_time( get_option( 'date_format' ) ); ?> - <?php echo $post->comment_count; ?> <?php _e( 'comments', 'woothemes' ); ?>
					            </li>
					        <?php } } ?>					  
					    </ul>											  
					</div><!--/#last-30-posts-->
					<div id="categories" class="fl" style="width:50%">												  
					    <h3><?php _e( 'Categories', 'woothemes' ); ?></h3>	  
					    <ul>											  
					        <?php wp_list_categories( 'title_li=&hierarchical=0&show_count=1' ); ?>	
					    </ul>											  
					</div><!--/#categories-->			     												  

					<div id="monthly-archives" class="fr" style="width:50%">												  
					    <h3><?php _e( 'Monthly Archives', 'woothemes' ); ?></h3>
																		  
					    <ul>											  
					        <?php wp_get_archives( 'type=monthly&show_post_count=1' ); ?>	
					    </ul>
					</div><!--/#monthly-archives-->	
					
					<div class="fix"></div>
					
					<div id="video-categories" class="fl" style="width:50%">												  
					    <h3><?php _e( 'Video Categories', 'woothemes' ); ?></h3>	  
					    <ul>											  
					        <?php wp_list_categories( 'title_li=&hierarchical=0&show_count=1&taxonomy=woo_video_category' ); ?>
					    </ul>											  
					</div><!--/#video-categories-->			     												  

					<div id="recent-videos" class="fr" style="width:50%">												  
					    <h3><?php _e( 'Recent Videos', 'woothemes' ); ?></h3>
																		  
					    <ul>											  
					        <?php query_posts( 'showposts=10&post_type=woo_video' ); ?>		  
					        <?php if ( have_posts() ) { while ( have_posts() ) { the_post(); ?>
					            <?php $wp_query->is_home = false; ?>	  
					            <li>
					            	<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					            </li>
					        <?php } } ?>
					    </ul>
					</div><!--/#recent-videos-->
					
					<div class="fix"></div>     												  

				</div><!-- /.entry -->
			    			
			</div><!-- /.post -->                 
                
        </div><!-- /#main -->

        <?php get_sidebar(); ?>

    </div><!-- /#content -->
	</div><!-- /#content-wrap -->
		
<?php get_footer(); ?>
