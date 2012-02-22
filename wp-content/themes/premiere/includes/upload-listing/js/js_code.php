<?php
	// Interpret this as a JavaScript file.
	header("Content-Type:text/javascript");
	
	// Get the path to the root.
	$full_path = __FILE__;
	
	$path_bits = explode( 'wp-content', $full_path );
	
	$url = $path_bits[0];
	
	// Load the WordPress bootstrap. This is relative to the path of this file.
	require_once( $url . '/wp-load.php' );
?>
jQuery(document).ready(function(){
	
	//ADD CLASS TO LOGIN FORM BUTTON
	jQuery('#wp-submit').addClass('button');
	
	//JQUERY DATEPICKER
	jQuery('.woo-input-calendar').each(function (){
		jQuery('#' + jQuery(this).attr('id')).datepicker({showOn: 'button', buttonImage: '<?php echo get_bloginfo('template_directory'); ?>/functions/images/calendar.gif', buttonImageOnly: true});
	});
	
	//JQUERY TIME INPUT MASK
	jQuery('.woo-input-time').each(function (){
		jQuery('#' + jQuery(this).attr('id')).mask("99:99");
	});
	
	// Validate form.
	jQuery('form[name="woo_listings_upload_listing"]').validate({
	
		onsubmit: true, 
		modal: true, 
		errorLabelContainer: ".error_msg",
		
		success: "valid", 
  		
  		submitHandler: function ( form ) {
  		
  			form.submit();
  			
  		},
		rules: {
		 woo_post_title: "required", 
	     woo_post_content: "required"
	   },
	   messages: {
	     woo_post_title: "Please enter a title", 
	     woo_post_content: "Please enter some content"
	   }
		
	});
	
	// AJAX on the frontend login form.
	jQuery('#woo-listings-login form').submit( function () {
	
		var formAction = jQuery(this).attr('action');
	
		var log = jQuery(this).find('#user_login').val();
		var pwd = jQuery(this).find('#user_pass').val();
	
		var notice_class = 'alert';
		
		var is_logged_in = false;
	
		jQuery.post( formAction, { log: log, pwd: pwd }, function ( data ) {
		
			var message_html = jQuery(data).find('#login_error').html();
		
			// If it's null, we know we can login.
			if ( message_html == null ) {
			
				message_html = 'Login successful.';
				
				notice_class = 'tick';
				
				is_logged_in = true;
			
			} // End IF Statement
			
			jQuery('#woo-listings-login .woo-sc-box').remove();
			
			jQuery('#woo-listings-login').prepend( '<p class="woo-sc-box ' + notice_class + '">' + message_html + '</p>' );
			
			// Refresh the window.
			
			if ( is_logged_in ) {
			
				window.location.reload(true);
			
			} // End IF Statement
		
		});
	
		return false;
	
	});


});