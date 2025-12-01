<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$section_id = get_sub_field( 'section_id' );

$accordion_id = substr( str_shuffle( str_repeat( $x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil( 5 / strlen( $x ) ) ) ), 1, 5 );
$tab_id       = 0;
?>
<div class="tabs-and-accordion"
	<?php if ( ! empty( $section_id ) ) : ?>
        id="<?php echo esc_attr( $section_id ); ?>"
	<?php endif; ?>
>
	<?php if ( have_rows( 'tabs' ) ) : ?>
        <div class="tabs-and-accordion__tabs">
			<?php while ( have_rows( 'tabs' ) ) : the_row(); ?>
                <div class="tabs-and-accordion__tab tab tab-<?php echo $accordion_id; ?> <?php if ( $tab_id == 0 ) {
					echo "active";
				} ?>" data-acc="<?php echo $accordion_id; ?>" data-tab="<?php echo $tab_id; ?>">
                    <p><?php the_sub_field( 'title' ); ?></p>
                </div>
				<?php $tab_id ++; ?>
			<?php endwhile; ?>
        </div>
	<?php endif; ?>

    <p class="tabs-accordion_text"><?php _e( 'Please let your server know of ANY food allergies.', 'mrmikes' ); ?></p>

	<?php
	$tab_id = 0;
	?>

	<?php if ( have_rows( 'tabs' ) ) : ?>
        <div class="tabs-and-accordion__panels">
			<?php while ( have_rows( 'tabs' ) ) : the_row(); ?>
                <div id="<?php echo $accordion_id; ?><?php echo $tab_id; ?>"
                     class="tabs-and-accordion__tab-panel tab-<?php echo $accordion_id; ?> <?php if ( $tab_id == 0 ) {
					     echo "active";
				     } ?>">
					<?php if ( have_rows( 'accordions' ) ) : ?>
                        <div class="tabs-and-accordion__accordion">
							<?php while ( have_rows( 'accordions' ) ) : the_row(); ?>
                                <div class="tabs-and-accordion__accordion-title accordion">
                                    <h3><?php the_sub_field( 'title' ); ?>
                                        <div class="arrow"></div>
                                    </h3>

                                </div>

                                <div class="tabs-and-accordion__accordion-panel">
                                    <img class="lazyload"<?php echo " " . mrm_get_lazy_loaded_image_attrs( get_sub_field( 'image' ) ); ?>>
                                </div>

							<?php endwhile; ?>
                        </div>
					<?php endif; ?>
                </div>
				<?php $tab_id ++; ?>
			<?php endwhile; ?>
        </div>
	<?php endif; ?>
</div>