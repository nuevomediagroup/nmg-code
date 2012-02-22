<?php
/*
Template Name: Sitemap
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
	            
		            <?php
		            	if (have_posts()) { the_post();
		            		the_content();
		            	}
		            ?>  

					<div id="pages" class="fl" style="width:50%">												  
		            	<h3><?php _e( 'Pages', 'woothemes' ); ?></h3>
		            	<ul>
		           	    	<?php wp_list_pages( 'depth=0&sort_column=menu_order&title_li=' ); ?>		
		            	</ul>
	            	</div>				
	    
					<div id="categories" class="fl" style="width:50%">												  	    
			            <h3><?php _e( 'Categories', 'woothemes' ); ?></h3>
			            <ul>
		    	            <?php wp_list_categories( 'title_li=&hierarchical=0&show_count=1' ); ?>	
		        	    </ul>
	        	    </div>
	        	    <div class="fix"></div>			
	    
					<div id="video-categories" class="fl" style="width:50%">												  	    
			            <h3><?php _e( 'Video Categories', 'woothemes' ); ?></h3>
			            <ul>
		    	            <?php wp_list_categories( 'title_li=&hierarchical=0&show_count=1&taxonomy=woo_video_category' ); ?>	
		        	    </ul>
	        	    </div>
	        	    <div class="fix"></div>
			        <div id="posts-per-category">
				        <h3><?php _e( 'Posts per category', 'woothemes' ); ?></h3>
				        <?php
				    
				            $cats = get_categories();
				            foreach ($cats as $cat) {
				    
				            query_posts('cat='.$cat->cat_ID);
				
				        ?>
		        
		        			<h4><?php echo $cat->cat_name; ?></h4>
				        	<ul>	
		    	        	    <?php while (have_posts()) { the_post(); ?>
		        	    	    <li style="font-weight:normal !important;"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> - <?php _e( 'Comments', 'woothemes' ); ?> (<?php echo $post->comment_count ?>)</li>
		            		    <?php } ?>
				        	</ul>
		    
		    		    <?php } ?>
	    		    </div><!--/#posts-per-category-->
	    		    <div id="videos-per-category">
				        <h3><?php _e( 'Videos per category', 'woothemes' ); ?></h3>
				        <?php
				    
				            $terms = get_terms( 'woo_video_category' );
				            
				            foreach ( $terms as $t ) {
				    
				    		$query_args = array();
				    		
				    		$query_args['tax_query'] = array(
											array(
												'taxonomy' => 'woo_video_category',
												'field' => 'slug',
												'terms' => $t->slug
											)
										);
				    
				            query_posts( $query_args );
							
				        ?>
		        
		        			<h4><?php echo $t->name; ?></h4>
				        	<ul>	
		    	        	    <?php while (have_posts()) { the_post(); ?>
		        	    	    <li style="font-weight:normal !important;"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> - <?php _e( 'Comments', 'woothemes' ); ?> (<?php echo $post->comment_count; ?>)</li>
		            		    <?php } ?>
				        	</ul>
		    
		    		    <?php } ?>
	    		    </div><!--/#videos-per-category-->
	    		
	    		</div><!-- /.entry -->
	    						
	        </div><!-- /.post -->                    
	                
        </div><!-- /#main -->

        <?php get_sidebar(); ?>

    </div><!-- /#content -->
	</div><!-- /#content-wrap -->
		
<?php get_footer(); ?>