( function () {
	'use strict';

	function configureFoodCarousels( root ) {
		var scope = root && root.querySelectorAll ? root : document;
		var carousels = scope.querySelectorAll( '.elementor-element-hmca1001 .swiper, .elementor-element-hmca1002 .swiper' );

		carousels.forEach( function ( carousel ) {
			var swiper = carousel.swiper;
			if ( ! swiper || ! swiper.params || ! swiper.params.autoplay ) {
				return;
			}

			swiper.params.speed = 5000;
			swiper.params.autoplay.delay = 0;
			swiper.params.autoplay.disableOnInteraction = false;
			swiper.params.autoplay.pauseOnMouseEnter = false;
			swiper.params.autoplay.reverseDirection = Boolean( carousel.closest( '.elementor-element-hmca1001' ) );
			swiper.update();
			if ( swiper.autoplay && swiper.autoplay.running ) {
				swiper.autoplay.stop();
				swiper.autoplay.start();
			}
		} );
	}

	function scheduleCarouselSync() {
		window.setTimeout( function () {
			configureFoodCarousels( document );
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
				if ( node && node.closest && node.closest( '.elementor-element-hmca1001, .elementor-element-hmca1002' ) ) {
					scheduleCarouselSync();
				}
			}
		);
	} );
}() );
