<?php
/**
 * Template part for displaying header style 4.
 *
 * @package FactoryHub
 */
$col = 'col-md-12';
if ( factoryhub_get_option( 'header_search' ) ) {
	$col = 'col-md-9';
}
?>
<div class="header-main clearfix">
	<div class="site-contact">
		<div class="container">
			<div class="row">
				<div class="site-logo col-md-3 col-sm-6 col-xs-6">
					<?php get_template_part( 'parts/logo' ); ?>
				</div>
				<div class="site-extra-text col-md-9 col-sm-6 col-xs-6">
					<?php factoryhub_header_extra_item() ?>
				</div>
			</div>
		</div>
	</div>
	<div class="site-menu">
		<div class="container">
			<div class="row">
				<div class="site-nav col-sm-12 col-xs-12 <?php echo esc_attr( $col ) ?>">
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
				<?php if ( factoryhub_get_option( 'header_search' )) :
					printf(
						'<div class="col-md-3 col-sm-12 col-xs-12 extra-item menu-item-search">
							<form method="get" class="search-form" action="%s">
								<i class="fa fa-search" aria-hidden="true"></i>
								<input type="search" class="search-field" placeholder="%s..." value="" name="s">
								<input type="submit" class="search-submit" value="Search">
							</form>
						</div>',
						esc_url( home_url( '/' ) ),
						esc_attr__( 'Search', 'factoryhub' )
					);
				endif ?>
			</div>
		</div>
	</div>
</div>
<?php factoryhub_menu_icon(); ?>
