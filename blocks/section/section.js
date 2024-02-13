jQuery(document).ready(function ($) {
	
	// Init Logo Carousel

	$('.hp-testimonial-section .intro_text').flickity({
		// options
		cellAlign: 'left',
		contain: true,
		pageDots: true,
		prevNextButtons: false,
		freeScroll: true,
		wrapAround: true,
		autoPlay: 3000,
		selectedAttraction: 0.009,
		watchCSS: true
		});

});

document.addEventListener( 'wpcf7mailsent', function( event ) {
	if ( event.detail.contactFormId == '1610' ) {
		location = '/landing-page-thank-you';
	}
}, false );
