<?php
add_shortcode("video_embed", function($atts){
	$atts - shortcode_atts(
		array(
			'playlistfile'	=> '',
            'adaptvjw5.key' => integration_test,
            'image' => ''
			), $atts);
			
	return '		

<embed
type='application/x-shockwave-flash'
plugins='http://nuestra.tv/mk/adaptvjw5.swf'
adaptvjw5.key='integration_test'
adaptvjw5.zid='flash_ads'
image='http://video.nuestra.tv/clips/telenovelas.jpg'
linktarget='http://nuestra.tv'
logo.file='http://d231bp3cl59kev.cloudfront.net/lic/logoplayer3.png'
logo.hide='false'
logo.over='1'
logo.out='0.5'
logo.position='top-left'
logo.margin='2'
logo.link='http://nuestra.tv'
logo.linktarget='_blank'
trackstarts='true'  
trackpercentage='true'
accountid='UA-28001504-1'
trackseconds='true'
tracktime='true'

playlist='left'
playlistsize='250'
start='4'
provider='http'
autostart='true'
repeat='always'
shuffle='false'
stretching='fill'
width='960'
height='380'



id='single2'
name='single2'
src='http://video.nuestra.tv/lic/player.swf'

allowscriptaccess='always'
allowfullscreen='true'
wmode='transparent'
flashvars='playlistfile=http://d231bp3cl59kev.cloudfront.net/clips/afp.rss&playlist=left'
/>
;
});