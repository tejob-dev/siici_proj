<?php
/**
 * Custom functions for Visual Composer
 *
 * @package    FactoryHub
 * @subpackage Visual Composer
 */

if ( ! function_exists( 'is_plugin_active' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

/**
 * Class FactoryHub
 *
 * @since 1.0.0
 */
class FactoryHub_VC {

	/**
	 * List of available icons
	 *
	 * @var array
	 */
	public $icons;

	/**
	 * Construction
	 */
	function __construct() {
		// Stop if VC is not installed
		if ( ! is_plugin_active( 'js_composer/js_composer.php' ) ) {
			return false;
		}

		$this->icons = self::get_icons();

		if ( function_exists( 'vc_add_shortcode_param' ) ) {
			vc_add_shortcode_param( 'icon', array( $this, 'icon_param' ), FACTORYHUB_ADDONS_URL . '/assets/js/vc/icon-field.js' );
		} elseif ( function_exists( 'add_shortcode_param' ) ) {
			add_shortcode_param( 'icon', array( $this, 'icon_param' ), FACTORYHUB_ADDONS_URL . '/assets/js/vc/icon-field.js' );
		} else {
			return false;
		}

		add_action( 'init', array( $this, 'map_shortcodes' ), 20 );
	}

	/**
	 * Define icon classes
	 *
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public static function get_icons() {
		$icons_awesome = array(
			'fa fa-500px',
			'fa fa-adjust',
			'fa fa-adn',
			'fa fa-align-center',
			'fa fa-align-justify',
			'fa fa-align-left',
			'fa fa-align-right',
			'fa fa-amazon',
			'fa fa-ambulance',
			'fa fa-american-sign-language-interpreting',
			'fa fa-anchor',
			'fa fa-android',
			'fa fa-angellist',
			'fa fa-angle-double-down',
			'fa fa-angle-double-left',
			'fa fa-angle-double-right',
			'fa fa-angle-double-up',
			'fa fa-angle-down',
			'fa fa-angle-left',
			'fa fa-angle-right',
			'fa fa-angle-up',
			'fa fa-apple',
			'fa fa-archive',
			'fa fa-area-chart',
			'fa fa-arrow-circle-down',
			'fa fa-arrow-circle-left',
			'fa fa-arrow-circle-o-down',
			'fa fa-arrow-circle-o-left',
			'fa fa-arrow-circle-o-right',
			'fa fa-arrow-circle-o-up',
			'fa fa-arrow-circle-right',
			'fa fa-arrow-circle-up',
			'fa fa-arrow-down',
			'fa fa-arrow-left',
			'fa fa-arrow-right',
			'fa fa-arrow-up',
			'fa fa-arrows',
			'fa fa-arrows-alt',
			'fa fa-arrows-h',
			'fa fa-arrows-v',
			'fa fa-asl-interpreting',
			'fa fa-assistive-listening-systems',
			'fa fa-asterisk',
			'fa fa-at',
			'fa fa-audio-description',
			'fa fa-automobile',
			'fa fa-backward',
			'fa fa-balance-scale',
			'fa fa-ban',
			'fa fa-bank',
			'fa fa-bar-chart',
			'fa fa-bar-chart-o',
			'fa fa-barcode',
			'fa fa-bars',
			'fa fa-battery-0',
			'fa fa-battery-1',
			'fa fa-battery-2',
			'fa fa-battery-3',
			'fa fa-battery-4',
			'fa fa-battery-empty',
			'fa fa-battery-full',
			'fa fa-battery-half',
			'fa fa-battery-quarter',
			'fa fa-battery-three-quarters',
			'fa fa-bed',
			'fa fa-beer',
			'fa fa-behance',
			'fa fa-behance-square',
			'fa fa-bell',
			'fa fa-bell-o',
			'fa fa-bell-slash',
			'fa fa-bell-slash-o',
			'fa fa-bicycle',
			'fa fa-binoculars',
			'fa fa-birthday-cake',
			'fa fa-bitbucket',
			'fa fa-bitbucket-square',
			'fa fa-bitcoin',
			'fa fa-black-tie',
			'fa fa-blind',
			'fa fa-bluetooth',
			'fa fa-bluetooth-b',
			'fa fa-bold',
			'fa fa-bolt',
			'fa fa-bomb',
			'fa fa-book',
			'fa fa-bookmark',
			'fa fa-bookmark-o',
			'fa fa-braille',
			'fa fa-briefcase',
			'fa fa-btc',
			'fa fa-bug',
			'fa fa-building',
			'fa fa-building-o',
			'fa fa-bullhorn',
			'fa fa-bullseye',
			'fa fa-bus',
			'fa fa-buysellads',
			'fa fa-cab',
			'fa fa-calculator',
			'fa fa-calendar',
			'fa fa-calendar-check-o',
			'fa fa-calendar-minus-o',
			'fa fa-calendar-o',
			'fa fa-calendar-plus-o',
			'fa fa-calendar-times-o',
			'fa fa-camera',
			'fa fa-camera-retro',
			'fa fa-car',
			'fa fa-caret-down',
			'fa fa-caret-left',
			'fa fa-caret-right',
			'fa fa-caret-square-o-down',
			'fa fa-caret-square-o-left',
			'fa fa-caret-square-o-right',
			'fa fa-caret-square-o-up',
			'fa fa-caret-up',
			'fa fa-cart-arrow-down',
			'fa fa-cart-plus',
			'fa fa-cc',
			'fa fa-cc-amex',
			'fa fa-cc-diners-club',
			'fa fa-cc-discover',
			'fa fa-cc-jcb',
			'fa fa-cc-mastercard',
			'fa fa-cc-paypal',
			'fa fa-cc-stripe',
			'fa fa-cc-visa',
			'fa fa-certificate',
			'fa fa-chain',
			'fa fa-chain-broken',
			'fa fa-check',
			'fa fa-check-circle',
			'fa fa-check-circle-o',
			'fa fa-check-square',
			'fa fa-check-square-o',
			'fa fa-chevron-circle-down',
			'fa fa-chevron-circle-left',
			'fa fa-chevron-circle-right',
			'fa fa-chevron-circle-up',
			'fa fa-chevron-down',
			'fa fa-chevron-left',
			'fa fa-chevron-right',
			'fa fa-chevron-up',
			'fa fa-child',
			'fa fa-chrome',
			'fa fa-circle',
			'fa fa-circle-o',
			'fa fa-circle-o-notch',
			'fa fa-circle-thin',
			'fa fa-clipboard',
			'fa fa-clock-o',
			'fa fa-clone',
			'fa fa-close',
			'fa fa-cloud',
			'fa fa-cloud-download',
			'fa fa-cloud-upload',
			'fa fa-cny',
			'fa fa-code',
			'fa fa-code-fork',
			'fa fa-codepen',
			'fa fa-codiepie',
			'fa fa-coffee',
			'fa fa-cog',
			'fa fa-cogs',
			'fa fa-columns',
			'fa fa-comment',
			'fa fa-comment-o',
			'fa fa-commenting',
			'fa fa-commenting-o',
			'fa fa-comments',
			'fa fa-comments-o',
			'fa fa-compass',
			'fa fa-compress',
			'fa fa-connectdevelop',
			'fa fa-contao',
			'fa fa-copy',
			'fa fa-copyright',
			'fa fa-creative-commons',
			'fa fa-credit-card',
			'fa fa-credit-card-alt',
			'fa fa-crop',
			'fa fa-crosshairs',
			'fa fa-css3',
			'fa fa-cube',
			'fa fa-cubes',
			'fa fa-cut',
			'fa fa-cutlery',
			'fa fa-dashboard',
			'fa fa-dashcube',
			'fa fa-database',
			'fa fa-deaf',
			'fa fa-deafness',
			'fa fa-dedent',
			'fa fa-delicious',
			'fa fa-desktop',
			'fa fa-deviantart',
			'fa fa-diamond',
			'fa fa-digg',
			'fa fa-dollar',
			'fa fa-dot-circle-o',
			'fa fa-download',
			'fa fa-dribbble',
			'fa fa-dropbox',
			'fa fa-drupal',
			'fa fa-edge',
			'fa fa-edit',
			'fa fa-eject',
			'fa fa-ellipsis-h',
			'fa fa-ellipsis-v',
			'fa fa-empire',
			'fa fa-envelope',
			'fa fa-envelope-o',
			'fa fa-envelope-square',
			'fa fa-envira',
			'fa fa-eraser',
			'fa fa-eur',
			'fa fa-euro',
			'fa fa-exchange',
			'fa fa-exclamation',
			'fa fa-exclamation-circle',
			'fa fa-exclamation-triangle',
			'fa fa-expand',
			'fa fa-expeditedssl',
			'fa fa-external-link',
			'fa fa-external-link-square',
			'fa fa-eye',
			'fa fa-eye-slash',
			'fa fa-eyedropper',
			'fa fa-fa',
			'fa fa-facebook',
			'fa fa-facebook-f',
			'fa fa-facebook-official',
			'fa fa-facebook-square',
			'fa fa-fast-backward',
			'fa fa-fast-forward',
			'fa fa-fax',
			'fa fa-feed',
			'fa fa-female',
			'fa fa-fighter-jet',
			'fa fa-file',
			'fa fa-file-archive-o',
			'fa fa-file-audio-o',
			'fa fa-file-code-o',
			'fa fa-file-excel-o',
			'fa fa-file-image-o',
			'fa fa-file-movie-o',
			'fa fa-file-o',
			'fa fa-file-pdf-o',
			'fa fa-file-photo-o',
			'fa fa-file-picture-o',
			'fa fa-file-powerpoint-o',
			'fa fa-file-sound-o',
			'fa fa-file-text',
			'fa fa-file-text-o',
			'fa fa-file-video-o',
			'fa fa-file-word-o',
			'fa fa-file-zip-o',
			'fa fa-files-o',
			'fa fa-film',
			'fa fa-filter',
			'fa fa-fire',
			'fa fa-fire-extinguisher',
			'fa fa-firefox',
			'fa fa-first-order',
			'fa fa-flag',
			'fa fa-flag-checkered',
			'fa fa-flag-o',
			'fa fa-flash',
			'fa fa-flask',
			'fa fa-flickr',
			'fa fa-floppy-o',
			'fa fa-folder',
			'fa fa-folder-o',
			'fa fa-folder-open',
			'fa fa-folder-open-o',
			'fa fa-font',
			'fa fa-font-awesome',
			'fa fa-fonticons',
			'fa fa-fort-awesome',
			'fa fa-forumbee',
			'fa fa-forward',
			'fa fa-foursquare',
			'fa fa-frown-o',
			'fa fa-futbol-o',
			'fa fa-gamepad',
			'fa fa-gavel',
			'fa fa-gbp',
			'fa fa-ge',
			'fa fa-gear',
			'fa fa-gears',
			'fa fa-genderless',
			'fa fa-get-pocket',
			'fa fa-gg',
			'fa fa-gg-circle',
			'fa fa-gift',
			'fa fa-git',
			'fa fa-git-square',
			'fa fa-github',
			'fa fa-github-alt',
			'fa fa-github-square',
			'fa fa-gitlab',
			'fa fa-gittip',
			'fa fa-glass',
			'fa fa-glide',
			'fa fa-glide-g',
			'fa fa-globe',
			'fa fa-google',
			'fa fa-google-plus',
			'fa fa-google-plus-circle',
			'fa fa-google-plus-official',
			'fa fa-google-plus-square',
			'fa fa-google-wallet',
			'fa fa-graduation-cap',
			'fa fa-gratipay',
			'fa fa-group',
			'fa fa-h-square',
			'fa fa-hacker-news',
			'fa fa-hand-grab-o',
			'fa fa-hand-lizard-o',
			'fa fa-hand-o-down',
			'fa fa-hand-o-left',
			'fa fa-hand-o-right',
			'fa fa-hand-o-up',
			'fa fa-hand-paper-o',
			'fa fa-hand-peace-o',
			'fa fa-hand-pointer-o',
			'fa fa-hand-rock-o',
			'fa fa-hand-scissors-o',
			'fa fa-hand-spock-o',
			'fa fa-hand-stop-o',
			'fa fa-hard-of-hearing',
			'fa fa-hashtag',
			'fa fa-hdd-o',
			'fa fa-header',
			'fa fa-headphones',
			'fa fa-heart',
			'fa fa-heart-o',
			'fa fa-heartbeat',
			'fa fa-history',
			'fa fa-home',
			'fa fa-hospital-o',
			'fa fa-hotel',
			'fa fa-hourglass',
			'fa fa-hourglass-1',
			'fa fa-hourglass-2',
			'fa fa-hourglass-3',
			'fa fa-hourglass-end',
			'fa fa-hourglass-half',
			'fa fa-hourglass-o',
			'fa fa-hourglass-start',
			'fa fa-houzz',
			'fa fa-html5',
			'fa fa-i-cursor',
			'fa fa-ils',
			'fa fa-image',
			'fa fa-inbox',
			'fa fa-indent',
			'fa fa-industry',
			'fa fa-info',
			'fa fa-info-circle',
			'fa fa-inr',
			'fa fa-instagram',
			'fa fa-institution',
			'fa fa-internet-explorer',
			'fa fa-intersex',
			'fa fa-ioxhost',
			'fa fa-italic',
			'fa fa-joomla',
			'fa fa-jpy',
			'fa fa-jsfiddle',
			'fa fa-key',
			'fa fa-keyboard-o',
			'fa fa-krw',
			'fa fa-language',
			'fa fa-laptop',
			'fa fa-lastfm',
			'fa fa-lastfm-square',
			'fa fa-leaf',
			'fa fa-leanpub',
			'fa fa-legal',
			'fa fa-lemon-o',
			'fa fa-level-down',
			'fa fa-level-up',
			'fa fa-life-bouy',
			'fa fa-life-buoy',
			'fa fa-life-ring',
			'fa fa-life-saver',
			'fa fa-lightbulb-o',
			'fa fa-line-chart',
			'fa fa-link',
			'fa fa-linkedin',
			'fa fa-linkedin-square',
			'fa fa-linux',
			'fa fa-list',
			'fa fa-list-alt',
			'fa fa-list-ol',
			'fa fa-list-ul',
			'fa fa-location-arrow',
			'fa fa-lock',
			'fa fa-long-arrow-down',
			'fa fa-long-arrow-left',
			'fa fa-long-arrow-right',
			'fa fa-long-arrow-up',
			'fa fa-low-vision',
			'fa fa-magic',
			'fa fa-magnet',
			'fa fa-mail-forward',
			'fa fa-mail-reply',
			'fa fa-mail-reply-all',
			'fa fa-male',
			'fa fa-map',
			'fa fa-map-marker',
			'fa fa-map-o',
			'fa fa-map-pin',
			'fa fa-map-signs',
			'fa fa-mars',
			'fa fa-mars-double',
			'fa fa-mars-stroke',
			'fa fa-mars-stroke-h',
			'fa fa-mars-stroke-v',
			'fa fa-maxcdn',
			'fa fa-meanpath',
			'fa fa-medium',
			'fa fa-medkit',
			'fa fa-meh-o',
			'fa fa-mercury',
			'fa fa-microphone',
			'fa fa-microphone-slash',
			'fa fa-minus',
			'fa fa-minus-circle',
			'fa fa-minus-square',
			'fa fa-minus-square-o',
			'fa fa-mixcloud',
			'fa fa-mobile',
			'fa fa-mobile-phone',
			'fa fa-modx',
			'fa fa-money',
			'fa fa-moon-o',
			'fa fa-mortar-board',
			'fa fa-motorcycle',
			'fa fa-mouse-pointer',
			'fa fa-music',
			'fa fa-navicon',
			'fa fa-neuter',
			'fa fa-newspaper-o',
			'fa fa-object-group',
			'fa fa-object-ungroup',
			'fa fa-odnoklassniki',
			'fa fa-odnoklassniki-square',
			'fa fa-opencart',
			'fa fa-openid',
			'fa fa-opera',
			'fa fa-optin-monster',
			'fa fa-outdent',
			'fa fa-pagelines',
			'fa fa-paint-brush',
			'fa fa-paper-plane',
			'fa fa-paper-plane-o',
			'fa fa-paperclip',
			'fa fa-paragraph',
			'fa fa-paste',
			'fa fa-pause',
			'fa fa-pause-circle',
			'fa fa-pause-circle-o',
			'fa fa-paw',
			'fa fa-paypal',
			'fa fa-pencil',
			'fa fa-pencil-square',
			'fa fa-pencil-square-o',
			'fa fa-percent',
			'fa fa-phone',
			'fa fa-phone-square',
			'fa fa-photo',
			'fa fa-picture-o',
			'fa fa-pie-chart',
			'fa fa-pied-piper',
			'fa fa-pied-piper-alt',
			'fa fa-pied-piper-pp',
			'fa fa-pinterest',
			'fa fa-pinterest-p',
			'fa fa-pinterest-square',
			'fa fa-plane',
			'fa fa-play',
			'fa fa-play-circle',
			'fa fa-play-circle-o',
			'fa fa-plug',
			'fa fa-plus',
			'fa fa-plus-circle',
			'fa fa-plus-square',
			'fa fa-plus-square-o',
			'fa fa-power-off',
			'fa fa-print',
			'fa fa-product-hunt',
			'fa fa-puzzle-piece',
			'fa fa-qq',
			'fa fa-qrcode',
			'fa fa-question',
			'fa fa-question-circle',
			'fa fa-question-circle-o',
			'fa fa-quote-left',
			'fa fa-quote-right',
			'fa fa-ra',
			'fa fa-random',
			'fa fa-rebel',
			'fa fa-recycle',
			'fa fa-reddit',
			'fa fa-reddit-alien',
			'fa fa-reddit-square',
			'fa fa-refresh',
			'fa fa-registered',
			'fa fa-remove',
			'fa fa-renren',
			'fa fa-reorder',
			'fa fa-repeat',
			'fa fa-reply',
			'fa fa-reply-all',
			'fa fa-resistance',
			'fa fa-retweet',
			'fa fa-rmb',
			'fa fa-road',
			'fa fa-rocket',
			'fa fa-rotate-left',
			'fa fa-rotate-right',
			'fa fa-rouble',
			'fa fa-rss',
			'fa fa-rss-square',
			'fa fa-rub',
			'fa fa-ruble',
			'fa fa-rupee',
			'fa fa-safari',
			'fa fa-save',
			'fa fa-scissors',
			'fa fa-scribd',
			'fa fa-search',
			'fa fa-search-minus',
			'fa fa-search-plus',
			'fa fa-sellsy',
			'fa fa-send',
			'fa fa-send-o',
			'fa fa-server',
			'fa fa-share',
			'fa fa-share-alt',
			'fa fa-share-alt-square',
			'fa fa-share-square',
			'fa fa-share-square-o',
			'fa fa-shekel',
			'fa fa-sheqel',
			'fa fa-shield',
			'fa fa-ship',
			'fa fa-shirtsinbulk',
			'fa fa-shopping-bag',
			'fa fa-shopping-basket',
			'fa fa-shopping-cart',
			'fa fa-sign-in',
			'fa fa-sign-language',
			'fa fa-sign-out',
			'fa fa-signal',
			'fa fa-signing',
			'fa fa-simplybuilt',
			'fa fa-sitemap',
			'fa fa-skyatlas',
			'fa fa-skype',
			'fa fa-slack',
			'fa fa-sliders',
			'fa fa-slideshare',
			'fa fa-smile-o',
			'fa fa-snapchat',
			'fa fa-snapchat-ghost',
			'fa fa-snapchat-square',
			'fa fa-soccer-ball-o',
			'fa fa-sort',
			'fa fa-sort-alpha-asc',
			'fa fa-sort-alpha-desc',
			'fa fa-sort-amount-asc',
			'fa fa-sort-amount-desc',
			'fa fa-sort-asc',
			'fa fa-sort-desc',
			'fa fa-sort-down',
			'fa fa-sort-numeric-asc',
			'fa fa-sort-numeric-desc',
			'fa fa-sort-up',
			'fa fa-soundcloud',
			'fa fa-space-shuttle',
			'fa fa-spinner',
			'fa fa-spoon',
			'fa fa-spotify',
			'fa fa-square',
			'fa fa-square-o',
			'fa fa-stack-exchange',
			'fa fa-stack-overflow',
			'fa fa-star',
			'fa fa-star-half',
			'fa fa-star-half-empty',
			'fa fa-star-half-full',
			'fa fa-star-half-o',
			'fa fa-star-o',
			'fa fa-steam',
			'fa fa-steam-square',
			'fa fa-step-backward',
			'fa fa-step-forward',
			'fa fa-stethoscope',
			'fa fa-sticky-note',
			'fa fa-sticky-note-o',
			'fa fa-stop',
			'fa fa-stop-circle',
			'fa fa-stop-circle-o',
			'fa fa-street-view',
			'fa fa-strikethrough',
			'fa fa-stumbleupon',
			'fa fa-stumbleupon-circle',
			'fa fa-subscript',
			'fa fa-subway',
			'fa fa-suitcase',
			'fa fa-sun-o',
			'fa fa-superscript',
			'fa fa-support',
			'fa fa-table',
			'fa fa-tablet',
			'fa fa-tachometer',
			'fa fa-tag',
			'fa fa-tags',
			'fa fa-tasks',
			'fa fa-taxi',
			'fa fa-television',
			'fa fa-tencent-weibo',
			'fa fa-terminal',
			'fa fa-text-height',
			'fa fa-text-width',
			'fa fa-th',
			'fa fa-th-large',
			'fa fa-th-list',
			'fa fa-themeisle',
			'fa fa-thumb-tack',
			'fa fa-thumbs-down',
			'fa fa-thumbs-o-down',
			'fa fa-thumbs-o-up',
			'fa fa-thumbs-up',
			'fa fa-ticket',
			'fa fa-times',
			'fa fa-times-circle',
			'fa fa-times-circle-o',
			'fa fa-tint',
			'fa fa-toggle-down',
			'fa fa-toggle-left',
			'fa fa-toggle-off',
			'fa fa-toggle-on',
			'fa fa-toggle-right',
			'fa fa-toggle-up',
			'fa fa-trademark',
			'fa fa-train',
			'fa fa-transgender',
			'fa fa-transgender-alt',
			'fa fa-trash',
			'fa fa-trash-o',
			'fa fa-tree',
			'fa fa-trello',
			'fa fa-tripadvisor',
			'fa fa-trophy',
			'fa fa-truck',
			'fa fa-try',
			'fa fa-tty',
			'fa fa-tumblr',
			'fa fa-tumblr-square',
			'fa fa-turkish-lira',
			'fa fa-tv',
			'fa fa-twitch',
			'fa fa-twitter',
			'fa fa-twitter-square',
			'fa fa-umbrella',
			'fa fa-underline',
			'fa fa-undo',
			'fa fa-universal-access',
			'fa fa-university',
			'fa fa-unlink',
			'fa fa-unlock',
			'fa fa-unlock-alt',
			'fa fa-unsorted',
			'fa fa-upload',
			'fa fa-usb',
			'fa fa-usd',
			'fa fa-user',
			'fa fa-user-md',
			'fa fa-user-plus',
			'fa fa-user-secret',
			'fa fa-user-times',
			'fa fa-users',
			'fa fa-venus',
			'fa fa-venus-double',
			'fa fa-venus-mars',
			'fa fa-viacoin',
			'fa fa-viadeo',
			'fa fa-viadeo-square',
			'fa fa-video-camera',
			'fa fa-vimeo',
			'fa fa-vimeo-square',
			'fa fa-vine',
			'fa fa-vk',
			'fa fa-volume-control-phone',
			'fa fa-volume-down',
			'fa fa-volume-off',
			'fa fa-volume-up',
			'fa fa-warning',
			'fa fa-wechat',
			'fa fa-weibo',
			'fa fa-weixin',
			'fa fa-whatsapp',
			'fa fa-wheelchair',
			'fa fa-wheelchair-alt',
			'fa fa-wifi',
			'fa fa-wikipedia-w',
			'fa fa-windows',
			'fa fa-won',
			'fa fa-wordpress',
			'fa fa-wpbeginner',
			'fa fa-wpforms',
			'fa fa-wrench',
			'fa fa-xing',
			'fa fa-xing-square',
			'fa fa-y-combinator',
			'fa fa-y-combinator-square',
			'fa fa-yahoo',
			'fa fa-yc',
			'fa fa-yc-square',
			'fa fa-yelp',
			'fa fa-yen',
			'fa fa-yoast',
			'fa fa-youtube',
			'fa fa-youtube-play',
			'fa fa-youtube-square',
		);

		$icons_moon = array(
			'factory-left-quotes-sign',
			'factory-mechanic',
			'factory-quality',
			'factory-science',
			'factory-tool',
			'factory-wrench',
			'factory-badge',
			'factory-business',
			'factory-check',
			'factory-cogwheel',
			'factory-internet',
			'factory-layers',
			'factory-leaf',
			'factory-link',
			'factory-motor',
			'factory-people',
			'factory-people-1',
			'factory-power',
			'factory-science2',
			'factory-technology',
			'factory-technology-1',
			'factory-technology-2',
			'factory-text',
			'factory-tool2',
			'factory-travel',
			'factory-wall-clock',
		);

		$flat_icon = array(
			'flaticon-technology-2',
			'flaticon-clock',
			'flaticon-music',
			'flaticon-road',
			'flaticon-multiply',
			'flaticon-nature',
			'flaticon-piston',
			'flaticon-drawing',
			'flaticon-wind',
			'flaticon-folder',
			'flaticon-tool',
			'flaticon-box',
			'flaticon-mark',
			'flaticon-avatar',
			'flaticon-pencil',
			'flaticon-wrench',
			'flaticon-check',
			'flaticon-interface',
			'flaticon-globe',
			'flaticon-global',
			'flaticon-science-1',
			'flaticon-mathematics',
			'flaticon-science',
			'flaticon-business',
			'flaticon-web',
			'flaticon-technology-1',
			'flaticon-technology',
			'flaticon-gps',
			'flaticon-pin',
			'flaticon-signs',
		);

		$icons = array_merge( $icons_awesome, $icons_moon, $flat_icon );

		return apply_filters( 'factoryhub_theme_icons', $icons );
	}

	/**
	 * Add new params or add new shortcode to VC
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	function map_shortcodes() {

		vc_remove_param( 'vc_row', 'parallax_image' );
		vc_remove_param( 'vc_row', 'parallax' );
		vc_remove_param( 'vc_row', 'parallax_speed_bg' );

		$attributes = array(
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Enable Parallax effect', 'factoryhub' ),
				'param_name'  => 'enable_parallax',
				'group'       => esc_html__( 'Design Options', 'factoryhub' ),
				'value'       => array( esc_html__( 'Enable', 'factoryhub' ) => 'yes' ),
				'description' => esc_html__( 'Enable this option if you want to have parallax effect on this row. When you enable this option, please set background repeat option as "Theme defaults" to make it works.', 'factoryhub' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_html__( 'Overlay', 'factoryhub' ),
				'param_name'  => 'overlay',
				'group'       => esc_html__( 'Design Options', 'factoryhub' ),
				'value'       => '',
				'description' => esc_html__( 'Select an overlay color for this row', 'factoryhub' ),
			),
		);

		vc_add_params( 'vc_row', $attributes );

		// FactoryHub Section Title
		vc_map(
			array(
				'name'     => esc_html__( 'FactoryHub Section Title', 'factoryhub' ),
				'base'     => 'fh_section_title',
				'class'    => '',
				'category' => esc_html__( 'FactoryHub', 'factoryhub' ),
				'params'   => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Title', 'factoryhub' ),
						'param_name'  => 'title',
						'value'       => '',
						'description' => esc_html__( 'Enter the title', 'factoryhub' ),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Style', 'factoryhub' ),
						'param_name' => 'style',
						'value'      => array(
							esc_html__( 'Style 1 ( Dark )', 'factoryhub' )  => '1',
							esc_html__( 'Style 2 ( Light )', 'factoryhub' ) => '2',
						),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Position', 'factoryhub' ),
						'param_name' => 'position',
						'value'      => array(
							esc_html__( 'Left', 'factoryhub' )   => 'left',
							esc_html__( 'Center', 'factoryhub' ) => 'center',
						),
					),
					array(
						'type'        => 'textfield',

						'heading'     => esc_html__( 'Extra class name', 'factoryhub' ),
						'param_name'  => 'el_class',
						'value'       => '',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'factoryhub' ),
					),
				),
			)
		);

		// FactoryHub Contact Box
		vc_map(
			array(
				'name'     => esc_html__( 'FactoryHub Contact Box', 'factoryhub' ),
				'base'     => 'fh_contact_box',
				'class'    => '',
				'category' => esc_html__( 'FactoryHub', 'factoryhub' ),
				'params'   => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Title', 'factoryhub' ),
						'param_name'  => 'title',
						'value'       => '',
						'description' => esc_html__( 'Enter the title', 'factoryhub' ),
					),
					array(
						'type'       => 'param_group',
						'heading'    => esc_html__( 'Address Infomation', 'factoryhub' ),
						'value'      => '',
						'param_name' => 'info',
						'params'     => array(
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__( 'Info Title', 'factoryhub' ),
								'param_name' => 'info_title',
							),
							array(
								'type'       => 'textarea',
								'heading'    => esc_html__( 'Info Details', 'factoryhub' ),
								'param_name' => 'info_details',
							),
						)
					),
					array(
						'type'        => 'textfield',

						'heading'     => esc_html__( 'Extra class name', 'factoryhub' ),
						'param_name'  => 'el_class',
						'value'       => '',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'factoryhub' ),
					),
				),
			)
		);

		// FactoryHub Projects Carousel
		vc_map(
			array(
				'name'     => esc_html__( 'FactoryHub Projects Carousel', 'factoryhub' ),
				'base'     => 'fh_project_carousel',
				'class'    => '',
				'category' => esc_html__( 'FactoryHub', 'factoryhub' ),
				'params'   => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Link text', 'factoryhub' ),
						'param_name'  => 'link_text',
						'value'       => esc_html__( 'More Projects', 'factoryhub' ),
						'description' => esc_html__( 'Enter the title', 'factoryhub' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Number of Projects', 'factoryhub' ),
						'param_name'  => 'number',
						'value'       => 'All',
						'description' => esc_html__( 'Set numbers of Projects you want to display', 'factoryhub' ),
					),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Columns', 'factoryhub' ),
						'param_name'  => 'columns',
						'value'       => array(
							esc_html__( '6 Columns', 'factoryhub' ) => 6,
							esc_html__( '5 Columns', 'factoryhub' ) => 5,
							esc_html__( '4 Columns', 'factoryhub' ) => 4,
						),
						'description' => esc_html__( 'Display Projects in how many columns', 'factoryhub' ),
						'std'         => 5
					),
					array(
						'type'        => 'colorpicker',
						'heading'     => esc_html__( 'Background Overlay', 'factoryhub' ),
						'param_name'  => 'overlay',
						'value'       => '',
						'description' => esc_html__( 'Select an overlay color for this element', 'factoryhub' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'factoryhub' ),
						'param_name'  => 'el_class',
						'value'       => '',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'factoryhub' ),
					),
				),
			)
		);

		// FactoryHub Service
		vc_map(
			array(
				'name'     => esc_html__( 'FactoryHub Service', 'factoryhub' ),
				'base'     => 'fh_service',
				'class'    => '',
				'category' => esc_html__( 'FactoryHub', 'factoryhub' ),
				'params'   => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Number of Projects', 'factoryhub' ),
						'param_name'  => 'number',
						'value'       => 'All',
						'description' => esc_html__( 'Set numbers of Projects you want to display', 'factoryhub' ),
					),
					array(
						'type'        => 'colorpicker',
						'heading'     => esc_html__( 'Background Overlay', 'factoryhub' ),
						'param_name'  => 'overlay',
						'value'       => '',
						'description' => esc_html__( 'Select an overlay color for this element', 'factoryhub' ),
					),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Columns', 'factoryhub' ),
						'param_name'  => 'columns',
						'value'       => array(
							esc_html__( '4 Columns', 'factoryhub' ) => '4',
							esc_html__( '3 Columns', 'factoryhub' ) => '3',
						),
						'description' => esc_html__( 'Display services in how many columns', 'factoryhub' ),
					),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Category', 'factoryhub' ),
						'param_name'  => 'category',
						'value'       => $this->get_categories( 'service_category' ),
						'description' => esc_html__( 'Select a category or all categories.', 'factoryhub' ),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Type', 'factoryhub' ),
						'param_name' => 'type',
						'value'      => array(
							esc_html__( 'Carousel', 'factoryhub' ) => 'carousel',
							esc_html__( 'Grid', 'factoryhub' )     => 'grid',
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Slider autoplay', 'factoryhub' ),
						'param_name'  => 'autoplay',
						'value'       => '5000',
						'description' => esc_html__( 'Duration of animation between slides (in ms). Enter the value is 0 or empty if you want the slider is not autoplay', 'factoryhub' ),
						'dependency'  => array(
							'element' => 'type',
							'value'   => array( 'carousel' ),
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'factoryhub' ),
						'param_name'  => 'el_class',
						'value'       => '',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'factoryhub' ),
					),
				),
			)
		);

		// FactoryHub Service 2
		vc_map(
			array(
				'name'     => esc_html__( 'FactoryHub Service List', 'factoryhub' ),
				'base'     => 'fh_service_list',
				'class'    => '',
				'category' => esc_html__( 'FactoryHub', 'factoryhub' ),
				'params'   => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Number of Projects', 'factoryhub' ),
						'param_name'  => 'number',
						'value'       => '6',
						'description' => esc_html__( 'Set numbers of Projects you want to display', 'factoryhub' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'factoryhub' ),
						'param_name'  => 'el_class',
						'value'       => '',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'factoryhub' ),
					),
				),
			)
		);


		// FactoryHub Latest Post
		vc_map(
			array(
				'name'     => esc_html__( 'FactoryHub Latest Post', 'factoryhub' ),
				'base'     => 'fh_latest_post',
				'class'    => '',
				'category' => esc_html__( 'FactoryHub', 'factoryhub' ),
				'params'   => array(
					array(
						'type'        => 'textfield',

						'heading'     => esc_html__( 'Number of Projects', 'factoryhub' ),
						'param_name'  => 'number',
						'value'       => 'All',
						'description' => esc_html__( 'Set numbers of Projects you want to display', 'factoryhub' ),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Columns', 'factoryhub' ),
						'param_name' => 'columns',
						'value'      => array(
							esc_html__( '4 columns', 'factoryhub' ) => '4',
							esc_html__( '3 columns', 'factoryhub' ) => '3',
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Slider autoplay', 'factoryhub' ),
						'param_name'  => 'autoplay',
						'value'       => '5000',
						'description' => esc_html__( 'Duration of animation between slides (in ms). Enter the value is 0 or empty if you want the slider is not autoplay', 'factoryhub' ),
					),
					array(
						'type'        => 'textfield',

						'heading'     => esc_html__( 'Extra class name', 'factoryhub' ),
						'param_name'  => 'el_class',
						'value'       => '',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'factoryhub' ),
					),
				),
			)
		);

		// Add Icon Box shortcode
		vc_map(
			array(
				'name'              => esc_html__( 'FactoryHub Icon Box', 'factoryhub' ),
				'base'              => 'fh_icon_box',
				'class'             => '',
				'category'          => esc_html__( 'FactoryHub', 'factoryhub' ),
				'admin_enqueue_css' => FACTORYHUB_ADDONS_URL . '/assets/css/vc/icon-field.css',
				'params'            => array(
					array(
						'type'       => 'icon',
						'heading'    => esc_html__( 'Icon', 'factoryhub' ),
						'param_name' => 'icon',
						'value'      => '',
					),
					array(
						'type'        => 'dropdown',

						'heading'     => esc_html__( 'Icon Box Style', 'factoryhub' ),
						'param_name'  => 'style',
						'default'     => 'icon-box',
						'value'       => array(
							esc_html__( 'Style 1', 'factoryhub' ) => 'icon-box',
							esc_html__( 'Style 2', 'factoryhub' ) => 'icon-box-2',
							esc_html__( 'Style 3', 'factoryhub' ) => 'icon-box-3',
							esc_html__( 'Style 4', 'factoryhub' ) => 'icon-box-4',
							esc_html__( 'Style 5', 'factoryhub' ) => 'icon-box-5',
							esc_html__( 'Style 6', 'factoryhub' ) => 'icon-box-6',
						),
						'description' => __( 'Choose 1 style you want to display', 'factoryhub' ),
					),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Icon Position', 'factoryhub' ),
						'param_name'  => 'icon_position',
						'value'       => array(
							esc_html__( 'Center', 'factoryhub' ) => 'center',
							esc_html__( 'Top', 'factoryhub' )    => 'top',
						),
						'description' => esc_html__( 'Choose Icon Position', 'factoryhub' ),
						'dependency'  => array(
							'element' => 'style',
							'value'   => array( 'icon-box' ),
						),
					),
					array(
						'heading'    => esc_html__( 'Button Link', 'factoryhub' ),
						'param_name' => 'button_link',
						'type'       => 'vc_link',
						'value'      => esc_html__( 'Read More', 'factoryhub' ),
						'dependency' => array(
							'element' => 'style',
							'value'   => array( 'icon-box-4' ),
						),
					),
					array(
						'type'        => 'colorpicker',
						'heading'     => esc_html__( 'Background Overlay', 'factoryhub' ),
						'param_name'  => 'overlay',
						'value'       => '',
						'description' => esc_html__( 'Select an overlay color for this element', 'factoryhub' ),
						'dependency'  => array(
							'element' => 'style',
							'value'   => array( 'icon-box-3' ),
						),
					),
					array(
						'heading'     => esc_html__( 'Background Images', 'factoryhub' ),
						'param_name'  => 'image',
						'type'        => 'attach_image',
						'value'       => '',
						'description' => esc_html__( 'Select images from media library', 'factoryhub' ),
						'dependency'  => array(
							'element' => 'style',
							'value'   => array( 'icon-box-3' ),
						),
					),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Version', 'factoryhub' ),
						'param_name'  => 'version',
						'value'       => array(
							esc_html__( 'Dark Version', 'factoryhub' )  => 'dark',
							esc_html__( 'Light Version', 'factoryhub' ) => 'light',
						),
						'description' => esc_html__( 'Choose Version For Icon Box', 'factoryhub' ),
						'dependency'  => array(
							'element' => 'style',
							'value'   => array( 'icon-box-2' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Title', 'factoryhub' ),
						'param_name' => 'title',
						'value'      => '',
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Sub Title', 'factoryhub' ),
						'param_name' => 'sub_title',
						'value'      => '',
						'dependency' => array(
							'element' => 'style',
							'value'   => array( 'icon-box-2' ),
						),
					),
					array(
						'type'        => 'textarea_html',
						'heading'     => esc_html__( 'Content', 'factoryhub' ),
						'param_name'  => 'content',
						'value'       => '',
						'description' => __( 'Enter the content of this box', 'factoryhub' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'factoryhub' ),
						'param_name'  => 'el_class',
						'value'       => '',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'factoryhub' ),
					)
				),
			)
		);


		// Add Counter shortcode
		vc_map(
			array(
				'name'     => esc_html__( 'FactoryHub Counter', 'factoryhub' ),
				'base'     => 'fh_counter',
				'class'    => '',
				'category' => esc_html__( 'FactoryHub', 'factoryhub' ),
				'params'   => array(
					array(
						'type'       => 'icon',
						'heading'    => esc_html__( 'Icon', 'factoryhub' ),
						'param_name' => 'icon',
						'value'      => '',
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Counter Value', 'factoryhub' ),
						'param_name'  => 'value',
						'value'       => '',
						'description' => esc_html__( 'Input integer value for counting', 'factoryhub' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Unit', 'factoryhub' ),
						'param_name'  => 'unit',
						'value'       => '',
						'description' => esc_html__( 'Enter the Unit. Example: +, % .etc', 'factoryhub' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Title', 'factoryhub' ),
						'param_name'  => 'title',
						'value'       => '',
						'description' => esc_html__( 'Enter the title of this box', 'factoryhub' ),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Style', 'factoryhub' ),
						'param_name' => 'style',
						'value'      => array(
							esc_html__( 'Style 1', 'factoryhub' ) => 'style-1',
							esc_html__( 'Style 2', 'factoryhub' ) => 'style-2',
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'factoryhub' ),
						'param_name'  => 'el_class',
						'value'       => '',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'factoryhub' ),
					)
				),
			)
		);

		// Add Testimonial
		vc_map(
			array(
				'name'     => esc_html__( 'FactoryHub Testimonial', 'factoryhub' ),
				'base'     => 'fh_testimonials',
				'class'    => '',
				'category' => esc_html__( 'FactoryHub', 'factoryhub' ),
				'params'   => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Number of Testimonial', 'factoryhub' ),
						'param_name'  => 'number',
						'value'       => 'All',
						'description' => esc_html__( 'Set numbers of Testimonial you want to display', 'factoryhub' ),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Type', 'factoryhub' ),
						'param_name' => 'type',
						'value'      => array(
							esc_html__( 'Carousel', 'factoryhub' ) => 'carousel',
							esc_html__( 'Grid', 'factoryhub' )     => 'grid',
						),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Style', 'factoryhub' ),
						'param_name' => 'style',
						'value'      => array(
							esc_html__( 'Style 1 ( Light )', 'factoryhub' ) => '1',
							esc_html__( 'Style 2 ( Dark )', 'factoryhub' )  => '2',
						),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Columns', 'factoryhub' ),
						'param_name' => 'columns',
						'value'      => array(
							esc_html__( '3 columns', 'factoryhub' ) => '3',
							esc_html__( '2 columns', 'factoryhub' ) => '2',
							esc_html__( '4 columns', 'factoryhub' ) => '4',
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Slider autoplay', 'factoryhub' ),
						'param_name'  => 'autoplay',
						'value'       => '5000',
						'description' => esc_html__( 'Duration of animation between slides (in ms). Enter the value is 0 or empty if you want the slider is not autoplay', 'factoryhub' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'factoryhub' ),
						'param_name'  => 'el_class',
						'value'       => '',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'factoryhub' ),
					),
				),
			)
		);


		// Add Testimonial 2
		vc_map(
			array(
				'name'     => esc_html__( 'FactoryHub Testimonial Carousel 2', 'factoryhub' ),
				'base'     => 'fh_testimonials_2',
				'class'    => '',
				'category' => esc_html__( 'FactoryHub', 'factoryhub' ),
				'params'   => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Number of Testimonial', 'factoryhub' ),
						'param_name'  => 'number',
						'value'       => 'All',
						'description' => esc_html__( 'Set numbers of Testimonial you want to display', 'factoryhub' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Slider autoplay', 'factoryhub' ),
						'param_name'  => 'autoplay',
						'value'       => '5000',
						'description' => esc_html__( 'Duration of animation between slides (in ms). Enter the value is 0 or empty if you want the slider is not autoplay', 'factoryhub' ),
					),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Testimonial Style', 'factoryhub' ),
						'param_name'  => 'style',
						'value'       => array(
							esc_html__( 'Style 1 ( Light )', 'factoryhub' ) => 'style-1',
							esc_html__( 'Style 2 ( Dark )', 'factoryhub' )  => 'style-2',
						),
						'description' => esc_html__( 'Choose Testimonial Style', 'factoryhub' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'factoryhub' ),
						'param_name'  => 'el_class',
						'value'       => '',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'factoryhub' ),
					),
				),
			)
		);

		// Add Testimonial 3
		vc_map(
			array(
				'name'     => esc_html__( 'FactoryHub Testimonial Carousel 3', 'factoryhub' ),
				'base'     => 'fh_testimonials_3',
				'class'    => '',
				'category' => esc_html__( 'FactoryHub', 'factoryhub' ),
				'params'   => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Number of Testimonial', 'factoryhub' ),
						'param_name'  => 'number',
						'value'       => 'All',
						'description' => esc_html__( 'Set numbers of Testimonial you want to display', 'factoryhub' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Slider autoplay', 'factoryhub' ),
						'param_name'  => 'autoplay',
						'value'       => '5000',
						'description' => esc_html__( 'Duration of animation between slides (in ms). Enter the value is 0 or empty if you want the slider is not autoplay', 'factoryhub' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'factoryhub' ),
						'param_name'  => 'el_class',
						'value'       => '',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'factoryhub' ),
					),
				),
			)
		);

		// Pricing Table
		vc_map(
			array(
				'name'     => esc_html__( 'FactoryHub Pricing Table', 'factoryhub' ),
				'base'     => 'fh_pricing_table',
				'category' => esc_html__( 'FactoryHub', 'factoryhub' ),
				'params'   => array(
					array(
						'heading'     => esc_html__( 'Plan Name', 'factoryhub' ),
						'admin_label' => true,
						'param_name'  => 'name',
						'type'        => 'textfield',
					),
					array(
						'heading'     => esc_html__( 'Price', 'factoryhub' ),
						'description' => esc_html__( 'Plan pricing', 'factoryhub' ),
						'param_name'  => 'price',
						'type'        => 'textfield',
					),
					array(
						'heading'     => esc_html__( 'Currency', 'factoryhub' ),
						'description' => esc_html__( 'Price currency', 'factoryhub' ),
						'param_name'  => 'currency',
						'type'        => 'textfield',
						'value'       => '$',
					),
					array(
						'heading'     => esc_html__( 'Unit', 'factoryhub' ),
						'description' => esc_html__( 'Unit', 'factoryhub' ),
						'param_name'  => 'unit',
						'type'        => 'textfield',
						'value'       => esc_html__( '/ Month', 'factoryhub' ),
					),
					array(
						'heading'     => esc_html__( 'Description', 'factoryhub' ),
						'description' => esc_html__( 'Description', 'factoryhub' ),
						'param_name'  => 'desc',
						'type'        => 'textarea',
						'value'       => '',
					),
					array(
						'heading'     => esc_html__( 'Features', 'factoryhub' ),
						'description' => esc_html__( 'Feature list of this plan. Click to arrow button to edit.', 'factoryhub' ),
						'param_name'  => 'features',
						'type'        => 'param_group',
						'params'      => array(
							array(
								'heading'    => esc_html__( 'Feature name', 'factoryhub' ),
								'param_name' => 'name',
								'type'       => 'textfield',
							),
						),
					),
					array(
						'heading'    => esc_html__( 'Button Text', 'factoryhub' ),
						'param_name' => 'button_text',
						'type'       => 'textfield',
						'value'      => esc_html__( 'Join Now', 'factoryhub' ),
					),
					array(
						'heading'    => esc_html__( 'Button Link', 'factoryhub' ),
						'param_name' => 'button_link',
						'type'       => 'vc_link',
						'value'      => esc_html__( 'Join Now', 'factoryhub' ),
					),
					array(
						'type'       => 'checkbox',
						'heading'    => esc_html__( 'Promoted', 'factoryhub' ),
						'param_name' => 'promoted',
						'value'      => array( esc_html__( 'Yes', 'factoryhub' ) => 'yes' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'factoryhub' ),
						'param_name'  => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'factoryhub' ),
					),
				),
			)
		);


		// Partners
		vc_map(
			array(
				'name'     => esc_html__( 'FactoryHub Partners', 'factoryhub' ),
				'base'     => 'fh_partner',
				'class'    => '',
				'category' => esc_html__( 'FactoryHub', 'factoryhub' ),
				'params'   => array(
					array(
						'type'        => 'attach_images',
						'heading'     => esc_html__( 'Images', 'factoryhub' ),
						'param_name'  => 'images',
						'value'       => '',
						'description' => esc_html__( 'Select images from media library', 'factoryhub' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Image size', 'factoryhub' ),
						'param_name'  => 'image_size',
						'description' => esc_html__( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'factoryhub' ),
					),
					array(
						'type'        => 'textarea',
						'heading'     => esc_html__( 'Custom links', 'factoryhub' ),
						'param_name'  => 'custom_links',
						'description' => esc_html__( 'Enter links for each slide here. Divide links with linebreaks (Enter).', 'factoryhub' ),
					),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Custom link target', 'factoryhub' ),
						'param_name'  => 'custom_links_target',
						'value'       => array(
							esc_html__( 'Same window', 'factoryhub' ) => '_self',
							esc_html__( 'New window', 'factoryhub' )  => '_blank',
						),
						'description' => esc_html__( 'Select where to open custom links.', 'factoryhub' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'factoryhub' ),
						'param_name'  => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'factoryhub' ),
					),
				),
			)
		);

		//Factoryhub Team
		vc_map(
			array(
				'name'     => esc_html__( 'Factoryhub Team', 'factoryhub' ),
				'base'     => 'fh_team',
				'category' => esc_html__( 'FactoryHub', 'factoryhub' ),
				'params'   => array(
					array(
						'heading'     => esc_html__( 'Images', 'factoryhub' ),
						'param_name'  => 'image',
						'type'        => 'attach_image',
						'value'       => '',
						'description' => esc_html__( 'Select images from media library', 'factoryhub' ),
					),
					array(
						'heading'     => esc_html__( 'Image size', 'factoryhub' ),
						'param_name'  => 'image_size',
						'type'        => 'textfield',
						'description' => esc_html__( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'factoryhub' ),
					),
					array(
						'type'        => 'colorpicker',
						'heading'     => esc_html__( 'Background Overlay', 'factoryhub' ),
						'param_name'  => 'overlay',
						'value'       => '',
						'description' => esc_html__( 'Select an overlay color for this element', 'factoryhub' ),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Style', 'factoryhub' ),
						'param_name' => 'style',
						'value'      => array(
							esc_html__( 'Style 1 ( Default )', 'factoryhub' )     => '1',
							esc_html__( 'Style 2 ( Text Center )', 'factoryhub' ) => '2',
						),
					),
					array(
						'heading'    => esc_html__( 'Name', 'factoryhub' ),
						'param_name' => 'name',
						'type'       => 'textfield',
					),
					array(
						'heading'    => esc_html__( 'Job', 'factoryhub' ),
						'param_name' => 'job',
						'type'       => 'textfield',
					),
					array(
						'heading'    => esc_html__( 'Desc', 'factoryhub' ),
						'param_name' => 'desc',
						'type'       => 'textarea',
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Facebook', 'factoryhub' ),
						'param_name' => 'facebook',
						'value'      => '',
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Twitter', 'factoryhub' ),
						'param_name' => 'twitter',
						'value'      => '',
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Google Hub', 'factoryhub' ),
						'param_name' => 'google',
						'value'      => '',
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'RSS', 'factoryhub' ),
						'param_name' => 'rss',
						'value'      => '',
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Pinterest', 'factoryhub' ),
						'param_name' => 'pinterest',
						'value'      => '',
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Linkedin', 'factoryhub' ),
						'param_name' => 'linkedin',
						'value'      => '',
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Youtube', 'factoryhub' ),
						'param_name' => 'youtube',
						'value'      => '',
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Instagram', 'factoryhub' ),
						'param_name' => 'instagram',
						'value'      => '',
					),
					array(
						'heading'     => esc_html__( 'Extra class name', 'factoryhub' ),
						'param_name'  => 'el_class',
						'type'        => 'textfield',
						'value'       => '',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'factoryhub' ),
					),
				)
			)
		);

		// GG maps

		vc_map(
			array(
				'name'     => esc_html__( 'Google Map', 'factoryhub' ),
				'base'     => 'fh_gmap',
				'category' => esc_html__( 'FactoryHub', 'factoryhub' ),
				'params'   => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Api Key', 'factoryhub' ),
						'param_name'  => 'api_key',
						'value'       => '',
						'description' => sprintf( __( 'Please go to <a href="%s">Google Maps APIs</a> to get a key', 'factoryhub' ), esc_url( 'https://developers.google.com/maps/documentation/javascript/get-api-key#get-an-api-key' ) ),
					),
					array(
						'heading'     => esc_html__( 'Style', 'factoryhub' ),
						'param_name'  => 'style',
						'type'        => 'dropdown',
						'value'       => array(
							esc_html__( 'Style 1', 'factoryhub' ) => 1,
							esc_html__( 'Style 2', 'factoryhub' ) => 2,
						),
						'description' => esc_html__( 'Select Style Of Google Map', 'factoryhub' ),
					),
					array(
						'type'        => 'attach_image',
						'heading'     => esc_html__( 'Marker', 'factoryhub' ),
						'param_name'  => 'marker',
						'value'       => '',
						'description' => esc_html__( 'Select an image from media library', 'factoryhub' ),
					),
					array(
						'type'       => 'param_group',
						'heading'    => esc_html__( 'Address Infomation', 'factoryhub' ),
						'value'      => '',
						'param_name' => 'info',
						'params'     => array(
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__( 'Lat', 'factoryhub' ),
								'param_name' => 'lat',
								'value'      => '',
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__( 'Lng', 'factoryhub' ),
								'param_name' => 'lng',
								'value'      => '',
							),
							array(
								'type'       => 'textarea',
								'heading'    => esc_html__( 'Address Details', 'factoryhub' ),
								'param_name' => 'details',
							),
						)
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Width(px)', 'factoryhub' ),
						'param_name' => 'width',
						'value'      => '',
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Height(px)', 'factoryhub' ),
						'param_name' => 'height',
						'value'      => '500',
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Zoom', 'factoryhub' ),
						'param_name' => 'zoom',
						'value'      => '13',
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'factoryhub' ),
						'param_name'  => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file . ', 'factoryhub' ),
					),
				)
			)
		);

	}


	/**
	 * Return setting UI for icon param type
	 *
	 * @param  array  $settings
	 * @param  string $value
	 *
	 * @return string
	 */
	function icon_param( $settings, $value ) {
		// Generate dependencies if there are any
		$icons = array();
		foreach ( $this->icons as $icon ) {
			$icons[] = sprintf(
				'<i data-icon="%1$s" class="%1$s %2$s"></i>',
				$icon,
				$icon == $value ? 'selected' : ''
			);
		}

		return sprintf(
			'<div class="icon_block">
				<span class="icon-preview"><i class="%s"></i></span>
				<input type="text" class="icon-search" placeholder="%s">
				<input type="hidden" name="%s" value="%s" class="wpb_vc_param_value wpb-textinput %s %s_field">
				<div class="icon-selector">%s</div>
			</div>',
			esc_attr( $value ),
			esc_attr__( 'Quick Search', 'factoryhub' ),
			esc_attr( $settings['param_name'] ),
			esc_attr( $value ),
			esc_attr( $settings['param_name'] ),
			esc_attr( $settings['type'] ),
			implode( '', $icons )
		);
	}

	/**
	 * Get categories
	 *
	 * @return array|string
	 */
	function get_categories( $term = 'category' ) {
		$output[esc_html__( 'All', 'factoryhub' )] = '';
		$categories                                = get_terms( $term );
		if ( ! is_wp_error( $categories ) && $categories ) {

			if ( $categories ) {
				foreach ( $categories as $category ) {
					if ( $category ) {
						$output[$category->name] = $category->slug;
					}
				}
			}
		}

		return $output;
	}
}
