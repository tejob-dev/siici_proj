<?php
/**
 * Template part for displaying header with left menu ( default ).
 *
 * @package FactoryHub
 */
$col = 'col-md-12';
if ( factoryhub_get_option( 'extra_text' ) ) {
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
				<?php if ( factoryhub_get_option( 'extra_text' )) :
					printf(
						'<div class="col-md-3 col-sm-12 col-xs-12 extra-item menu-item-text">
							%s
						</div>',
						wp_kses( factoryhub_get_option( 'extra_text' ), wp_kses_allowed_html( 'post' ) )
					);
				endif ?>
			</div>
		</div>
	</div>
</div>
<?php factoryhub_menu_icon(); ?>
