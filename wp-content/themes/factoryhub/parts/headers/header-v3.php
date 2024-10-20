<?php
/**
 * Template part for displaying header transparent.
 *
 * @package FactoryHub
 */
?>

<div class="header-main">
	<div class="container">
		<div class="row">
			<div class="site-logo col-md-3 col-sm-6 col-xs-6">
				<?php get_template_part( 'parts/logo' ); ?>
			</div>
			<div class="site-menu col-md-9 col-sm-6 col-xs-6">
				<nav id="site-navigation" class="main-nav primary-nav nav">
					<?php
					wp_nav_menu( array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_id' => 'primary-menu',
					) );
					?>
				</nav>
			</div>
		</div>
	</div>
</div>
<?php factoryhub_menu_icon(); ?>
