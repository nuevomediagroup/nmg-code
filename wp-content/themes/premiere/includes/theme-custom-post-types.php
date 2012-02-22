<?php
	require_once ( 'custom-post-types/cpt_woo_video.class.php' );

	$woo_video = new Woo_PostType_Video( trailingslashit( dirname( __FILE__ ) ), trailingslashit( get_template_directory_uri() ), 'woo_video_' );
?>