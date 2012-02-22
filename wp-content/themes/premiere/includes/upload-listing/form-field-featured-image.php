<?php
	/* Featured image (possibly using new uploader).
	--------------------------------------------------------------------------------*/
	
	$post_type = 'woo_video';
	if ( isset( $_POST['woo_post_type'] ) ) { $post_type = $_POST['woo_post_type']; }
	
	$_html = '';
	
	if ( count( $cf_images ) > 0 ) {
	
		$return = woothemes_machine( $cf_images );
		
		$_html .= $return[0];
	
	} // End IF Statement
	
	if ( $_html == '' ) {} else {
	
	/*------------------------------------------------------------------------------*/
?>
<fieldset id="featured-image" class="featured-image">
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