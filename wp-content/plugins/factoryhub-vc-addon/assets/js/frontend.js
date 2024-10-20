var factoryhubShortCode = factoryhubShortCode || {},
	factoryhub = factoryhub || {};

( function ( $ ) {
	'use strict';

	$( function() {

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

			// Shortcode
			$this.parents( '.fh-latest-project' ).find('.list-project').isotope({
				filter: selector,
				layoutMode  : 'fitRows'
			});

		});

		// Counter
		function count($this) {
			var current = parseInt($this.html(), 10);
			current = current + 10;
			$this.html(++current);
			if (current > $this.data('count')) {
				$this.html($this.data('count'));
			}
			else {
				setTimeout(function () {
					count($this);
				}, 5);
			}
		}

		//Section Parallax
		var $parallaxsRow = $('.vc_row.parallax');
		for (var i = 0; i < $parallaxsRow.length; i++) {
			$($parallaxsRow[i]).parallax('50%', 0.6);
		}

		/**
		 * Partner
		 */
		$('.fh-partner .list-item').owlCarousel({
			direction: factoryhub.direction,
            items: 5,
            navigation: false,
            autoPlay: false,
            pagination: false
        });

		$('.counter .value').each(function () {
			$(this).data('count', parseInt($(this).html(), 10));
			$(this).html('0');
			count($(this));
		});

		testimonialCarousel();
		testimonialCarousel2();
		testimonialCarousel3();
		postCarousel();
		productCarousel();
		serviceCarousel();

        /**
         * Init testimonials carousel
         */
        function testimonialCarousel() {
            if (factoryhubShortCode.length === 0 || typeof factoryhubShortCode.testimonial === 'undefined') {
                return;
            }
            $.each(factoryhubShortCode.testimonial, function (id, testimonialData) {
	            if (testimonialData.iscarousel == 1) {
		            $(document.getElementById(id)).owlCarousel({
			            direction: factoryhub.direction,
			            singleItem: false,
			            items: testimonialData.columns,
			            autoPlay: testimonialData.autoplay,
			            pagination: true,
			            navigation : false,
			            slideSpeed : 300,
			            paginationSpeed : 500,
			            navigationText: ['<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>'],
			            itemsTablet: [768, 1],
			            itemsDesktopSmall: [991, 1],
			            itemsDesktop: [1199, 1]
		            });
	            }
            });
        }

        /**
         * Init testimonials carousel
         */
        function testimonialCarousel2() {
            if (factoryhubShortCode.length === 0 || typeof factoryhubShortCode.testimonial2 === 'undefined') {
                return;
            }
            $.each(factoryhubShortCode.testimonial2, function (id, testimonialData) {
                $(document.getElementById(id)).owlCarousel({
                    direction: factoryhub.direction,
					singleItem: true,
					autoPlay: testimonialData.autoplay,
					pagination: true,
					navigation : false,
					slideSpeed : 300,
					paginationSpeed : 500,
					navigationText: ['<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>'],
					itemsTablet: [768, 1],
	                itemsDesktopSmall: [991, 1],
	                itemsDesktop: [1199, 1]
                });
            });
        }

		/**
		 * Init testimonials carousel
		 */
		function testimonialCarousel3() {
			if (factoryhubShortCode.length === 0 || typeof factoryhubShortCode.testimonial3 === 'undefined') {
				return;
			}
			$.each(factoryhubShortCode.testimonial3, function (id, testimonial3Data) {
				$(document.getElementById(id)).owlCarousel({
					direction: factoryhub.direction,
					singleItem: true,
					autoPlay: testimonial3Data.autoplay,
					pagination: true,
					navigation : false,
					slideSpeed : 300,
					paginationSpeed : 500,
					navigationText: ['<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>'],
					itemsTablet: [768, 1],
					itemsDesktopSmall: [991, 1],
					itemsDesktop: [1199, 1]
				});
			});
		}

        /**
         * Init product carousel
         */
        function productCarousel() {
            if (factoryhubShortCode.length === 0 || typeof factoryhubShortCode.product === 'undefined') {
                return;
            }
            $.each(factoryhubShortCode.product, function (id, productData) {
            	var columns = 4;
				if (productData.columns < 4) {
					columns = productData.columns;
				}

                $(document.getElementById(id)).find('ul.products').owlCarousel({
                    direction: factoryhub.direction,
					items: columns,
					autoPlay: productData.autoplay,
					pagination: false,
					navigation : true,
					slideSpeed : 300,
					paginationSpeed : 500,
					navigationText: ['<i class="fa fa-chevron-left" aria-hidden="true"></i>', '<i class="fa fa-chevron-right" aria-hidden="true"></i>']
                });
            });
        }

		/**
		 * Init post carousel
		 */
		function postCarousel() {
			if (factoryhubShortCode.length === 0 || typeof factoryhubShortCode.post === 'undefined') {
				return;
			}
			$.each(factoryhubShortCode.post, function (id, postData) {

				$(document.getElementById(id)).owlCarousel({
					direction: factoryhub.direction,
					items: postData.columns,
					autoPlay: postData.autoplay,
					pagination: true,
					navigation : false,
					slideSpeed : 300,
					paginationSpeed : 500,
					navigationText: ['<i class="fa fa-chevron-left" aria-hidden="true"></i>', '<i class="fa fa-chevron-right" aria-hidden="true"></i>'],
					itemsTablet: [480, 1],
					itemsDesktopSmall: [991, 2],
					itemsDesktop: [1199, 3]
				});
			});
		}

		/**
		 * Init post carousel
		 */
		function serviceCarousel() {
			if (factoryhubShortCode.length === 0 || typeof factoryhubShortCode.service === 'undefined') {
				return;
			}
			$.each(factoryhubShortCode.service, function (id, serviceData) {
				if (serviceData.iscarousel == 1) {
					$(document.getElementById(id)).owlCarousel({
						direction: factoryhub.direction,
						items: serviceData.columns,
						autoPlay: serviceData.autoplay,
						pagination: true,
						navigation : false,
						slideSpeed : 300,
						paginationSpeed : 500,
						navigationText: ['<i class="fa fa-chevron-left" aria-hidden="true"></i>', '<i class="fa fa-chevron-right" aria-hidden="true"></i>'],
						itemsTablet: [480, 1],
						itemsDesktopSmall: [991, 2],
						itemsDesktop: [1199, 3]
					});
				}
			});
		}

		$( '.fh-project-carousel' ).each( function(){
			var $this = $( this),
				columns = $this.data( 'columns' );

			$this.find( '.list-project' ).owlCarousel({
				direction: factoryhub.direction,
				singleItem: false,
				items: columns,
				autoPlay: 5000,
				pagination: false,
				navigation : true,
				slideSpeed : 300,
				paginationSpeed : 500,
				navigationText: ['<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>'],
				itemsMobile: [480, 1],
				itemsTablet: [768, 2],
				itemsDesktopSmall: [991, 3],
				itemsDesktop: [1199, 4]
			} );
		} );

		factoryhubMaps();

		/**
         * Init Google maps
         */
        function factoryhubMaps() {
            if (factoryhubShortCode.length === 0 || typeof factoryhubShortCode.map === 'undefined') {
                return;
            }
            var style1 =
	            [
		            {
			            'featureType': 'administrative',
			            'elementType': 'labels.text.fill',
			            'stylers': [
				            {
					            'color': '#444444'
				            }
			            ]
		            },
		            {
			            'featureType': 'landscape',
			            'elementType': 'all',
			            'stylers': [
				            {
					            'color': '#f2f2f2'
				            }
			            ]
		            },
		            {
			            'featureType': 'poi',
			            'elementType': 'all',
			            'stylers': [
				            {
					            'visibility': 'off'
				            }
			            ]
		            },
		            {
			            'featureType': 'road',
			            'elementType': 'all',
			            'stylers': [
				            {
					            'saturation': -100
				            },
				            {
					            'lightness': 45
				            }
			            ]
		            },
		            {
			            'featureType': 'road.highway',
			            'elementType': 'all',
			            'stylers': [
				            {
					            'visibility': 'simplified'
				            }
			            ]
		            },
		            {
			            'featureType': 'road.highway',
			            'elementType': 'geometry.stroke',
			            'stylers': [
				            {
					            'color': '#cecece'
				            }
			            ]
		            },
		            {
			            'featureType': 'road.arterial',
			            'elementType': 'labels.icon',
			            'stylers': [
				            {
					            'visibility': 'off'
				            }
			            ]
		            },
		            {
			            'featureType': 'transit',
			            'elementType': 'all',
			            'stylers': [
				            {
					            'visibility': 'off'
				            }
			            ]
		            },
		            {
			            'featureType': 'water',
			            'elementType': 'all',
			            'stylers': [
				            {
					            'color': '#ffc811'
				            },
				            {
					            'visibility': 'on'
				            }
			            ]
		            }
	            ];
			var style2 = [];

			var mapOptions = {
                scrollwheel: false,
                draggable: true,
                zoom: 10,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                panControl: false,
                zoomControl: true,
                zoomControlOptions: {
                    style: google.maps.ZoomControlStyle.SMALL
                },
                scaleControl: false,
                streetViewControl: false

            },
            customMap,
			styles;

            var bounds = new google.maps.LatLngBounds();
            var infoWindow = new google.maps.InfoWindow();


            $.each(factoryhubShortCode.map, function (id, mapData) {
	            if (mapData.style == '1') {
		            styles = style1;
	            } else {
		            styles = style2;
	            }

            	customMap = new google.maps.StyledMapType(styles,
                    {name: 'Styled Map'});

                if ( mapData.number > 1 ) {
					mutiMaps(infoWindow, bounds, mapOptions, mapData, id, styles, customMap);
				} else {
					singleMap(mapOptions, mapData, id, styles, customMap);
				}

            });
        }

        function singleMap(mapOptions, mapData, id, styles, customMap) {
			var map,
				marker,
				location = new google.maps.LatLng(mapData.lat, mapData.lng);

			// Update map options
			mapOptions.zoom = parseInt(mapData.zoom, 10);
			mapOptions.center = location;
			mapOptions.mapTypeControlOptions = {
				mapTypeIds: [google.maps.MapTypeId.ROADMAP]
			};

			// Init map
			map = new google.maps.Map(document.getElementById(id), mapOptions);

			// Create marker options
			var markerOptions = {
				map     : map,
				position: location
			};
			if (mapData.marker) {
				markerOptions.icon = {
					url: mapData.marker
				};
			}

			map.mapTypes.set('map_style', customMap);
			map.setMapTypeId('map_style');

			// Init marker
			marker = new google.maps.Marker(markerOptions);

			if (mapData.info) {
				var infoWindow = new google.maps.InfoWindow({
					content : '<div class="info-box fb-map">' + mapData.info + '</div>',
					maxWidth: 600
				});

				google.maps.event.addListener(marker, 'click', function () {
					infoWindow.open(map, marker);
				});
			}
		}

		function mutiMaps(infoWindow, bounds, mapOptions, mapData, id, styles, customMap) {

			// Display a map on the page
			mapOptions.zoom = parseInt(mapData.zoom, 10);
			mapOptions.mapTypeControlOptions = {
				mapTypeIds: [google.maps.MapTypeId.ROADMAP]
			};

			var map = new google.maps.Map(document.getElementById(id), mapOptions);
			map.mapTypes.set('map_style', customMap);
			map.setMapTypeId('map_style');
			for (var i = 0; i < mapData.number; i++) {
				var lats = mapData.lat,
					lng = mapData.lng,
					info = mapData.info;

				var position = new google.maps.LatLng(lats[i], lng[i]);
				bounds.extend(position);

				// Create marker options
				var markerOptions = {
					map     : map,
					position: position
				};
				if (mapData.marker) {
					markerOptions.icon = {
						url: mapData.marker
					};
				}

				// Init marker
				var marker = new google.maps.Marker(markerOptions);

				// Allow each marker to have an info window
				googleMaps(infoWindow, map, marker, info[i]);

				// Automatically center the map fitting all markers on the screen
				map.fitBounds(bounds);
			}
		}

        function googleMaps(infoWindow, map, marker, info) {
            google.maps.event.addListener(marker, 'click', function () {
                infoWindow.setContent(info);
                infoWindow.open(map, marker);
            });
        }

	} );
} )( jQuery );
