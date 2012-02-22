<?php
// If necessary, force the user to login.
global $woo_options, $current_user;

// Determine whether or not the user can upload a listing (do they need to be registered? If so, are they logged in? Can anyone upload a listing without registering?)

	$can_upload_listing = false;
	
	if ( $woo_options['woo_upload_user_logged_in'] == 'true' ) {
	
		get_currentuserinfo();
		
		if ( is_user_logged_in() ) {
		
			$can_upload_listing = true;
		
		} // End IF Statement ( is_user_logged_in() )

	} else {

		$can_upload_listing = true;

	} // End IF Statement ( $woo_options['woo_upload_user_logged_in'] )
	
// Once we have determined whether or not to display the form, display either the upload form or a login/register option.

if ( $can_upload_listing ) {

// Load the form processing file,
// allowing users to override the form in their child theme.

$templates = array( 'includes/upload-listing/process.php' );
$form = locate_template( $templates, false );

if ( $form == '' ) {} else { require_once( $form ); } // End IF Statement
	
?>
	<form name="woothemes_upload_video" method="post" action="">
<?php

	// Once in, display a form using dynamic data from the database.
	
	$post_type = 'woo_video';
	
	if ( $post_type ) {
	
		// $post_type = strtolower( strip_tags( trim( $_POST['woo_post_type'] ) ) );
	
		// If errors are present, display them for the user.
	
		if ( count( $errors ) ) {
		
			$_html = '';
			
			$_html .= '<p class="woo-sc-box alert">' . "\n";
			
				foreach ( $errors as $e ) {
				
					$_html .= $e . '<br />' . "\n";
				
				} // End FOREACH Loop
			
			$_html .= '</p>' . "\n";
			
			echo $_html;
		
		} // End IF Statement
	
		$button_text = __( 'Post Your Video' , 'woothemes' );
		$action = 'upload';
		
		// $post_type = $_POST['woo_post_type'];
	
		// Display the remaining form.
		
		if ( $_is_processed ) {
		
			$message = sprintf( __( 'Your %s has been submitted for review.', 'woothemes' ), 'video' );
			
			$message = apply_filters( 'woo_listings_thankyou_message', $message );
			
			$message = '<p class="woo-sc-box tick">' . $message . '</p>' . "\n";
			
			echo $message;
		
		} else {
		
			// Load the "Title and content" form,
			// allowing users to override the form in their child theme.
		
			$templates = array( 'includes/upload-listing/form-field-defaults.php' );
			$form = locate_template( $templates, false );
			
			if ( $form == '' ) {} else { require_once( $form ); } // End IF Statement
			
			// Load the "Select custom taxonomies" form,
			// allowing users to override the form in their child theme.
		
			$templates = array( 'includes/upload-listing/form-field-custom-taxonomies.php' );
			$form = locate_template( $templates, false );
			
			if ( $form == '' ) {} else { require_once( $form ); } // End IF Statement
			
			// Load the "Input custom fields" form,
			// allowing users to override the form in their child theme.
		
			$templates = array( 'includes/upload-listing/form-field-custom-fields.php' );
			$form = locate_template( $templates, false );
			
			if ( $form == '' ) {} else { require_once( $form ); } // End IF Statement
			
			// Load the "Upload a featured image" form,
			// allowing users to override the form in their child theme.
			
			// Make sure the user is logged in before allowing them to upload images.
			
			if ( is_user_logged_in() && current_user_can( 'upload_files' ) ) {
			
				$templates = array( 'includes/upload-listing/form-field-featured-image.php' );
				$form = locate_template( $templates, false );
				
				if ( $form == '' ) {} else { require_once( $form ); } // End IF Statement
			
			} // End IF Statement
		
		} // End IF Statement
		
	} else {
	
		$button_text = __( 'Create Entry' , 'woothemes' );
		$action = 'select_post_type';
	
		// No post type has been selected yet. Let the user choose one.

		// Load the "Select a post type" form,
		// allowing users to override the form in their child theme.
	
		$templates = array( 'includes/upload-listing/form-field-post-type.php' );
		$form = locate_template( $templates, false );
		
		if ( $form == '' ) {} else { require_once( $form ); } // End IF Statement

	} // End IF Statement
	
	if ( $_is_processed ) {} else {
?>
	<fieldset class="submit-buttons">
		<input type="hidden" name="woo-action" value="<?php echo $action; ?>" />
		<?php
			// $post_id_val = $_POST['woo_post_id'];
			
			$post_id_val = '';
			
			// If no post ID has yet been set, create an auto-draft.
			if ( $post_id_val == '' ) {
			
				// Get the file that holds get_default_post_to_edit().
				require_once( 'wp-admin/includes/post.php' );
			
				$post_obj = get_default_post_to_edit( $post_type, true );
				$post_id_val = $post_obj->ID;
			
			} // End IF Statement
		?>
		<input type="hidden" name="woo_post_type" value="<?php echo $post_type; ?>" />
		<input type="hidden" name="woo_post_id" value="<?php echo $post_id_val; ?>" />
		<input type="submit" name="woo-submit" class="submit button button-primary" id="submit-button" value="<?php echo $button_text; ?>" />
	</fieldset>
<?php // End the main form. Don't remove this, or the sky will fall on your head. ?>
<?php

	} // End IF Statement ( $_is_processed )
?></form><?php
} else {

	// Display a login/register form.
	
	echo '<div id="woo-listings-login">' . "\n";
	
	echo '<p class="woo-sc-box info">' . __( 'Please login to post a video', 'woothemes' ) . '</p>';
	
	wp_login_form();
	
	echo '</div>' . "\n";
	
	// Only show the "register" link if the user has enabled registrations in their installation.
	
	if ( get_option('users_can_register') ) {
	
		$_register_link = '';
	
		$_site_root_url = site_url();
		
		if ( function_exists( 'network_home_url' ) ) {
		
			$_site_root_url = network_home_url();
		
		} // End IF Statement
		
		$_site_root_url = trailingslashit( $_site_root_url );
		
		$_register_link .= '<p class="woo-sc-box note">' . __( 'Not a member yet? ', 'woothemes' ) . '<a href="' . $_site_root_url . 'wp-login.php?action=register&is_woothemes_register=yes&TB_iframe=true" class="thickbox">' . __( 'Click here to register.', 'woothemes' ) . '</a></p>';
		
		echo $_register_link;
		
		// wp_register( '<span class="woo-listings-register">', '</span>' );
	
	} // End IF Statement

} // End IF Statement ( $can_upload_listing )
?>