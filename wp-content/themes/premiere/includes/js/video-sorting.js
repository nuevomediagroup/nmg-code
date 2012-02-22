/*-----------------------------------------------------------------------------------

FILE INFORMATION

Description: JavaScript functions for video sorting.
Date Created: 2011-03-24.
Author: Matty.
Since: 1.0.0


TABLE OF CONTENTS

- AJAX call for sorting.

- function woo_show_loader()
- function woo_remove_loader()

-----------------------------------------------------------------------------------*/

jQuery(function($) {	

/*-----------------------------------------------------------------------------------
  AJAX call for sorting.
-----------------------------------------------------------------------------------*/

	if ( jQuery('ul.sorting a').length ) {

		jQuery( 'ul.sorting a' ).live( 'click', function () {
		
			var url = jQuery( this ).attr( 'href' );
			var sectionToLoad = '#main';
		
			if ( url ) {
			
				jQuery( sectionToLoad ).fadeTo( 'slow', 0.5, function () {
					
					woo_show_loader( 'woo-loader' );
					
					jQuery.ajax({
						url: url, 
						success: function ( data ) {
							var content = jQuery( data ).find( sectionToLoad ).html();
							if ( content ) {
								jQuery( sectionToLoad ).html( content ).fadeTo( 'slow', 1 );
							}
						}, 
						error: function ( jqXHR, textStatus, errorThrown ) {}, 
						complete: function ( jqXHR, textStatus ) { woo_remove_loader( 'woo-loader' ); }
					});
				
				});
			
			}
		
			return false;
		
		});

	} // End IF Statement
	
/*-----------------------------------------------------------------------------------
  woo_show_loader()
-----------------------------------------------------------------------------------*/

function woo_show_loader( loaderId ) {

	var loadingDiv = jQuery( '<div></div>' ).attr( 'id', loaderId ).addClass( 'loading' ).html( '<span>Loading</span>' ).hide();

	jQuery( '#main' ).before( loadingDiv );
	jQuery( loadingDiv ).fadeTo( 'slow', 1 );

} // End woo_show_loader()

/*-----------------------------------------------------------------------------------
  woo_remove_loader()
-----------------------------------------------------------------------------------*/

function woo_remove_loader( loaderId ) {

	jQuery( '#' + loaderId ).fadeTo( 'slow', 0, function () {
		jQuery( this ).remove();
	});

} // End woo_show_loader()
	
});