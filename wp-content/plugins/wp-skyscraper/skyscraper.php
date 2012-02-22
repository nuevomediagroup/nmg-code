<?php

/*
Plugin Name: WP Skyscraper
Plugin URI: http://jakubas.net.pl/projekty/narzedzia/wp-skyscraper-plugin
Description: WP Skyscraper is a wordpress plugin that allows you to add fixed - box on your wordpress blog.
Author: Piotr Jakubas
Version: 0.1
Author URI: http://jakubas.net.pl
*/

function wp_skyscraper_url( $path = '' ) {
	global $wp_version;
	if ( version_compare( $wp_version, '2.8', '<' ) ) { // WordPress 2.7
		$folder = dirname( plugin_basename( __FILE__ ) );
		if ( '.' != $folder )
			$path = path_join( ltrim( $folder, '/' ), $path );

		return plugins_url( $path );
	}
	return plugins_url( $path, __FILE__ );
}

function activate_wp_skyscraper() {
	$wp_skyscraper_opts1 = get_option('wp_skyscraper_options');
	$wp_skyscraper_opts2 =array();
	if ($wp_skyscraper_opts1) {
	    $wp_skyscraper = $wp_skyscraper_opts1 + $wp_skyscraper_opts2;
		update_option('wp_skyscraper_options',$wp_skyscraper);
	}
	else {
		
		$wp_skyscraper_opts1 = array(	
									'html' => '<span><a href="http://jakubas.net.pl/">WP Skyscraper</a></span>',

									'top'=>'100',
									'align'=>'right',
									'width'=>'100',
									'height'=>'200',
											
									'textcolor'=>'282828',
									'textsize'=>'14',
									'textfont'=>'Verdana',
									'bgcolor'=>'FFFFFF',
									'bordercolor'=>'282828',

						);	
		$wp_skyscraper = $wp_skyscraper_opts1 + $wp_skyscraper_opts2;
		add_option('wp_skyscraper_options',$wp_skyscraper);		
	}
}

						  
register_activation_hook( __FILE__, 'activate_wp_skyscraper' );
global $wp_skyscraper;
$wp_skyscraper = get_option('wp_skyscraper_options');
define("wp_skyscraper_VER","0.9",false);

//inline styles
function wp_skyscraper_css() {
?>

<style type="text/css">

.wp_skyscraper_c2 {
	position:fixed;
	background:#<?php global $wp_skyscraper; echo $wp_skyscraper[bgcolor]; ?>;
	top:<?php global $wp_skyscraper; echo $wp_skyscraper['top'];?>px;
	<?php global $wp_skyscraper; echo $wp_skyscraper['align'];?>:0px;
	width:<?php global $wp_skyscraper; if(is_int($wp_skyscraper['width']*1)){ echo $wp_skyscraper['width']; } else echo "50";  ?>px;
	height:<?php global $wp_skyscraper; if(is_int($wp_skyscraper['height']*1)){ echo $wp_skyscraper['height']; } else echo "200";  ?>px;
	border:1px solid #<?php global $wp_skyscraper; echo $wp_skyscraper['bordercolor'];?>;
	color:#<?php global $wp_skyscraper; echo $wp_skyscraper[textcolor]; ?>;
}
</style>

<?php
}
add_action('wp_head', 'wp_skyscraper_css');


function show_skyscraper() {

?>

<div style="position:relative;">
  <div class="wp_skyscraper_c2">
		<?php global $wp_skyscraper; echo $wp_skyscraper['html'];?>
  </div>
</div>

<?php
}
add_action( 'get_footer', 'show_skyscraper' );


function wp_skyscraper_settings() {
    // Add a new submenu under Options:
    add_options_page('WP Skyscraper', 'WP Skyscraper', 9, basename(__FILE__), 'wp_skyscraper_settings_page');
}

function wp_skyscraper_admin_head() {
?>

<?php
}

add_action('admin_head', 'wp_skyscraper_admin_head');

function wp_skyscraper_settings_page() {
	require_once(ABSPATH.'/wp-admin/includes/plugin-install.php');

?>
<link rel="stylesheet" media="screen" type="text/css" href="<?php echo wp_skyscraper_url('js/css/colorpicker.css'); ?>" />
<script type="text/javascript" src="<?php echo wp_skyscraper_url('js/js/jquery.js'); ?>"></script>
<script type="text/javascript" src="<?php echo wp_skyscraper_url('js/js/colorpicker.js'); ?>"></script>



<script type="text/javascript">
$(function(){


	 $('#wp_skyscraper_options_textcolor').ColorPicker({
			color: '#<?php echo $wp_skyscraper[textcolor]; ?>',
			onShow: function (colpkr) {
				$(colpkr).fadeIn(500);
				return false;
			},
			onHide: function (colpkr) {
				$(colpkr).fadeOut(500);
				return false;
			},
			onChange: function (hsb, hex, rgb) {
				$('#wp_skyscraper_options_textcolor').css('backgroundColor', '#' + hex);
				$('#wp_skyscraper_options_textcolor').val(hex);
			}
		});
		
	
	 $('#wp_skyscraper_options_bgcolor').ColorPicker({
			color: '#<?php echo $wp_skyscraper[bgcolor]; ?>',
			onShow: function (colpkr) {
				$(colpkr).fadeIn(500);
				return false;
			},
			onHide: function (colpkr) {
				$(colpkr).fadeOut(500);
				return false;
			},
			onChange: function (hsb, hex, rgb) {
				$('#wp_skyscraper_options_bgcolor').css('backgroundColor', '#' + hex);
				$('#wp_skyscraper_options_bgcolor').val(hex);
			}
		});

	 
	 $('#wp_skyscraper_options_bordercolor').ColorPicker({
			color: '#<?php echo $wp_skyscraper[bordercolor]; ?>',
			onShow: function (colpkr) {
				$(colpkr).fadeIn(500);
				return false;
			},
			onHide: function (colpkr) {
				$(colpkr).fadeOut(500);
				return false;
			},
			onChange: function (hsb, hex, rgb) {
				$('#wp_skyscraper_options_bordercolor').css('backgroundColor', '#' + hex);
				$('#wp_skyscraper_options_bordercolor').val(hex);
			}
		});		

});
</script>
<div class="wrap">
<h2>WP Skyscraper</h2>

<form  method="post" action="options.php">
<div id="poststuff" class="metabox-holder has-right-sidebar"> 

<div style="float:left;width:60%;">
<?php
settings_fields('wp-skyscraper-group');
$wp_skyscraper = get_option('wp_skyscraper_options');
?>
<h2>Settings</h2> 

<div class="postbox">
<h3 style="cursor:pointer;"><span>WP Skyscraper Options</span></h3>
<div>
<table class="form-table">


<tr valign="top" class="alternate"> 
		<th scope="row" style="width:20%;"><label for="wp_skyscraper_options[twitter]" style="font-weight:bold;">Your HTML</label></th> 
	<td>
	<textarea autocomplete="off" type="text" cols="65" rows="15"  class="regular-text code" name="wp_skyscraper_options[html]"> <?php echo $wp_skyscraper[html]; ?></textarea> <br />
	<?php 
//		if ( substr($wp_skyscraper[html], 0, 4) != "http" && $wp_skyscraper[html]){
//		echo '<span style="color:red;">Error:</span> <strong>The Twitter URL must begin with <em>http</em></strong>';
//		}
//		if ( !$wp_skyscraper[html] ){
//		echo '<span style="color:red;">Error:</span> <strong>The Twitter URL cannot be blank</strong>';
//		}
		
	?>
	</td>
</tr>



<tr valign="top"> 
		<th scope="row" style="width:20%;"><label for="wp_skyscraper_options[bgcolor]">Background color</label></th> 
	<td><input autocomplete="off" type="text" id="wp_skyscraper_options_bgcolor" name="wp_skyscraper_options[bgcolor]" value="<?php echo $wp_skyscraper[bgcolor]; ?>" id="iconbgall" class="color regular-text code" style="background-color:#<?php echo $wp_skyscraper[bgcolor]; ?>;" />
		
	</td>
	
</tr>



<tr valign="top" class="alternate"> 
		<th scope="row" style="width:20%;"><label for="wp_skyscraper_options[textcolor]">Text color</label></th> 
	<td><input autocomplete="off" type="text" id="wp_skyscraper_options_textcolor" name="wp_skyscraper_options[textcolor]" value="<?php echo $wp_skyscraper[textcolor]; ?>" class="color regular-text code" style="background-color:#<?php echo $wp_skyscraper[textcolor]; ?>;" />
		
	</td>
</tr>

<tr valign="top" class="alternate"> 
		<th scope="row" style="width:20%;"><label for="wp_skyscraper_options[bordercolor]">Badge border color</label></th> 
	<td><input autocomplete="off" type="text" id="wp_skyscraper_options_bordercolor" name="wp_skyscraper_options[bordercolor]" value="<?php echo $wp_skyscraper[bordercolor]; ?>" class="color regular-text code" style="background-color:#<?php echo $wp_skyscraper[bordercolor]; ?>;"/>
		
	</td>
</tr>

<tr valign="top">
<th scope="row"><label for="wp_skyscraper_options[align]">Alignment</label></th>
<td><select name="wp_skyscraper_options[align]">
<option value="left" <?php if ($wp_skyscraper['align'] == "left"){ echo "selected";}?> >Left</option>
<option value="right" <?php if ($wp_skyscraper['align'] == "right"){ echo "selected";}?> >Right</option>
</select></td>
</tr>

<tr valign="top">
<th scope="row"><label for="wp_skyscraper_options[top]">Distance From Top</label></th> 
<td><input type="text" name="wp_skyscraper_options[top]" class="small-text" value="<?php echo $wp_skyscraper['top']; ?>" />&nbsp;px</td>
</tr>

<tr valign="top">
<th scope="row"><label for="wp_skyscraper_options[width]">Width</label></th> 
<td><input type="text" name="wp_skyscraper_options[width]" class="small-text" value="<?php echo $wp_skyscraper['width']; ?>" />&nbsp;px</td>
</tr>

<tr valign="top">
<th scope="row"><label for="wp_skyscraper_options[height]">Height</label></th> 
<td><input type="text" name="wp_skyscraper_options[height]" class="small-text" value="<?php echo $wp_skyscraper['height']; ?>" />&nbsp;px</td>
</tr>

</table>
</div>
</div>


<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>

</div>
</form>

   <div id="side-info-column" class="inner-sidebar"> 
			<div class="postbox"> 
			  <h3 class="hndle"><span>WP Skyscraper</span></h3>
			  <div class="inside">
                <ul>
                <li><a href="http://jakubas.net.pl/projekty/narzedzia/wp-skyscraper-plugin" title="WP Skyscraper plugin page" target="_blank">Plugin Homepage</a></li>
                <li><a href="http://jakubas.net.pl/" title="Visit jakubas.net.pl Website" target="_blank">jakubas.net.pl Website</a></li>
                
                </ul> 
              </div> 
			</div> 
     </div>
     

</div> 


</div> <!--end wrap -->
<?php	
}
// adding admin menus
if ( is_admin() ){ // admin actions
  add_action('admin_menu', 'wp_skyscraper_settings');
  add_action( 'admin_init', 'register_wp_skyscraper_settings' ); 
} 
function register_wp_skyscraper_settings() { // whitelist options
  register_setting( 'wp-skyscraper-group', 'wp_skyscraper_options' );
}

?>