<?php

$bgImg     = get_sub_field( 'background_image' );

if ( $bgImg ):
	$bgImgUrl = "background-image:url(" . $bgImg['url'] . ");";
	$hasBg    = "has-bg";
else :
	$bgImgUrl = "";
	$hasBg    = "";
endif;

$section_id = get_sub_field( 'section_id' );

?>

<div class="text burger-meter" style="<?php echo $bgImgUrl; ?>"
	<?php if ( ! empty( $section_id ) ) : ?>
        id="<?php echo esc_attr( $section_id ); ?>"
	<?php endif; ?>
>
    <div class="text__container text__container--meter">
        <div class="text__contents text__contents--meter">

			<?php if ( have_rows( 'figures_repeater' ) ):
				while ( have_rows( 'figures_repeater' ) ): the_row();
					$figuresNumber = get_sub_field( 'figures_number' );
					$figuresText   = get_sub_field( 'figures_text' );
					?>
                    <div class="meter-figure">
                        <div class="meter-figure__number">
							<?php echo $figuresNumber; ?>
                        </div>

                        <div class="meter-figure__text">
							<?php echo $figuresText; ?>
                        </div>
                    </div>
				<?php endwhile; ?>
			<?php endif; ?>

        </div>
    </div>
</div>

