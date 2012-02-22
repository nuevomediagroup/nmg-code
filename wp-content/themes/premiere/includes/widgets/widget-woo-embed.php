<?php
/*-----------------------------------------------------------------------------------

CLASS INFORMATION

Description: A customised video embed widget, using the "woo_video" custom post type.
Date Created: 2011-03-23.
Author: Matty.
Since: 1.0.0


TABLE OF CONTENTS

- function Woo_Widget_Embed () (constructor)
- function widget ()
- function update ()
- function form ()

- Register the widget on `widgets_init`.

-----------------------------------------------------------------------------------*/

class Woo_Widget_Embed extends WP_Widget {

	/*----------------------------------------
	  Woo_Widget_Embed()
	  ----------------------------------------
	  
	  * The constructor. Sets up the widget.
	----------------------------------------*/
	
	function Woo_Widget_Embed () {
		
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'widget_woo_embed', 'description' => __('Displays the embed code from video posts in a tab-like fashion.', 'woothemes' ) );

		/* Widget control settings. */
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'widget_woo_embed' );

		/* Create the widget. */
		$this->WP_Widget( 'widget_woo_embed', __('Woo - Embed/Video', 'woothemes' ), $widget_ops, $control_ops );
		
		/* Functionality specific to this widget. */
		if(is_active_widget( null, null, 'widget_woo_embed' ) == true) { add_action( 'wp_footer', array( &$this, 'embed_footer_js' ) ); }
		
	} // End Woo_Widget_Embed()

	/*----------------------------------------
	  widget()
	  ----------------------------------------
	  
	  * Displays the widget on the frontend.
	----------------------------------------*/
	
	function widget( $args, $instance ) {
		extract( $args );
		
		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		
		$category = $instance['category'];
		$tag = $instance['tag'];
		$width = $instance['width'];
		$height = $instance['height'];
		$limit = intval( $instance['limit'] );
		
		if ( $limit == 0 ) { $limit = 10; }
		
		/* Get Posts. */
		$query_args = array(
						'post_type' => 'woo_video', 
						'posts_per_page' => $limit
					 );
		
		/* Determine whether to look in a tag or a category. */
		if ( $tag ) {
		
			$query_args['tax_query'] = array(
											array(
												'taxonomy' => 'post_tag',
												'field' => 'slug',
												'terms' => $tag
											)
										);
		
		} else if ( $category && ( $category > 0 ) ) {
		
			$query_args['tax_query'] = array(
											array(
												'taxonomy' => 'woo_video_category',
												'field' => 'id',
												'terms' => $category
											)
										);
		
		}
		
		$query = new WP_Query( $query_args );
		
		wp_reset_query();
		
		$posts = array();
		
		/* Make sure we only have posts of the "woo_video" post type. */
		if ( $query->have_posts() ) {
			while( $query->have_posts() ) {
				$query->the_post();
				
				if ( get_post_type() == 'woo_video' ) {
					$posts[] = $query->post;
				}
				
			}
		}
		
		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title ) {
		
			echo $before_title . $title . $after_title;
		
		} // End IF Statement
		
		/* Widget content. */
		
		// Add actions for plugins/themes to hook onto.
		do_action( 'widget_woo_embed_top' );
		
		$html = '';
		
		if ( $posts ) {
			
			$count = 0;
			$post_list = '';
			$display = '';
			$active = 'active';
			
			foreach ( $posts as $post ) {
				$embed = woo_get_embed( 'embed', $width, $height, 'widget_video', $post->ID );
				$title = get_the_title( $post->ID );
				
				if( $embed ) {
					$count++;
					if( $count > 1 ) {$active = ''; $display = 'style="display:none;"'; }
					
					$html .= '<div class="widget-video-unit" ' . $display . '>' . "\n";
						$html .= '<h4>' . $title . '</h4>' . "\n";
						$html .= $embed . "\n";
						
						$post_list .= '<li class="' . $active . '"><a href="#">' . $title . '</a></li>' . "\n";
					
					$html .= '</div><!--/.widget-video-unit-->' . "\n";
				
				} // End IF Statement
			}
		}
		
		if ( $post_list != '' ) {
		
			$html .= '<ul class="widget-video-list">' . "\n";
				$html .= $post_list;
			$html .= '</ul><!--/.widget-video-list-->' . "\n";
		
		}
		
		echo $html;
		
		// Add actions for plugins/themes to hook onto.
		do_action( 'widget_woo_embed_bottom' );

		/* After widget (defined by themes). */
		echo $after_widget;
		
	} // End widget()

	/*----------------------------------------
	  update()
	  ----------------------------------------
	  
	  * Function to update the settings from
	  * the form() function.
	  
	  * Params:
	  * - Array $new_instance
	  * - Array $old_instance
	----------------------------------------*/
	
	function update ( $new_instance, $old_instance ) {
		
		$instance = $old_instance;

		/* Strip tags for title to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['category'] = $new_instance['category'];
		$instance['tag'] = $new_instance['tag'];
		$instance['width'] = intval( $new_instance['width'] );
		$instance['height'] = intval( $new_instance['height'] );
		$instance['limit'] = intval( $new_instance['limit'] );

		return $instance;
		
	} // End update()

	/*----------------------------------------
	  form()
	  ----------------------------------------
	  
	  * The form on the widget control in the
	  * widget administration area.
	  
	  * Make use of the get_field_id() and 
	  * get_field_name() function when creating
	  * your form elements. This handles the confusing stuff.
	  
	  * Params:
	  * - Array $instance
	----------------------------------------*/

	function form ( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('Recent Videos', 'woothemes' ), 'category' => '', 'tag' => '', 'width' => 280, 'height' => 200, 'limit' => 10 );
		$instance = wp_parse_args( (array) $instance, $defaults );
?>
		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:','woothemes'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" type="text" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
		</p>
		
		<!-- Category: Select -->
		<p>
			<label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e('Category:','woothemes'); ?></label>
			<?php
				$args = array(
								'show_option_none' => __( 'Disabled', 'woothemes' ), 
								'selected' => $instance['category'], 
								'taxonomy' => 'woo_video_category', 
								'id' => $this->get_field_id( 'category' ), 
								'name' => $this->get_field_name( 'category' ), 
								'class' => 'widefat'
							);
				
				wp_dropdown_categories( $args );
			?>
		</p>
		<!-- Tag: Text Input -->
		<p>
            <label for="<?php echo $this->get_field_id( 'tag' ); ?>"><?php _e( 'Or Tag:','woothemes' ); ?></label>
            <input type="text" name="<?php echo $this->get_field_name( 'tag' ); ?>" value="<?php echo $instance['tag']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'tag' ); ?>" />
        </p>
		
		<!-- Size: Text Inputs -->
        <p>
            <label for="<?php echo $this->get_field_id( 'width' ); ?>"><?php _e( 'Size:','woothemes' ); ?></label>
            <input type="text" size="2" name="<?php echo $this->get_field_name( 'width' ); ?>" value="<?php echo $instance['width']; ?>" class="" id="<?php echo $this->get_field_id( 'width' ); ?>" /> W
            <input type="text" size="2" name="<?php echo $this->get_field_name( 'height' ); ?>" value="<?php echo $instance['height']; ?>" class="" id="<?php echo $this->get_field_id( 'height' ); ?>" /> H

        </p>
        
        <!-- Limit: Text Input -->
        <p>
            <label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e( 'Limit <small>(optional)</small>:','woothemes' ); ?></label>
            <input type="text" name="<?php echo $this->get_field_name( 'limit' ); ?>" value="<?php echo $instance['limit']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'limit' ); ?>" />
        </p>

<?php
	
	} // End form()
	
	/*----------------------------------------
	  Utility Functions
	  ----------------------------------------
	  
	  * Functions specific to this widget to
	  * perform simple tasks.
	----------------------------------------*/
	
	function embed_footer_js () {
?>
	<!-- Woo Video Player Widget -->
	<script type="text/javascript">
		jQuery(document).ready(function(){
			var list = jQuery('ul.widget-video-list');
			list.find('a').click(function(){
				var clickedTitle = jQuery(this).text();
				jQuery(this).parent().parent().find('li').removeClass('active');
				jQuery(this).parent().addClass('active');
				var videoHolders = jQuery(this).parent().parent().parent().children('.widget-video-unit');
				videoHolders.each(function(){
					if(clickedTitle == jQuery(this).children('h4').text()){
						videoHolders.hide();
						jQuery(this).show();
					}
				})
				return false;
			})
		})
	</script>
<?php
	} // End embed_footer_js()
	
} // End Class

/*----------------------------------------
  Register the widget on `widgets_init`.
  ----------------------------------------
  
  * Registers this widget.
----------------------------------------*/

add_action( 'widgets_init', create_function( '', 'return register_widget("Woo_Widget_Embed");' ), 1 );
?>