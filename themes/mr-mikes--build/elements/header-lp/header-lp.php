<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$bg_image = '';

if ( get_field( 'background_image_cb' ) ):
	if ( get_field( 'background_image' ) ):
		$bg_image = get_field( 'background_image' );
	else :
		$bg_image = get_field( 'body_background_image', 'options' );
	endif;
endif;

$cta_button_link = get_field( "cta_button_link" );

element( 'helmet' );
?>
<header class="mrm-lp-header">

    <div class="mrm-lp-header-logos">
        <a href="<?php echo esc_url( get_home_url() ); ?>" class="mrm-lp-header-logos__primary">
			<?php echo svg( 'logo' ); ?>
            <span class='screen-reader-text'>
                <?php bloginfo( 'name' ); ?>
            </span>
        </a>

        <div class="mrm-lp-header-logos__secondary">
            <img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/static/icons/custom/el_ec_icon.svg' ); ?>"
                 alt="<?php echo esc_attr( get_the_title() ); ?>"
            >
        </div>
    </div>

    <div class="mrm-lp-header-right">
        <div class="mrm-lp-header-right-menu-wrapper">
			<?php bem_menu( 'lp_header', 'mrm-lp-header-right-menu' ); ?>
        </div>
		<?php if ( ! empty( $cta_button_link ) ) :

			$cta_button_link_url = $cta_button_link['url'];
			$cta_button_link_title = $cta_button_link['title'];
			$cta_button_link_target = $cta_button_link['target'] ?: '_self';

			?>
            <a href="<?php echo esc_url( $cta_button_link_url ); ?>"
               target="<?php echo esc_attr( $cta_button_link_target ); ?>"
               title="<?php echo esc_attr( $cta_button_link_title ); ?>"
               class="mrm-lp-header-right__cta-btn"
            >
				<?php echo esc_html( $cta_button_link_title ); ?>
            </a>
		<?php endif; ?>
    </div>

    <button class="mrm-lp-header__burger-btn hamburger hamburger--spin">
        <span class="hamburger-box">
            <span class="hamburger-inner"></span>
        </span>
    </button>
</header>

<div class="mrm-page-wrapper" id="barba-wrapper">
    <div class="barba-container lazyload <?php if ( get_field( 'background_image_cb' ) ): echo 'barba-container__' . get_field( 'width' ); endif; ?>"
		<?php lazy_background( $bg_image ); ?>>