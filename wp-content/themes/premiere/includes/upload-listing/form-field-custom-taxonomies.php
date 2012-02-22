<?php	
	/* Taxonomies.
	--------------------------------------------------------------------------------*/
	
	$post_type = 'woo_video';
	if ( isset( $_POST['woo_post_type'] ) ) { $post_type = $_POST['woo_post_type']; }
	
	$_html = '';
	
	$custom_taxonomies = array();
	
	$supported_taxonomies = array( 'woo_video_category', 'post_tag' );
	
	foreach ( $supported_taxonomies as $s ) {
	
		$tax_obj = get_taxonomy( $s );
	
		$custom_taxonomies[] = array(
										'name' => $s, 
										'object_type' => array( 'woo_video' ),  
										'args' => array( 'label' => $tax_obj->labels->name, 'labels' => $tax_obj->labels )
									);
	
	}
	
	$ctx_formatted = array();
		
	if ( count( $custom_taxonomies ) ) {
	
		foreach ( $custom_taxonomies as $ctx ) {
		
			$ctx_formatted[$ctx['name']] = array( 'label' => $ctx['args']['label'], 'plural' => $ctx['args']['labels']->name );
			
			if ( count( $ctx['object_type'] ) ) {
			
				$ctx_formatted[$ctx['name']]['objects'] = join( ',', $ctx['object_type'] );
			
			} // End IF Statement
		
		} // End FOREACH Loop
	
	} // End IF Statement
	
	if ( count( $ctx_formatted ) ) {
	
		foreach ( $ctx_formatted as $k => $v ) {
		
			// Create an array of objects, if necessary.
			$objects = explode( ',', $v['objects'] );
		
			if ( in_array( $post_type, $objects ) ) {} else {
			
				unset( $ctx_formatted[$k] );
			
			} // End IF Statement
		
		} // End FOREACH Loop
			
	} // End IF Statement
	
	if ( count( $ctx_formatted ) ) {
	
		foreach ( $ctx_formatted as $ctx => $data ) {
		
			// Get the data for all the terms.
			$_terms_args = array( 'hide_empty' => false );
			$_terms = get_terms( $ctx, $_terms_args );
			
			if ( count( $_terms ) ) {
		
				$label = $data['plural'];
				
				$label = str_replace( 'Video ', '', $label );
				$label = str_replace( 'Post ', '', $label );
		
				$_html .= '<div class="form_row custom_taxonomy_listing custom_taxonomy_' . $ctx . '" rel="' . $data['objects'] . '">' . "\n";
			
				$_html .= '<label for="custom_taxonomy_' . $ctx . '">' . $label . '</label>';
				
				$_html .= '<select name="custom_taxonomy_' . $ctx . '[]" multiple="multiple">' . "\n";
				
				foreach ( $_terms as $t => $p ) {
				
					$_html .= '<option value="' . $p->term_id . '">' . $p->name . '</option>' . "\n";
				
				} // End FOREACH Loop
				
				$_html .= '</select>' . "\n";
				
				$_html .= '<div class="explain">' . sprintf( __( 'Select one or more %s that apply to your video.', 'woothemes' ), strtolower( $label ) ) . '</div>';
				
				$_html .= '</div>' . "\n";
			
			} // End IF Statement
		
		} // End FOREACH Loop
	
	} // End IF Statement
	
	// If there's nothing to display, don't display the fieldset.
	
	if ( $_html == '' ) {} else {
	
	/*------------------------------------------------------------------------------*/
?>
<fieldset id="custom-taxonomies" class="custom-taxonomies">
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