var factoryhub = factoryhub || {};

( function ( $ ) {
	'use strict';

	$( function() {

		var $body = $('body'),
			$window = $(window),
			$header = $('#masthead'),
			$mobileNav = $('#primary-mobile-nav'),
			$listProject = $('.post-type-archive-project .content-area .list-project');

		// Back to top scroll
		$window.on('scroll', function() {
			if ( $(window).scrollTop() > $(window).height() ) {
				$( '#scroll-top' ).addClass( 'show-scroll' );
			} else {
				$( '#scroll-top' ).removeClass( 'show-scroll' );
			}
		} );

		// Scroll effect button top
		$( '#scroll-top' ).on( 'click', function( event ) {
			event.preventDefault();
			$( 'html, body' ).stop().animate ( {
					scrollTop : 0
				},
				1200
			);
		} );

		$window.on( 'scroll', function () {
			var hHeader = $( '.site-contact' ).outerHeight(true),
				hTopbar = $('.topbar').outerHeight(true );

			hTopbar = hTopbar == undefined ? 0 : hTopbar;
				
			var  scrollTop = hHeader + hTopbar;

			if ( $window.width() <= 1200 ) {
				if ( $body.hasClass( 'hide-topbar-mobile' ) ) {
					scrollTop = 0;
				} else {
					scrollTop = hTopbar;
				}
			}

			if ($window.scrollTop() > scrollTop) {
				$header.addClass('minimized');
				$('#fh-header-minimized').addClass('minimized');
			} else {
				$header.removeClass('minimized');
				$('#fh-header-minimized').removeClass('minimized');
			}
		});

		$window.on( 'resize', function () {
			var wHeight;

			if ( $window.width() < 1200 ) {
				wHeight = $header.outerHeight(true);

				var headerTransparent = $( '.header-transparent .site-header' ),
					hTopbar = $('.topbar').outerHeight(true);

				if ( $window.width() <= 1200 && $body.hasClass( 'hide-topbar-mobile' ) ) {
					if ( $body.hasClass( 'hide-topbar-mobile' ) ) {
						hTopbar = 0;
					}
				}

				if ( $body.hasClass( 'admin-bar' ) ) {
					headerTransparent.css({
						'top' : hTopbar + 42
					});
				} else {
					headerTransparent.css({
						'top' : hTopbar + 10
					});
				}

			} else {
				wHeight = $('.site-menu').outerHeight(true);
			}

			if ( $body.hasClass( 'header-sticky' ) ) {
				$('#fh-header-minimized').height(wHeight);
			}

		}).trigger('resize');

		/**
		 * Filter project category
		 */
		$(window).on('load',function () {
			$('.fh-latest-project').find('.list-project').isotope({
				itemSelector: '.project',
				layoutMode  : 'fitRows'
			});
			$('ul.filter li.active').trigger('click');
		});



		$('ul.filter').on('click', 'li', function (e) {
			e.preventDefault();

			var $this = $( this ),
				selector = $this.attr('data-option-value');

			if ( $this.hasClass( 'active' ) ) {
				return;
			}

			// Archive
			$this.closest( '.filters-dropdown' ).next('.list-project').isotope({
				filter: selector
			});

			$this.addClass( 'active' ).siblings( '.active' ).removeClass( 'active' );

		});

		$listProject.imagesLoaded(function () {
			var args = {
				itemSelector: '.project-wrapper',
				percentPosition: true
			};

			if ( $body.hasClass( 'project-mansony' ) ) {
				args.masonry = {
				   columnWidth: '.project-sizer'
				};
			}

			$listProject.isotope( args );
		} );

		// Product qty change
		$body.on('click', '.quantity .increase, .quantity .decrease', function (e) {
			e.preventDefault();
			var $this = $(this),
				$qty = $this.siblings('.qty'),
				current = parseInt($qty.val(), 10),
				min = parseInt($qty.attr('min'), 10),
				max = parseInt($qty.attr('max'), 10);

			max = max ? max : current + 1;

			if ($this.hasClass('decrease') && current > min) {
				$qty.val(current - 1);
				$qty.trigger( 'change' );
			}
			if ($this.hasClass('increase') && current < max) {
				$qty.val(current + 1);
				$qty.trigger( 'change' );
			}
		});

		$( '.single-project .project-gallery' ).owlCarousel( {
			direction: factoryhub.direction,
			items: 1,
			slideSpeed : 800,
			navigation: false,
			pagination: true,
			autoPlay: true,
			paginationSpeed : 1000,
			navigationText: ['<i class="fa fa-chevron-left" aria-hidden="true"></i>', '<i class="fa fa-chevron-right" aria-hidden="true"></i>'],
			itemsTablet: [768, 1],
			itemsDesktopSmall: [991, 1],
			itemsDesktop: [1199, 1]
		} );


		// product relate
		$( '.single-product .related.products').find( 'ul.products' ).owlCarousel( {
			direction: factoryhub.direction,
			items: 3,
			navigation: true,
			pagination: false,
			navigationText: ['<i class="fa fa-chevron-left" aria-hidden="true"></i>', '<i class="fa fa-chevron-right" aria-hidden="true"></i>']
		} );

		//Section Parallax
		var $pageHeader = $('.page-header .header-title');
		for (var i = 0; i < $pageHeader.length; i++) {
			$($pageHeader[i]).parallax('50%', 0.6);
		}

		$('.post-type-archive-project').find('.numeric-navigation').on('click', '.page-numbers.next', function (e) {
			e.preventDefault();

			if ( $(this).data('requestRunning') ) {
				return;
			}

			$(this).data('requestRunning', true);

			$(this).addClass('loading');

			var $project = $(this).parents('.numeric-navigation').prev('.list-project'),
				$pagination = $(this).parents('.numeric-navigation');

			$.get(
				$(this).attr('href'),
				function (response) {
					var content = $(response).find('.list-project').html(),
						$pagination_html = $(response).find('.numeric-navigation').html();
					var $content = $(content);

					$pagination.html($pagination_html);

					if ($body.hasClass('project-nav-ajax')) {
						$content.imagesLoaded(function () {
							$project.isotope('insert', $content);
							$pagination.find('.page-numbers.next').removeClass('loading');
						});
					} else {
						$project.append($content);
						$pagination.find('.page-numbers.next').removeClass('loading');
					}

					$pagination.find('.page-numbers.next').data('requestRunning', false);
				}
			);
		});

		/**
		 * Hide cart or nav when click to off canvas layer
		 */
		$('#off-canvas-layer').on('click', function (e) {
			e.preventDefault();

			$body.removeClass('display-mobile-menu');
		});

		/**
		 * Off canvas cart toggle
		 */
		$body.on('click', '.navbar-toggle', function (e) {
			e.preventDefault();
			$(this).toggleClass('selected-mobile');
			$body.toggleClass('display-mobile-menu');
		});

		$('ul.menu li.menu-item-has-children a').addClass('dropdown-toggle');

		// Menu Mobile
		menuMobile();

		function menuMobile() {

			$mobileNav.find('.menu .menu-item-has-children').prepend('<span class="toggle-children "><i class="fa fa-angle-right" aria-hidden="true"></i></span>');
			$mobileNav.find('.menu .menu-item-has-children').each(function () {
				var title = '';
				if ($(this).children('a').hasClass('dropdown-toggle')) {
					title = $(this).children('.dropdown-toggle').html();
				}
				$(this).children('ul').prepend('<li class="menu-parent-items">' + title + '</li>');
				$(this).children('ul').prepend('<li class="menu-back">' + factoryhub.factoryhub_back + '</li>');
			});
			$mobileNav.find('.menu .menu-item-has-children').on('click', '.toggle-children', function (e) {
				e.preventDefault();
				$(this).parent('li').addClass('over-menu');
				$(this).parents('.menu').addClass('over-submenu');
			});
			$mobileNav.find('.menu .menu-item-has-children').on('click', '.menu-back', function (e) {
				e.preventDefault();
				$(this).closest('ul').closest('li').removeClass('over-menu');
				if (!$mobileNav.find('.menu .menu-item-has-children').hasClass('over-menu')) {
					$mobileNav.find('.menu').removeClass('over-submenu');
				}
			});

			$mobileNav.on('click', '.close-canvas-mobile-panel', function (e) {
				e.preventDefault();
				$body.toggleClass('display-mobile-menu');
			});
		}

		quickView();

		// Show quick view of product

		function quickView() {
			/**
			 * Product quick view popup
			 */
			var $modal = $( '#modal' ),
				$modalBody = $modal.find( '.modal-body' );

			// Open product single modal
			$( '.woocommerce' ).on( 'click', '.product-quick-view', function( e ) {
				e.preventDefault();

				$modal.fadeIn().addClass( 'in' );
				$modalBody.html( '<div class="ajax-loading"><i class="fa fa-spin fa-spinner"></i></div>' );
				$body.addClass( 'modal-open' );
				$.get( $( this ).attr( 'data-href' ), function( response ) {
					if ( ! response ) {
						return;
					}

					var $content = $( response ).find( '.product-details' );

					$modalBody.html( $content );

					var modalHeight = $modal.find('.modal-content').height(),
						winHeight = $(window).height(),
						topModal = ( winHeight - modalHeight) / 2;

					if( topModal < 0 ) {
						topModal = 0;
					}

					$modal.find('.modal-content').css({'margin-top': topModal});

				} );
			} );

			// Close portfolio modal
			$modal.on( 'click', 'button.close', function( e ) {
				e.preventDefault();

				$modal.fadeOut( 500, function() {
					$body.removeClass( 'modal-open' );
					$modal.removeClass( 'in' );

					// Trigger resize event on $window to make isotope mansory works correctly
					$( window ).trigger( 'resize' );
				} );
			} );
		}

	} );
} )( jQuery );
