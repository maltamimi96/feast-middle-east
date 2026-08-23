( function () {
	'use strict';

	function reverseTopFoodCarousel( root ) {
		var scope = root && root.querySelectorAll ? root : document;
		var carousels = scope.querySelectorAll( '.elementor-element-hmca1001 .swiper' );

		carousels.forEach( function ( carousel ) {
			var swiper = carousel.swiper;
			if ( ! swiper || ! swiper.params || ! swiper.params.autoplay ) {
				return;
			}

			swiper.params.autoplay.reverseDirection = true;
			if ( swiper.autoplay && swiper.autoplay.running ) {
				swiper.autoplay.stop();
				swiper.autoplay.start();
			}
		} );
	}

	function scheduleCarouselSync() {
		window.setTimeout( function () {
			reverseTopFoodCarousel( document );
		}, 400 );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', scheduleCarouselSync );
	} else {
		scheduleCarouselSync();
	}

	window.addEventListener( 'elementor/frontend/init', function () {
		if ( ! window.elementorFrontend || ! window.elementorFrontend.hooks ) {
			return;
		}

		window.elementorFrontend.hooks.addAction(
			'frontend/element_ready/image-carousel.default',
			function ( element ) {
				var node = element && element[ 0 ] ? element[ 0 ] : element;
				if ( node && node.closest && node.closest( '.elementor-element-hmca1001' ) ) {
					scheduleCarouselSync();
				}
			}
		);
	} );
}() );
