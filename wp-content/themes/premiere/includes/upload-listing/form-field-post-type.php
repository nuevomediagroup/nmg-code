<?php
		
	/* Post type: All except the "post" type. Get post types from the theme option.
	--------------------------------------------------------------------------------*/
	
		// Setup a variable for displaying the custom post type data.
		$_html = '';
		
		// Determine which post type(s) the user can post, based on the option in the admin.
		
		$allowed_post_types = array();
		
		$_args = array();
		
		// Get all post types.
		
		$wp_custom_post_types = get_post_types( $_args, 'objects' );
		
		// Filter out the post types we don't need.
		
		foreach ($wp_custom_post_types as $post_type_item ) {
		
			$cpt_test = get_option( 'woo_upload_post_types_'.$post_type_item->name );
			
			if ($cpt_test == 'true') {
			
				array_push( $allowed_post_types, $post_type_item->name );
				
			} // End IF Statement
			
		} // End FOREACH Loop
		
		$post_types_data = array();
		
		// Get the data for each of the allowed post types, passing the object to an array.
		
		foreach ( $allowed_post_types as $p ) {
		
			$obj = get_post_type_object( $p );
			
			if ( isset( $obj ) ) {
			
				$post_types_data[$p] = $obj;
			
			} // End IF Statement
		
		} // End FOREACH Loop
		
		// If there's only one post type, display it.
		
		if ( count( $post_types_data ) == 1 ) {
		
			foreach ( $post_types_data as $t => $p ) {
			
				$token = $t;
				$title = $p->labels->singular_name;
				
			} // End FOREACH Loop
		
			$_html .= sprintf( __( 'You are about to create a %s.' , 'woothemes' ), strtolower( $title ) ) . "\n";
			$_html .= '<input type="hidden" name="woo_post_type" value="' . $token . '" />';
		
		} else {
		
			// Otherwise, give the user the choice.
			
			$_html .= '<label for="woo_post_type">' . __( 'Please select one of the following types', 'woothemes' ) . ':</label>';
			
			$_html .= '<select name="woo_post_type">' . "\n";
			
			foreach ( $post_types_data as $t => $p ) {
			
				$_html .= '<option value="' . $t . '">' . $p->labels->singular_name . '</option>' . "\n";
			
			} // End FOREACH Loop
			
			$_html .= '</select>' . "\n";
		
		} // End IF Statement
		
		// If there's nothing to display, don't display the fieldset.
		
		if ( $_html == '' ) {} else {
	
	/*------------------------------------------------------------------------------*/
?>
<fieldset id="custom-post-types" class="custom-post-types">
	<div class="form_row">
<?php
	// And finally, display the data.
	
	echo $_html;
?>
	</div><!--/.form_row-->
</fieldset>
<?php
		} // End IF Statement ( $_html )
?>