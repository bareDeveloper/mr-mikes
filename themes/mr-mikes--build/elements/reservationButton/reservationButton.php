<?php

// Get the reservation button link from the primary menu (the menu ID is hardcoded because it is the menu selected in ACF Field Group settings)
$relatedNavMenuObject = wp_get_nav_menu_object( 2 );

if ( ! empty( $relatedNavMenuObject ) ) {
	$reservationButtonLink = get_field( 'reservation_button_link', $relatedNavMenuObject );
}

if ( ! empty( $reservationButtonLink ) ) : ?>
    <div class="reservationButton button__container btn-red">
        <a id="reservations-button"
           class="reservationButton__container button"
           href="<?php echo esc_url( $reservationButtonLink['url'] ); ?>"
           title="<?php echo esc_attr( $reservationButtonLink['title'] ); ?>"
        >
			<?php echo esc_html( $reservationButtonLink['title'] ); ?>
        </a>
    </div>
<?php endif; ?>