<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$section_id = get_sub_field( 'section_id' );

$bgColour = get_sub_field( 'background_colour' );
$bgColour = "bg-" . $bgColour;
$vPad     = get_sub_field( 'vertical_padding' );
$vPad     = "vpad-" . $vPad;

if ( have_rows( 'buttons' ) ): ?>

    <div class="teaser-button <?php echo $bgColour; ?> <?php echo $vPad; ?>"
		<?php if ( ! empty( $section_id ) ) : ?>
            id="<?php echo esc_attr( $section_id ); ?>"
		<?php endif; ?>
    >
        <div class="teaser-button__content">
			<?php while ( have_rows( 'buttons' ) ): the_row();
				$headline = get_sub_field( 'headline' );
				$link     = get_sub_field( 'link' );
				$type     = get_sub_field( 'type' );
				?>
				<?php if ( $type == "cta_large" ): ?>

                    <div class="teaser-button__item teaser-button__item--cta-large">
                        <div class="button__container">
                            <a
                                    class="button"
                                    href="<?php echo $link['url']; ?>"
                                    target="<?php echo $link['target']; ?>" <?php if ( $link['url'] == '#' ) {
								echo 'data-featherlight="#balance" data-featherlight-variant="check_balance"';
							} ?>
                            >
                                <span><?php echo $link['title']; ?></span>
                            </a>
                        </div>
                    </div>

				<?php else: ?>

                    <div class="teaser-button__item">
						<?php if ( $headline ): ?>
                            <p class="teaser-button__title red"><?php echo $headline; ?></p>

						<?php else: ?>
                            <p class="teaser-button__title red">&nbsp;</p>

						<?php endif; ?>

                        <div class="button__container btn-red">
                            <a
                                    class="button"
                                    href="<?php echo $link['url']; ?>"
                                    target="<?php echo $link['target']; ?>" <?php if ( $link['url'] == '#' ) {
								echo 'data-featherlight="#balance" data-featherlight-variant="check_balance"';
							} ?>
                            >
								<?php echo $link['title']; ?>
                            </a>
                        </div>
                    </div>

				<?php endif; ?>

			<?php endwhile; ?>

        </div>
    </div>

<?php endif; ?>
