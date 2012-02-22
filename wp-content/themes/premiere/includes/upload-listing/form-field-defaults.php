<?php
	/* Default post fields (title and content).
	--------------------------------------------------------------------------------*/
	
	// $post_type = $_POST['woo_post_type'];
	$post_type = 'woo_video';
	
	/*------------------------------------------------------------------------------*/
?>
<fieldset id="default-fields" class="default-fields">
	<div class="form_row">
		<label for="woo_post_title"><?php _e( 'Title', 'woothemes' ); ?></label>
		<input type="text" name="woo_post_title" class="woo-input input-text input-woo_post_title" value="" />
	</div><!--/.form_row-->
	<div class="form_row">
		<label for="woo_post_content"><?php _e( 'Content', 'woothemes' ); ?></label>
		<textarea name="woo_post_content" class="woo-textarea textarea textarea-woo_post_content"></textarea>
	</div><!--/.form_row-->
</fieldset>