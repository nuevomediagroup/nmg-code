<?php
	// Process the "Upload a listing" form.
	$_action = '';
	$_posttype = 'woo_video';
	if ( isset( $_POST['woo-action'] ) ) { $_action = $_POST['woo-action']; }
	if ( isset( $_POST['woo_post_type'] ) ) { $_posttype = $_POST['woo_post_type']; }
	
	$errors = array();
	$_data = array();
	$post_data = array();
	$custom_taxonomies = array();
	$_is_processed = false;
	
	if ( $_action == 'upload' && $_posttype !== '' ) {

/* Begin preparing data for validation.
------------------------------------------------------------*/
		
		// Skip over these fields during processing.
		$fields_to_ignore = array( 'woo-submit', 'woo-action', 'woo_post_title', 'woo_post_content', 'woo_post_type', 'woo_post_id' );
	
		// Create a temporary array to store our fields.
		$_data = $_POST;
	
		// Remove skipped fields from new array.
		foreach ( $fields_to_ignore as $k => $v ) {
		
			if ( in_array( $v, array_keys( $_data ) ) ) {
			
				unset( $_data[$v] );
			
			} // End IF Statement
		
		} // End FOREACH Loop
		
		// Remove any empty fields from new array.
		foreach ( $_data as $k => $v ) {
		
			if ( $v == '' ) {
			
				unset( $_data[$k] );
			
			} // End IF Statement
		
		} // End FOREACH Loop
		
		// Separate the custom taxonomy fields into a new array.
		foreach ( $_data as $k => $v ) {
		
			$check_string = strpos( $k, 'custom_taxonomy_' );
		
			if ( $check_string === false ) {} else {
			
				$tax = str_replace( 'custom_taxonomy_', '', $k );
			
				$custom_taxonomies[$tax] = $v;
				
				unset( $_data[$k] );
			
			} // End IF Statement
		
		} // End FOREACH Loop
		
		
		// Get an array of all custom fields.
		$custom_fields = get_option("woo_custom_template");
	
		$cf_formatted = array();
		
		// Strip out `image` fields into a separate array.
		$cf_images = array();
		
		if ( is_array( $custom_fields ) && count( $custom_fields ) ) {
	
			foreach ( $custom_fields as $c ) {
			
				if ( array_key_exists( 'cpt', $c ) && is_array( $c['cpt'] ) && in_array( $post_type, array_keys( $c['cpt'] ) ) ) {
				
					// Ignore `googlemap` fields, and place `upload` fields in a separate array.
				
					switch ( $c['type'] ) {
					
						case 'googlemap' :
						
						break;
						
						case 'upload' :
						
						$c['id'] = $c['name'];
						
						$cf_images[] = $c;
						
						break;
						
						default :
						
						$c['id'] = $c['name'];
						
						$cf_formatted[] = $c;
						
						break;
					
					} // End SWITCH Statement
				
				} // End IF Statement
			
			} // End FOREACH Loop
			
		} // End IF Statement
		
		// Separate the post title, post content and post type into their own array.
		$post_data = array();
		
		if ( isset( $_POST['woo_post_title'] ) && $_POST['woo_post_title'] != '' ) {
		
			$post_data['post_title'] = trim( strip_tags( $_POST['woo_post_title'] ) );
		
		} // End IF Statement
		
		if ( isset( $_POST['woo_post_content'] ) && $_POST['woo_post_content'] != '' ) {
		
			$post_data['post_content'] = trim( strip_tags( $_POST['woo_post_content'] ) );
		
		} // End IF Statement
		
		if ( isset( $_POST['woo_post_type'] ) && $_POST['woo_post_type'] != '' ) {
		
			$post_data['post_type'] = trim( strip_tags( $_POST['woo_post_type'] ) );
		
		} // End IF Statement
		
		if ( isset( $_POST['woo_post_id'] ) && $_POST['woo_post_id'] != '' ) {
		
			$post_data['ID'] = intval( $_POST['woo_post_id'] );
		
		} // End IF Statement

/* Begin validating data.
------------------------------------------------------------*/

$message = __( 'Please enter the ', 'woothemes' );

$required_fields = array(
						array( 'id' => 'ID', 'label' => 'post ID', 'type' => 'int' ), 
						array( 'id' => 'post_title', 'label' => 'post title', 'type' => 'text' ), 
						array( 'id' => 'post_content', 'label' => 'post content', 'type' => 'text' ), 
						array( 'id' => 'post_type', 'label' => 'post type', 'type' => 'text' )
					);
					
foreach ( $required_fields as $r ) {

	if ( ! in_array( $r['id'], array_keys( $post_data ) ) || $post_data[$r['id']] == '' ) {
	
		$errors[] = $message . $r['label'] . '.';
	
	} // End IF Statement

} // End FOREACH Loop

/* Begin processing valid data, if all is valid.
------------------------------------------------------------*/

	if ( count( $errors ) > 0 ) {} else {
	
		$_is_processed = false;
	
		// Process the default post content.
		
		$post_data['post_status'] = 'draft';
		
		$post_id = wp_update_post( $post_data );
		
		// If there was an error, report it and stop.
		
		if ( $post_id == 0 ) {
		
			$errors[] = sprintf( __( 'There was an error adding your %s. Please try again.', 'woothemes' ), $post_data['post_type'] );
		
		} else {
		
			// It's all safe. Continue processing the entry.
		
			// Process the custom fields.
			
			$custom_fields = $_data;
			
			if ( count( $custom_fields ) > 0 ) {
			
				foreach ( $custom_fields as $k => $v ) {
				
					add_post_meta( $post_id, $k, $v, true );
				
				} // End FOREACH Loop
			
			} // End IF Statement
			
			// Process the custom taxonomies.
			
			if ( count( $custom_taxonomies ) > 0 ) {
			
				foreach ( $custom_taxonomies as $k => $v ) {
				
					$ids = array();
					
					foreach ( $v as $id ) {
					
						$ids[] = intval( $id );
					
					} // End FOREACH Loop
					
					// Assign the custom taxonomy terms.
					
					wp_set_object_terms( $post_id, $ids, $k );
				
				} // End FOREACH Loop
			
			} // End IF Statement
	
		$_is_processed = true;
		
		} // End IF Statement
	
	} // End IF Statement
		
} // End IF Statement
?>