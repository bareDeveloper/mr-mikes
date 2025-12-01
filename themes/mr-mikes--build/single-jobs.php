<?php
element( 'header' );

element( 'banner', [
	'background_image' => get_field( 'jobs_background_image', 'options' ),
	'headline'         => get_the_title()
] );

// Pulling here the Related Apply Form
$related_apply_form = get_field( 'related_apply_form' );

?>

    <div class="single-jobs lazyload" <?php lazy_background( 1644 ); ?>>

        <div class="single-jobs__container">

			<?php

			the_field( 'text' );

			if ( ! empty( $related_apply_form ) ) {

				element( 'headline', [
					'text'  => 'Apply now',
					'style' => 'h2'
				] );

				// Printing the related Apply Gravity Form
				gravity_form(
					$related_apply_form,
					false,
					false
				);

			}

			?>

        </div>
    </div>

<?php element( 'footer' );