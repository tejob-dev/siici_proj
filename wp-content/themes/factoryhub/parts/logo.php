<?php
/**
 * Hooks for template logo
 *
 * @package Solo
 */

$logo_dark  = factoryhub_get_option( 'logo_dark' );
$logo_light = factoryhub_get_option( 'logo_light' );

if ( ! $logo_dark && ! $logo_light ) {
	$logo_dark  = $logo_dark ? $logo_dark : get_template_directory_uri() . '/img/logo.png';
	$logo_light = $logo_light ? $logo_light : get_template_directory_uri() . '/img/logo-light.png';
} elseif ( ! $logo_light && $logo_dark ) {
	$logo_light = $logo_dark;
} elseif ( ! $logo_dark && $logo_light ) {
	$logo_dark = $logo_light;
}

$class_light = '';
$class_dark = '';

if ( factoryhub_get_option( 'header_layout' )== 'v3' ) {
	$class_light = 'show-logo';
	$class_dark = 'hide-logo';
} else {
	$class_light = 'hide-logo';
	$class_dark = 'show-logo';
}
?>
	<a href="<?php echo esc_url( home_url() ) ?>" class="logo">
		<img src="<?php echo esc_url( $logo_light ); ?>" alt="<?php echo get_bloginfo( 'name' ); ?>" class="logo-light <?php echo esc_attr($class_light) ?>">
		<img src="<?php echo esc_url( $logo_dark ); ?>" alt="<?php echo get_bloginfo( 'name' ); ?>" class="logo-dark <?php echo esc_attr($class_dark) ?>">
	</a>
<?php

printf(
	'<%1$s class="site-title"><a href="%2$s" rel="home">%3$s</a></%1$s>',
	is_home() || is_front_page() ? 'h1' : 'p',
	esc_url( home_url( '' ) ),
	get_bloginfo( 'name' )
);
?>
<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
