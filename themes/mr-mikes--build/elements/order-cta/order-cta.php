<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$section_id = get_sub_field( 'section_id' );
?>
<div class="order-cta"
	<?php if ( ! empty( $section_id ) ) : ?>
        id="<?php echo esc_attr( $section_id ); ?>"
	<?php endif; ?>
>
    <div class="order-cta__container">
        <div class="order-cta__headline">
            <p><?php the_sub_field( 'headline' ); ?></p>
        </div>

        <div class="order-cta__buttons">
            <div class="button__container btn-black list-filter-button" rel="restaurantWidget--pickup"
                 data-featherlight=".restaurantWidget--pickup"
                 data-featherlight-variant="restaurantWidget--pickup__featherlight">
                <a class="button">Pickup</a>
            </div>

            <div class="button__container btn-black list-filter-button" rel="restaurantWidget--skip"
                 data-featherlight=".restaurantWidget--skip"
                 data-featherlight-variant="restaurantWidget--skip__featherlight">
                <a class="button">Delivery</a>
            </div>

        </div>

        <div class="order-cta__text">
			<?php the_sub_field( 'text' ); ?>
        </div>
    </div>
</div>  