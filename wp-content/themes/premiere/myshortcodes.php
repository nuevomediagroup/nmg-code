<?php
add_shortcode('video_embed', function($atts) {
   $atts = shortcode_atts(
		array(
			'src'			=> 'http://video.nuestra.tv/lic/player.swf',
			'file'      	=> '',
			'autostart'     => 'true',
			'width'			=> 600,
			'height'		=> 388,
			'title'			=> '',
			'adplugin'  	=> 'http://video.nuestra.tv/mk/adaptvjw5.swf',
			'adpluginkey'	=> 'nuevomediagroup',
			'adpluginzid'	=> 'flash_ads',
			'cat'			=> '',
			'duration'		=> '60',
			'skin'			=> '',
			'playlistsize'	=> '',
			'controlbar'	=> 'over',
			'playlist'		=> '',
			'repeat'		=> 'always',
			'adcompanionid' => 'adaptvcompanion'
			
			), $atts);
			
		return  '
		<div class="embed_video">
		<embed src="' . $atts['src'] . '" width="' . $atts['width'] . '" height="' . $atts['height'] . '" type="application/x-shockwave-flash" stretching="fill" provider="http" start="5" volume="50" allowscriptaccess="always" allowfullscreen="true" flashvars="file=' . $atts['file'] . '&plugins=' . $atts['adplugin'] . '&adaptvjw5.key=' . $atts['adpluginkey'] . '&adaptvjw5.categories=' . $atts['cat'] . '&adaptvjw5.duration=' . $atts['duration'] . '&adaptvjw5.zid=' . $atts['adpluginzid'] . '&adaptvjw5.companionid=' . $atts['adcompanionid'] . '&skin=' . $atts['skin'] . '&autostart=' . $atts['autostart'] . '&playlistsize=' . $atts['playlistsize'] . '&playlist=' . $atts['playlist'] . '&controlbar=' . $atts['controlbar'] . '&repeat=' . $atts['repeat'] . '"
/></embed>
<h4>' . $atts['title'] . '</h4>
</div>';
});



