<?php
	/* Custom fields.
	--------------------------------------------------------------------------------*/
	
	$post_type = 'woo_video';
	if ( isset( $_POST['woo_post_type'] ) ) { $post_type = $_POST['woo_post_type']; }
	
	$_html = '';
	
	$custom_fields = get_option("woo_custom_template");
	
	$cf_formatted = array();
	
	// Strip out `image` fields into a separate array.
	$cf_images = array();
	
	$accepted_fields = array( 'embed' );
	
	if ( ! is_user_logged_in() ) {
	
		$accepted_fields[] = 'uploader_name';
		$accepted_fields[] = 'uploader_email';
	}
	
	if ( is_array( $custom_fields ) && count( $custom_fields ) ) {
	
		foreach ( $custom_fields as $c ) {
		
			if ( in_array( $c['name'], $accepted_fields ) ) {
			
				// Ignore `googlemap` fields, and place `upload` fields in a separate array.
			
				switch ( $c['type'] ) {
				
					case 'googlemap' :
					
					break;
					
					case 'info' :
					
					break;
					
					case 'upload' :
					
					$c['id'] = $c['name'];
					$c['name'] = $c['label'];
					
					$cf_images[] = $c;
					
					break;
					
					default :
					
					$c['id'] = $c['name'];
					$c['name'] = $c['label'];
					
					$cf_formatted[] = $c;
					
					break;
				
				} // End SWITCH Statement
			
			} // End IF Statement
		
		} // End FOREACH Loop
	
		$return = woothemes_machine( $cf_formatted );
		
		$filtered_return = $return[0];
	
		$replacements = array(
								'<h3' => '<label', 
								'</h3' => '</label', 
								'woo-input' => 'woo-input input-text'
							);
		
		foreach ( $replacements as $k => $v ) {
	
			$filtered_return = str_replace( $k, $v, $filtered_return );
	
		}
	
		$_html .= $filtered_return;
	
	} // End IF Statement
	
	// If there's nothing to display, don't display the fieldset.
	
	if ( $_html == '' ) {} else {
	
	/*------------------------------------------------------------------------------*/
?>
<fieldset id="custom-fields" class="custom-fields">
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