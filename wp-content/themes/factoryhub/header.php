<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package FactoryHub
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="hfeed site">

	<?php do_action( 'factoryhub_before_header' ); ?>

	<header id="masthead" class="site-header clearfix">
		<?php do_action( 'factoryhub_header' ); ?>
	</header><!-- #masthead -->

	<?php do_action( 'factoryhub_after_header' ); ?>

	<div id="content" class="site-content">
		<?php
		$container = 'container';
		if ( is_post_type_archive( 'project' ) && factoryhub_get_option( 'project_layout' ) == 'full_width' ) {
			$container = 'factoryhub-container';
		}

		if ( is_page_template( 'template-homepage.php' ) || is_page_template( 'template-fullwidth.php' ) ) {
			$container = 'container-fluid';
		}
		?>

		<div class="<?php echo esc_attr( $container ) ?>">
			<div class="row">
