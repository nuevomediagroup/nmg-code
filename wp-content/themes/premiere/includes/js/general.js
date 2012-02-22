jQuery(document).ready(function(){

	// Add alt-row styling to tables
	jQuery('.entry table tr:odd').addClass('alt-table-row');
	
	// Remove borders on video sharing links
	jQuery('#feat-video .share li:first-child').addClass('first');
	jQuery('#feat-video .share li:last-child').addClass('last');
	
	// Add "last" class to related videos li
	jQuery('.related-videos ul li:nth-child(3n)').addClass('last');
	
	jQuery(".slide-overlay-toggle").each(function(){
		
		jQuery(this).click(function(){
			jQuery(this).parent().hide();
		});
		
	});
	
	
});

jQuery('.tab-image-block img').mouseover(function() {
    jQuery(this).stop().fadeTo(400, 0.2);
});
jQuery('.tab-image-block img').mouseout(function() {
    jQuery(this).stop().fadeTo(500, 1.0);
});
