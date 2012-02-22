<?php get_header(); ?>
<?php 
// Global query variable
global $wp_query, $woo_options; 
// Get taxonomy query object
$taxonomy_archive_query_obj = $wp_query->get_queried_object();
// Taxonomy term name
$taxonomy_term_nice_name = $taxonomy_archive_query_obj->name;
// Taxonomy term id
$term_id = $taxonomy_archive_query_obj->term_taxonomy_id;
// Get taxonomy object
$taxonomy_short_name = $taxonomy_archive_query_obj->taxonomy;
$taxonomy_raw_obj = get_taxonomy($taxonomy_short_name);
// You can alternate between these labels: name, singular_name
$taxonomy_full_name = $taxonomy_raw_obj->labels->singular_name;
// Get the permalink for the current page.
$taxonomy_permalink = get_term_link( $taxonomy_archive_query_obj, 'woo_video_category' );
?>
<center>

<div id="video_container">



  <?php
    
if ($taxonomy_term_nice_name=="Politics") echo do_shortcode('[video_embed file="http://nuestra.tv/rss/RSS_en/pol_en.rss" width="960" playlistsize="300" playlist="right"]');
elseif ($taxonomy_term_nice_name=="Entertainment") echo do_shortcode('[video_embed file="http://nuestra.tv/rss/RSS_en/ace_en.rss" width="960" playlistsize="300" playlist="right"]');
elseif ($taxonomy_term_nice_name=="Sports") echo do_shortcode('[video_embed file="http://nuestra.tv/rss/RSS_en/spo_en.rss" width="960" playlistsize="300" playlist="right"]');
elseif ($taxonomy_term_nice_name=="Economy") echo do_shortcode('[video_embed file="http://nuestra.tv/rss/RSS_en/fin_en.rss" width="960" playlistsize="300" playlist="right"]');
elseif ($taxonomy_term_nice_name=="Health") echo do_shortcode('[video_embed file="http://nuestra.tv/rss/RSS_en/hth_en.rss" width="960" playlistsize="300" playlist="right"]');
elseif ($taxonomy_term_nice_name=="Odd") echo do_shortcode('[video_embed file="http://nuestra.tv/rss/RSS_en/hum_en.rss" width="960" playlistsize="300" playlist="right"]');
elseif ($taxonomy_term_nice_name=="Tech") echo do_shortcode('[video_embed file="http://nuestra.tv/rss/RSS_en/sci_en.rss" width="960" playlistsize="300" playlist="right"]');
elseif ($taxonomy_term_nice_name=="Environmental") echo do_shortcode('[video_embed file="http://nuestra.tv/rss/RSS_en/env_en.rss" width="960" playlistsize="300" playlist="right"]');
elseif ($taxonomy_term_nice_name=="World") echo do_shortcode('[video_embed file="http://nuestra.tv/rss/RSS_en/war_en.rss" width="960" playlistsize="300" playlist="right"]');
elseif ($taxonomy_term_nice_name=="Law") echo do_shortcode('[video_embed file="http://nuestra.tv/rss/RSS_en/clj_en.rss" width="960" playlistsize="300" playlist="right"]');
elseif ($taxonomy_term_nice_name=="Lifestyle") echo do_shortcode('[video_embed file="http://nuestra.tv/rss/RSS_en/lif_en.rss" width="960" playlistsize="300" playlist="right"]');
elseif ($taxonomy_term_nice_name=="Política") echo do_shortcode('[video_embed file="http://nuestra.tv/rss/RSS_es/pol_es.rss" width="960" playlistsize="300" playlist="right"]');
elseif ($taxonomy_term_nice_name=="Deportes") echo do_shortcode('[video_embed file="http://nuestra.tv/rss/RSS_es/spo_es.rss" width="960" playlistsize="300" playlist="right"]');
elseif ($taxonomy_term_nice_name=="Economía") echo do_shortcode('[video_embed file="http://nuestra.tv/rss/RSS_es/fin_es.rss" width="960" playlistsize="300" playlist="right"]');
elseif ($taxonomy_term_nice_name=="Entretenimiento") echo do_shortcode('[video_embed file="http://nuestra.tv/rss/RSS_es/ace_es.rss" width="960" playlistsize="300" playlist="right"]');
elseif ($taxonomy_term_nice_name=="Justicia") echo do_shortcode('[video_embed file="http://nuestra.tv/rss/RSS_es/clj_es.rss" width="960" playlistsize="300" playlist="right"]');
elseif ($taxonomy_term_nice_name=="Salud") echo do_shortcode('[video_embed file="http://nuestra.tv/rss/RSS_es/hth_es.rss" width="960" playlistsize="300" playlist="right"]');
elseif ($taxonomy_term_nice_name=="Mundo") echo do_shortcode('[video_embed file="http://nuestra.tv/rss/RSS_es/war_es.rss" width="960" playlistsize="300" playlist="right"]');
elseif ($taxonomy_term_nice_name=="Fe") echo do_shortcode('[video_embed file="http://nuestra.tv/rss/RSS_es/rel_es.rss" width="960" playlistsize="300" playlist="right"]');
elseif ($taxonomy_term_nice_name=="Tecnología") echo do_shortcode('[video_embed file="http://nuestra.tv/rss/RSS_es/sci_es.rss" width="960" playlistsize="300" playlist="right"]');
elseif ($taxonomy_term_nice_name=="Estilo") echo do_shortcode('[video_embed file="http://nuestra.tv/rss/RSS_es/lif_es.rss" width="960" playlistsize="300" playlist="right"]');
elseif ($taxonomy_term_nice_name=="Insólito") echo do_shortcode('[video_embed file="http://nuestra.tv/rss/RSS_es/hum_es.rss" width="960" playlistsize="300" playlist="right"]');
elseif ($taxonomy_term_nice_name=="Verde") echo do_shortcode('[video_embed file="http://nuestra.tv/rss/RSS_es/evn_es.rss" width="960" playlistsize="300" playlist="right"]');








?>
</div>

</center>
    <div id="content-wrap">   
    <div id="content" class="col-full">
		<div class="col-left">
		
			<?php if ( $woo_options[ 'woo_breadcrumbs_show' ] == 'true' ) woo_breadcrumbs(); ?>

			<div id="main" class="video-archive">
			<?php if (have_posts()) : $count = 0; ?>
	        
	            <span class="archive_header"><?php echo __( 'Video Category Archives:', 'woothemes' ); ?> <?php echo $taxonomy_term_nice_name; ?></span>
 
 
	            
	            <div class="fix"></div>
	            <?php
	            	// Bar displaying video sorting functionality.
	            	echo woo_sorting_bar( $taxonomy_permalink );
	            ?>
	        
	        <?php while (have_posts()) : the_post(); $count++; ?>
	                                                                    
	            <!-- Post Starts -->
	            <div class="post <?php if($count % 3 == 0) { echo "last"; } ?>">
	
	                <?php
	                	if ( $woo_options['woo_post_content'] != 'content' ) {
	                		echo '<a href="' . get_permalink( get_the_ID() ) . '" rel="bookmark" title="' . the_title_attribute( array( 'echo' => 0 ) ) . '">' . woo_image_vimeo('width=' . $woo_options['woo_thumb_w'] . '&height=' . $woo_options['woo_thumb_h'] . '&class=thumbnail&link=img&return=true&id=' . get_the_ID() ) . '</a>';
	                	}
	                ?>
	
	                <h2 class="title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
	                
	                <span class="date"><?php the_time( get_option( 'date_format' ) ); ?></span>
	
	            </div><!-- /.post -->
	            
	            <?php if($count % 3 == 0) { ?>
					<div class="fix"></div>
		        <?php } ?>
	            
	        <?php endwhile; else: ?>
	        
	            <div class="post">
	                <p><?php _e( 'Sorry, no posts matched your criteria.', 'woothemes' ); ?></p>
	            </div><!-- /.post -->
	        
	        <?php endif; ?>  
	    
				<?php woo_pagenav(); ?>
                
			</div><!-- /#main -->

		</div><!-- /.col-left -->

        <?php get_sidebar(); ?>

    </div><!-- /#content -->
	</div><!-- /#content-wrap -->
		
<?php get_footer(); ?>