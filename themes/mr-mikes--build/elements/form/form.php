<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$section_id      = get_sub_field( 'section_id' );
$related_gf_form = get_sub_field( 'related_gf_form' );

if ( ! empty( $related_gf_form ) ) : ?>
    <div class="form"
		<?php if ( ! empty( $section_id ) ) : ?>
            id="<?php echo esc_attr( $section_id ); ?>"
		<?php endif; ?>
    >
        <div class="form__container">
			<?php

			// Printing the related Gravity Form (using value from the field)
			gravity_form(
				$related_gf_form,
				false,
				false,
				false,
				null,
                true
			);

			?>
        </div>
    </div>
<?php endif; ?>