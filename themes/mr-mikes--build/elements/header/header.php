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

element( 'helmet' );

if ( get_the_ID() == 44555 ): ?>
    <!-- Holiday Corporate Gift Cards landing page -->
    <img height="1" width="1" style="display:none;" alt=""
         src="https://px.ads.linkedin.com/collect/?pid=2746876&conversionId=3158540&fmt=gif"/>
<?php endif; ?>

<section class="header">

    <div class="header__container">

        <div class="header__logo">
            <a href="<?php echo get_home_url(); ?>">
                <?php echo svg( 'logo' ); ?>
                <span class='screen-reader-text'>
                    <?php bloginfo( 'name' ); ?>
                </span>
            </a>
        </div>

        <a class="header__hamburger" href="#">
            <span></span>
            <span></span>
            <span></span>
        </a>

        <div class="header__menu">
            <div class="mobile-wrap">
                <div class="header__row1">
                    <?php bem_menu( 'utility', 'utility-menu' ); ?><?php echo do_shortcode('[mrmikes_location]'); ?>
                    <?php element( 'reservationButton' ); ?>
                </div>
                <div class="header__row2">
                    <?php bem_menu( 'primary', 'primary-menu' ); ?>
                    <?php element( 'reservationButton' ); ?>
                </div>
            </div>
        </div>

    </div>

    <div class="header__grunge-vector">
        <?php echo svg( 'grunge/1-header' ); ?>
    </div>
</section>

<div id="barba-wrapper">
    <div class="barba-container lazyload <?php if ( get_field( 'background_image_cb' ) ): echo 'barba-container__' . get_field( 'width' ); endif; ?>"
        <?php lazy_background( $bg_image ); ?>>