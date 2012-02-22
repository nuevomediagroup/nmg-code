<?php
/*-----------------------------------------------------------------------------------

CLASS INFORMATION

Description: A customised "Recent Comments" widget.
Date Created: 2011-03-23.
Author: Matty.
Since: 1.0.0


TABLE OF CONTENTS

- function Woo_Widget_RecentComments () (constructor)
- function widget ()
- function update ()
- function form ()

- Register the widget on `widgets_init`.

-----------------------------------------------------------------------------------*/

class Woo_Widget_RecentComments extends WP_Widget {

	/*----------------------------------------
	  Woo_Widget_RecentComments()
	  ----------------------------------------
	  
	  * The constructor. Sets up the widget.
	----------------------------------------*/
	
	function Woo_Widget_RecentComments () {
		
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'widget_woo_recentcomments', 'description' => __('Displays the recent comments on your blog.', 'woothemes' ) );

		/* Widget control settings. */
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'widget_woo_recentcomments' );

		/* Create the widget. */
		$this->WP_Widget( 'widget_woo_recentcomments', __('Woo - Recent Comments', 'woothemes' ), $widget_ops, $control_ops );
		
		/* Functions specific to this widget. */
		add_action( 'comment_post', array( &$this, 'flush_widget_cache' ) );
		add_action( 'transition_comment_status', array( &$this, 'flush_widget_cache' ) );
		
	} // End Woo_Widget_RecentComments()

	/*----------------------------------------
	  widget()
	  ----------------------------------------
	  
	  * Displays the widget on the frontend.
	----------------------------------------*/
	
	function widget( $args, $instance ) {
		global $comments, $comment;

		$cache = wp_cache_get( 'widget_woo_recentcomments', 'widget' );

		if ( ! is_array( $cache ) )
			$cache = array();

		if ( isset( $cache[$args['widget_id']] ) ) {
			echo $cache[$args['widget_id']];
			return;
		}
		extract( $args, EXTR_SKIP );
		
		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		
		$number_of_comments = $instance['number_of_comments'];
		$comment_length = $instance['comment_length'];
		
		if ( $comment_length == 0 ) { $comment_length = 70; }
		
		/* Get Comments. */
		$recent_comments = get_comments( array( 'number' => $number_of_comments, 'status' => 'approve' ) );
			
		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title ) {
		
			echo $before_title . $title . $after_title;
		
		} // End IF Statement
		
		/* Widget content. */
		
		// Add actions for plugins/themes to hook onto.
		do_action( 'widget_woo_recentcomments_top' );
		
		$html = '';
		
		if ( $recent_comments ) {
		
			$html .= '<ul class="recentcomments">' . "\n";
			
			foreach ( (array) $recent_comments as $c ) {
				
				$excerpt = strip_tags( $c->comment_content );
				if ( strlen( $excerpt ) > $comment_length ) { $excerpt = substr( $excerpt, 0, $comment_length ) . '...'; }
			
				$html .= '<li>' . "\n";
					$html .= '<div class="comment-excerpt">' . "\n";
						$html .= $excerpt . "\n";
					$html .= '</div><!--/.comment-excerpt-->' . "\n";
					$html .= '<div class="comment-author">' . "\n";
						$html .= sprintf( _x( '%1$s on %2$s', 'widgets' ), get_comment_author_link( $c->comment_ID ), '<a href="' . esc_url( get_comment_link( $c->comment_ID ) ) . '">' . get_the_title( $c->comment_post_ID ) . '</a>') . "\n";
					$html .= '</div><!--/.comment-author-->' . "\n";
				$html .= '</li>' . "\n";
			
			}
			
			$html .= '</ul><!--/.recentcomments-->' . "\n";
			
		}
		
		echo $html;
		
		$cache[$args['widget_id']] = $html;
		wp_cache_set( 'widget_woo_recentcomments', $cache, 'widget' );
		
		// Add actions for plugins/themes to hook onto.
		do_action( 'widget_woo_recentcomments_bottom' );

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

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number_of_comments'] = $new_instance['number_of_comments'];
		$instance['comment_length'] = intval( $new_instance['comment_length'] );
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset( $alloptions['widget_woo_recentcomments'] ) )
			delete_option( 'widget_woo_recentcomments' );

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
		$defaults = array( 'title' => __('Recent Comments', 'woothemes' ), 'number_of_comments' => 5, 'comment_length' => 70 );
		$instance = wp_parse_args( (array) $instance, $defaults );
?>
		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:','woothemes'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" type="text" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
		</p>
		
		<!-- Number of Comments: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'number_of_comments' ); ?>"><?php _e('Number of Comments:','woothemes'); ?></label>
			<input id="<?php echo $this->get_field_id( 'number_of_comments' ); ?>" type="text" name="<?php echo $this->get_field_name( 'number_of_comments' ); ?>" value="<?php echo $instance['number_of_comments']; ?>" class="widefat" />
		</p>
		
		<!-- Comment Length: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'comment_length' ); ?>"><?php _e('Comment Length:','woothemes'); ?></label>
			<input id="<?php echo $this->get_field_id( 'comment_length' ); ?>" type="text" name="<?php echo $this->get_field_name( 'comment_length' ); ?>" value="<?php echo $instance['comment_length']; ?>" class="widefat" />
		</p>

<?php
	
	} // End form()
	
	/*----------------------------------------
	  Utility Functions
	  ----------------------------------------
	  
	  * Functions specific to this widget to
	  * perform simple tasks.
	----------------------------------------*/
	
	function flush_widget_cache() {
		wp_cache_delete( 'widget_woo_recentcomments', 'widget' );
	} // End flush_widget_cache()
	
} // End Class

/*----------------------------------------
  Register the widget on `widgets_init`.
  ----------------------------------------
  
  * Registers this widget.
----------------------------------------*/

add_action( 'widgets_init', create_function( '', 'return unregister_widget("WP_Widget_Recent_Comments");' ), 1 );
add_action( 'widgets_init', create_function( '', 'return register_widget("Woo_Widget_RecentComments");' ), 1 );
?>