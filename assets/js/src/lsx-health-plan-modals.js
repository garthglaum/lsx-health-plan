var LSX_HP_MODALS = Object.create( null );

;( function( $, window, document, undefined ) {

    'use strict';

    LSX_HP_MODALS.document = $(document);

    /**
     * Start the JS Class
     */
    LSX_HP_MODALS.init = function() {
        LSX_HP_MODALS.loadIframes();
	};
	

    /**
     * Initiate the Sliders
     */
    LSX_HP_MODALS.loadIframes = function( ) {
		$( ".lsx-health-plan-modal" ).on( 'shown.bs.modal', function(e) {
			if ( 0 < jQuery('#'+e.target.id).find('iframe' ).length ) {
				jQuery('#'+e.target.id).find('iframe' ).each( function(){
					jQuery(this).attr('src', jQuery(this).attr('data-src') );
				});
			}
		 });
	};
	
    /**
     * On document ready.
     *
     * @package    lsx-health-plan
     * @subpackage scripts
     */
    LSX_HP_MODALS.document.ready( function() {
        LSX_HP_MODALS.init();
    } );

} )( jQuery, window, document );