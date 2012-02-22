<?php

/*-------------------------------------------------------------------------------------

TABLE OF CONTENTS

- Display video posts on author archive screens.
- Sorting bar for video archives.
- Order videos on video taxonomy screens using query variables.
- Function to display either Vimeo thumbnail or conventional woo_image().
- Override related posts shortcode, adding an optional "post_type" value.
- Create an e-mail link for sharing a specific video
- Get tags assigned only to posts of a specific type
- Register WP Menus
- Page navigation
- Post Meta
- Subscribe & Connect

-------------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------*/
/* Display video posts on author archive screens. */
/*-----------------------------------------------------------------------------------*/

add_filter( 'pre_get_posts', 'woo_show_videos_on_authors' );

function woo_show_videos_on_authors ( $query ) {

	if ( $query->is_author ) { $query->set( 'post_type', array( 'post', 'woo_video' ) ); }

	return $query;

} // End woo_show_videos_on_authors()

/*-----------------------------------------------------------------------------------*/
/* Sorting bar for video archives. */
/*
/* @param $url string - the URL to the current page.
/* @return $html string - sorting bar outputted XHTML.
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woo_sorting_bar' ) ) {
	function woo_sorting_bar ( $url ) {
		if ( ! $url ) { return; }
		
		// Supported sorting types.
		$allowed_sorting = array( 
								'date' => __( 'Date Added', 'woothemes' ),
								'title' => __( 'Name', 'woothemes' )
								);
		
		// Sorting toggle.
		$sorting = 'asc';
		if ( $_GET['video-order'] && in_array( $_GET['video-order'], array( 'asc', 'desc' ) ) ) {
			$current_sort = strtolower( strip_tags( $_GET['video-order'] ) );
			if ( $current_sort == 'asc' ) { $new_sort = 'desc'; } else { $new_sort = 'asc'; }
			$sorting = $new_sort;
		}
		
		// Sortby "active" CSS class toggle.
		$current_sortby = 'date';
		if ( $_GET['video-sortby'] && in_array( $_GET['video-sortby'], array_keys( $allowed_sorting ) ) ) {
			$current_sortby = strtolower( strip_tags( $_GET['video-sortby'] ) );
		}
		
		$html = '';
		
		$html .= '<ul class="sorting">' . "\n";
		$html .= '<li class="sortby">' . __( 'Sort by:', 'woothemes' ) . '</li>' . "\n";
		
		foreach ( $allowed_sorting as $k => $v ) {
			$css_class = 'sortby-item';
			
			$direction_indicator = '&uarr;';
			if ( $sorting == 'desc' ) { $direction_indicator = '&darr;'; }
			
			if ( $k == $current_sortby ) { $css_class .= ' active'; }
			
			$html .= '<li class="' . $css_class . '"><a href="' . $url . '?video-sortby=' . $k . '&video-order=' . $sorting . '" title="' . esc_attr( sprintf( __( 'Sort by %s', 'woothemes' ), $v ) ) . '">' . $v;
			if ( $k == $current_sortby ) { $html .= ' <span class="direction-indicator">' . $direction_indicator . '</span>' . "\n"; }
			$html .= '</a></li>' . "\n";
		}

		$html .= '</ul>' . "\n";
		
		return $html;
	
	} // End woo_sorting_bar()
}

/*-----------------------------------------------------------------------------------*/
/* Order videos on video taxonomy screens using query variables. */
/*-----------------------------------------------------------------------------------*/

if ( ! is_admin() && ! is_home() ) {
	add_filter( 'pre_get_posts', 'woo_sort_in_taxonomy' );
}

if ( ! function_exists( 'woo_sort_in_taxonomy' ) ) {
	function woo_sort_in_taxonomy ( $query ) {
		if ( ( ! is_admin() && ! is_home() ) && ( is_tax() || is_post_type_archive() ) ) {
			if ( $query->is_tax || $query->is_post_type_archive ) {
			
				$allowed_directions = array( 'asc', 'desc' );
				$allowed_sorting = array( 'date', 'title' );
				
				$default_direction = 'desc';
				$default_sorting = 'date';
				
				$direction = $default_direction;
				$sorting = $default_sorting;
				
				if ( isset( $_GET['video-order'] ) && in_array( $_GET['video-order'], $allowed_directions ) ) { $direction = strtolower( strip_tags( $_GET['video-order'] ) ); }
				if ( isset( $_GET['video-sortby'] ) && in_array( $_GET['video-sortby'], $allowed_sorting ) ) { $sorting = strtolower( strip_tags( $_GET['video-sortby'] ) ); }
				
				$query->set( 'orderby', $sorting );
				$query->set( 'order', strtoupper( $direction ) );
				
				$query->parse_query();
			
			}
		}
	
		return $query;
	} // End woo_sort_in_taxonomy()
}

/*-----------------------------------------------------------------------------------*/
/* Function to display either Vimeo thumbnail or conventional woo_image(). */
/*-----------------------------------------------------------------------------------*/
if (!function_exists('woo_image_vimeo')) {
	function woo_image_vimeo ( $args ) {
	
		global $woo_video;
		
		$is_vimeo = false;
		$has_image = false;
		$html = '';
		
		if ( !is_array( $args ) ) 
			parse_str( $args, $parsed );
		
		$id = 0;
		
		if ( array_key_exists( 'id', $parsed ) ) { $id = $parsed['id']; }
		
		if ( $id == 0 ) {
			global $post;
			if ( isset( $post->ID ) ) { $id = $post->ID; }
		}
		
		$vimeo_id = 0;
    	$embed = get_post_meta( $id, 'embed', true );
    	
    	if ( $embed ) {
    	
    	preg_match( '#http://player.vimeo.com/video/([0-9]+)#s', $embed, $matches );
		
			if ( count( $matches ) ) {
			
				$vimeo_id = $matches[1];
			
			} // End IF Statement
		
		}
		
		// Check for Vimeo thumbnail.
		if ( $vimeo_id ) {
			$url = Woo_PostType_Video::get_vimeo_info( $vimeo_id );
			$is_vimeo = true;
		}
		
		// Check for woo_image.
		if ( ( get_post_meta( $id, 'image', true ) != '' ) || ( function_exists( 'has_post_thumbnail' ) && has_post_thumbnail( $id ) ) ) {
			$has_image = true;
		}
		
		// If there's a Vimeo thumbnail, and no featured image/image custom field, display the Vimeo thumbnail.
		if ( ( $url != '' ) && ( $has_image == false ) ) {
		
			$image_atts = '';
			
			if ( isset( $parsed['width'] ) ) { $image_atts .= ' width="' . $parsed['width'] . '"'; }
			if ( isset( $parsed['height'] ) ) { $image_atts .= ' height="' . $parsed['height'] . '"'; }
		
			$html = '<img src="' . $url . '"' . $image_atts . ' class="woo-image ' . $parsed['class'] . '" />' . "\n";
		
		} else {
		
			$html = woo_image( $args );
		
		}
		
		return $html;
	
	} // End woo_image_vimeo()
} // End IF Statement 

/*-----------------------------------------------------------------------------------*/
/* Custom related posts shortcode, adding an optional "post_type" value. */
/*-----------------------------------------------------------------------------------*/

/*

Optional arguments:
 - limit: number of posts to show (default: 5)
 - image: thumbnail size, 0 = off (default: 0)
 - post_type: filter by post type (default: empty string)
*/

function woo_shortcode_related_posts_custom( $atts ) {
 
 	global $woo_options;
 
	extract(shortcode_atts(array(
	    'limit' => '5',
	    'image' => '',
	    'post_type' => '', 
	    'width' => $woo_options['woo_thumb_w'], 
	    'height' => $woo_options['woo_thumb_h']
	), $atts));
 
	global $wpdb, $post, $table_prefix;
 
	if ($post->ID) {
 
		$retval = '<ul class="woo-sc-related-posts">';
 
		// Get tags
		$tags = wp_get_post_tags($post->ID);
		$tagsarray = array();
		foreach ($tags as $tag) {
			$tagsarray[] = $tag->term_id;
		}
		$tagslist = implode( ',', $tagsarray);
 
		// Do the query
		$q = "
			SELECT p.*, count(tr.object_id) as count
			FROM $wpdb->term_taxonomy AS tt, $wpdb->term_relationships AS tr, $wpdb->posts AS p
			WHERE tt.taxonomy ='post_tag'
				AND tt.term_taxonomy_id = tr.term_taxonomy_id
				AND tr.object_id  = p.ID
				AND tt.term_id IN ($tagslist)
				AND p.ID != $post->ID
				AND p.post_status = 'publish'
				AND p.post_date_gmt < NOW()";
		
		// Filter by post type, if necessary.
		if ( $post_type ) {
			$q .= " AND p.post_type = '" . $post_type . "' ";
		}
				
		$q .= "
			GROUP BY tr.object_id
			ORDER BY count DESC, p.post_date_gmt DESC
			LIMIT $limit;";
 
		$related = $wpdb->get_results($q);
 
		if ( $related ) {
			foreach( $related as $r ) {
				
				if ( $image ) {
					$image_out = "";
					$image_out .= '<a class="thumbnail" href="'.get_permalink($r->ID).'">';
					$image_out .= woo_image_vimeo( "link=img&width=" . $width . "&height=" . $height . "&return=true&id=" . $r->ID );
					$image_out .= '</a>';
				}
				$retval .= '<li>'.$image_out.'<a class="related-title" title="'.wptexturize($r->post_title).'" href="'.get_permalink($r->ID).'"><span>'.wptexturize($r->post_title).'</span></a></li>';
			}
		} else {
			$retval .= '<li>'.__( 'No related videos found', 'woothemes' ).'</li>';
		}
		$retval .= '</ul>';
		return $retval;
	}
	return;
}
add_shortcode( 'related_posts_custom', 'woo_shortcode_related_posts_custom' );

/*-----------------------------------------------------------------------------------*/
/* Create an e-mail link for sharing a specific video */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woo_email_share_link' ) ) {
	function woo_email_share_link () {
		global $post;
		
		if ( ! $post ) { return; }
		
		$html = '';
		
		$subject = esc_attr( sprintf( __( '%1$s on %2$s', 'woothemes' ), get_the_title( $post->ID ), get_bloginfo( 'name' ) ) );
		$body = esc_attr( sprintf( __( "Hi, \n\rCheck out this video called " . '%1$s' . " which I found on " . '%2$s.' . " \n\rIt can be viewed at " . '%3$s.', 'woothemes' ), get_the_title( $post->ID ), get_bloginfo( 'name' ), get_permalink( $post->ID ) ) );
		
		$html = '<a href="mailto:?subject=' . $subject . '&body=' . $body . '"><img src="'. get_bloginfo('template_directory') .'/images/ico-share-mail.png" alt="ico" />' . __( 'Share via E-mail', 'woothemes' ) . '</a>' . "\n";
		
		return $html;
		
	} // End woo_email_share_link()
}

/*-----------------------------------------------------------------------------------*/
/* Get tags assigned only to posts of a specific type */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woo_get_tags_by_post_type' ) ) {
	function woo_get_tags_by_post_type ( $post_type ) {
	
		if ( ! $post_type ) { return; }
		
		$tag_ids = array();
		
		// Get posts of the specified post type.
		$posts = get_posts( array( 'numberposts' => -1, 'post_type' => esc_attr( $post_type ) ) );
		
		if ( $posts ) {
			foreach ( $posts as $p ) {
				$tags = get_the_tags( $p->ID );
				if ( $tags ) {
					foreach ( $tags as $t ) {
						if ( ! in_array( $t->term_id, $tag_ids ) ) { $tag_ids[] = $t->term_id; }
					}
				}
			}
		}
		
		return $tag_ids;
	
	} // End woo_get_tags_by_post_type()
} // End IF Statement

/*-----------------------------------------------------------------------------------*/
/* Register WP Menus */
/*-----------------------------------------------------------------------------------*/
if ( function_exists( 'wp_nav_menu') ) {
	add_theme_support( 'nav-menus' );
	register_nav_menus( array( 'primary-menu' => __( 'Primary Menu', 'woothemes' ) ) );
	register_nav_menus( array( 'top-menu' => __( 'Top Menu', 'woothemes' ) ) );
}


/*-----------------------------------------------------------------------------------*/
/* Page navigation */
/*-----------------------------------------------------------------------------------*/
if (!function_exists( 'woo_pagenav')) {
	function woo_pagenav() {

		global $woo_options;

		// If the user has set the option to use simple paging links, display those. By default, display the pagination.
		if ( array_key_exists( 'woo_pagination_type', $woo_options ) && $woo_options[ 'woo_pagination_type' ] == 'simple' ) {
			if ( get_next_posts_link() || get_previous_posts_link() ) {
		?>

            <div class="nav-entries">
                <?php next_posts_link( '<span class="nav-prev fl">'. __( '<span class="meta-nav">&larr;</span> Older posts', 'woothemes' ) . '</span>' ); ?>
                <?php previous_posts_link( '<span class="nav-next fr">'. __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'woothemes' ) . '</span>' ); ?>
                <div class="fix"></div>
            </div>

		<?php
			} 
		} else {
			woo_pagination();
		} 
	
	} 
}


/*-----------------------------------------------------------------------------------*/
/* Post Meta */
/*-----------------------------------------------------------------------------------*/

if (!function_exists('woo_post_meta')) {
	function woo_post_meta() {
	
		global $post;
	
		$current_post_type = get_post_type();
?>
<ul class="post-meta">
    <li class="post-date"><span class="small"><?php _e('Posted on:', 'woothemes') ?></span> <?php the_time( get_option( 'date_format' ) ); ?></li>
    <li class="post-author"><span class="small"><?php _e('Author:', 'woothemes') ?></span> <?php the_author_posts_link(); ?></li>
    <?php
    	if ( $current_post_type == 'woo_video' ) {
    	
    		$terms = get_the_term_list( $post->ID, 'woo_video_category', '<li class="post-category"><span class="small">' . __( 'Category:', 'woothemes' ) . '</span> ', ', ', '</li>' );
    		
    		if ( $terms ) { echo $terms; }

    	} else {
    ?>
    <li class="post-category"><span class="small"><?php _e('Category:', 'woothemes') ?></span> <?php the_category(', ') ?></li>
    <?php } ?>
    <li class="post-tags"> <?php the_tags( '<span class="small">'.__('Tags:', 'woothemes').'</span><ul><li>', '</li><li>', '</li></ul>'); ?></li>
</ul>
<?php 
	}
}


/*-----------------------------------------------------------------------------------*/
/* Subscribe / Connect */
/*-----------------------------------------------------------------------------------*/

if (!function_exists( 'woo_subscribe_connect')) {
	function woo_subscribe_connect($widget = 'false', $title = '', $form = '', $social = '') {

		global $woo_options;

		// Setup title
		if ( $widget != 'true' )
			$title = $woo_options[ 'woo_connect_title' ];

		// Setup related post (not in widget)
		$related_posts = '';
		if ( $woo_options[ 'woo_connect_related' ] == "true" AND $widget != "true" )
			$related_posts = '';
			// $related_posts = do_shortcode( '[related_posts_custom limit="5" image="180" post_type="woo_video"]' );

?>
	<?php if ( $woo_options[ 'woo_connect' ] == "true" OR $widget == 'true' ) : ?>
	<div id="connect">
		<h3 class="title"><?php if ( $title ) echo $title; else _e( 'Subscribe', 'woothemes' ); ?></h3>

		<div <?php if ( $related_posts != '' ) echo 'class="col-left"'; ?>>
			<p><?php if ($woo_options[ 'woo_connect_content' ] != '') echo stripslashes($woo_options[ 'woo_connect_content' ]); else _e( 'Subscribe to our e-mail newsletter to receive updates.', 'woothemes' ); ?></p>

			<?php if ( $woo_options[ 'woo_connect_newsletter_id' ] != "" AND $form != 'on' ) : ?>
			<form class="newsletter-form<?php if ( $related_posts == '' ) echo ' fl'; ?>" action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open( 'http://feedburner.google.com/fb/a/mailverify?uri=<?php echo $woo_options[ 'woo_connect_newsletter_id' ]; ?>', 'popupwindow', 'scrollbars=yes,width=550,height=520' );return true">
				<input class="email" type="text" name="email" value="<?php esc_attr_e( 'E-mail', 'woothemes' ); ?>" onfocus="if (this.value == '<?php _e( 'E-mail', 'woothemes' ); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e( 'E-mail', 'woothemes' ); ?>';}" />
				<input type="hidden" value="<?php echo $woo_options[ 'woo_connect_newsletter_id' ]; ?>" name="uri"/>
				<input type="hidden" value="<?php bloginfo( 'name' ); ?>" name="title"/>
				<input type="hidden" name="loc" value="en_US"/>
				<input class="submit" type="submit" name="submit" value="<?php _e( 'Submit', 'woothemes' ); ?>" />
			</form>
			<?php endif; ?>

			<?php if ( $woo_options['woo_connect_mailchimp_list_url'] != "" AND $form != 'on' AND $woo_options['woo_connect_newsletter_id'] == "" ) : ?>
			<!-- Begin MailChimp Signup Form -->
			<div id="mc_embed_signup">
				<form class="newsletter-form<?php if ( $related_posts == '' ) echo ' fl'; ?>" action="<?php echo $woo_options['woo_connect_mailchimp_list_url']; ?>" method="post" target="popupwindow" onsubmit="window.open('<?php echo $woo_options['woo_connect_mailchimp_list_url']; ?>', 'popupwindow', 'scrollbars=yes,width=650,height=520');return true">
					<input type="text" name="EMAIL" class="required email" value="<?php _e('E-mail','woothemes'); ?>"  id="mce-EMAIL" onfocus="if (this.value == '<?php _e('E-mail','woothemes'); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e('E-mail','woothemes'); ?>';}">
					<input type="submit" value="<?php _e('Submit', 'woothemes'); ?>" name="subscribe" id="mc-embedded-subscribe" class="btn submit button">
				</form>
			</div>
			<!--End mc_embed_signup-->
			<?php endif; ?>

			<?php if ( $social != 'on' ) : ?>
			<div class="social<?php if ( $related_posts == '' AND $woo_options[ 'woo_connect_newsletter_id' ] != "" ) echo ' fr'; ?>">
		   		<?php if ( $woo_options[ 'woo_connect_rss' ] == "true" ) { ?>
		   		<a href="<?php if ( $woo_options[ 'woo_feed_url' ] ) { echo $woo_options[ 'woo_feed_url' ]; } else { echo get_bloginfo_rss( 'rss2_url' ); } ?>" class="subscribe"><img src="<?php echo get_template_directory_uri(); ?>/images/ico-social-rss.png" title="<?php esc_attr_e( 'Subscribe to our RSS feed', 'woothemes' ); ?>" alt=""/></a>

		   		<?php } if ( $woo_options[ 'woo_connect_twitter' ] != "" ) { ?>
		   		<a href="<?php echo $woo_options[ 'woo_connect_twitter' ]; ?>" class="twitter"><img src="<?php echo get_template_directory_uri(); ?>/images/ico-social-twitter.png" title="<?php esc_attr_e( 'Follow us on Twitter', 'woothemes' ); ?>" alt=""/></a>

		   		<?php } if ( $woo_options[ 'woo_connect_facebook' ] != "" ) { ?>
		   		<a href="<?php echo $woo_options[ 'woo_connect_facebook' ]; ?>" class="facebook"><img src="<?php echo get_template_directory_uri(); ?>/images/ico-social-facebook.png" title="<?php esc_attr_e( 'Connect on Facebook', 'woothemes' ); ?>" alt=""/></a>

		   		<?php } if ( $woo_options[ 'woo_connect_youtube' ] != "" ) { ?>
		   		<a href="<?php echo $woo_options[ 'woo_connect_youtube' ]; ?>" class="youtube"><img src="<?php echo get_template_directory_uri(); ?>/images/ico-social-youtube.png" title="<?php esc_attr_e( 'Watch on YouTube', 'woothemes' ); ?>" alt=""/></a>

		   		<?php } if ( $woo_options[ 'woo_connect_flickr' ] != "" ) { ?>
		   		<a href="<?php echo $woo_options[ 'woo_connect_flickr' ]; ?>" class="flickr"><img src="<?php echo get_template_directory_uri(); ?>/images/ico-social-flickr.png" title="<?php esc_attr_e( 'See photos on Flickr', 'woothemes' ); ?>" alt=""/></a>

		   		<?php } if ( $woo_options[ 'woo_connect_linkedin' ] != "" ) { ?>
		   		<a href="<?php echo $woo_options[ 'woo_connect_linkedin' ]; ?>" class="linkedin"><img src="<?php echo get_template_directory_uri(); ?>/images/ico-social-linkedin.png" title="<?php esc_attr_e( 'Connect on LinkedIn', 'woothemes' ); ?>" alt=""/></a>

		   		<?php } if ( $woo_options[ 'woo_connect_delicious' ] != "" ) { ?>
		   		<a href="<?php echo $woo_options[ 'woo_connect_delicious' ]; ?>" class="delicious"><img src="<?php echo get_template_directory_uri(); ?>/images/ico-social-delicious.png" title="<?php esc_attr_e( 'Discover on Delicious', 'woothemes' ); ?>" alt=""/></a>

				<?php } ?>
			</div>
			<?php endif; ?>

		</div><!-- col-left -->

		<?php if ( $woo_options[ 'woo_connect_related' ] == "true" AND $related_posts != '' ) : ?>
		<div class="related-posts col-right">
			<h4><?php _e( 'Related Videos:', 'woothemes' ); ?></h4>
			<?php echo $related_posts; ?>
		</div><!-- col-right -->
		<?php wp_reset_query(); endif; ?>

        <div class="fix"></div>
	</div>
	<?php endif; ?>
<?php
	}
}


/*-----------------------------------------------------------------------------------*/
/* END */
/*-----------------------------------------------------------------------------------*/
?>