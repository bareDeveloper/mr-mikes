<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$section_id = get_sub_field( 'section_id' );

?>
<div class="experiences"
	<?php if ( ! empty( $section_id ) ) : ?>
        id="<?php echo esc_attr( $section_id ); ?>"
	<?php endif; ?>
>

    <div class="experiences__header">

        <div class="experiences__titles">

            <h2 class="experiences__title">
				<?php the_sub_field( 'title_1' ); ?>
            </h2>

            <h2 class="experiences__title">
				<?php the_sub_field( 'title_2' ); ?>
            </h2>

        </div>

        <div class="grid-edges">
            <div class="experiences__text">
                <div class="experiences__text-container">
					<?php the_sub_field( 'text' ); ?>
                </div>
            </div>
        </div>

    </div>

    <div class="experiences__body">

        <div class="experiences__items">

			<?php $i = 0; ?>

			<?php if ( have_rows( 'keyfacts' ) ):
				while ( have_rows( 'keyfacts' ) ) : the_row(); ?>

                    <div class="experiences__item">

                        <div class="experiences__item-image lazyload" <?php echo lazy_background( get_sub_field( 'background_image' ) ); ?>>
                        </div>

                        <div class="experiences__item-content">

                            <div class="experiences__grunge">
								<?php
								if ( $i == 0 ):
									$i = 1;
								else:
									echo svg( "grunge/17-experience-top-right" );
									$i = 0;
								endif;
								?>
                            </div>


                            <div class="experiences__item-content-container">

								<?php
								echo '<p class="experiences__item-content-headline">' . get_sub_field( 'headline' ) . '</p>';
								the_sub_field( 'text' );
								?>
                            </div>

                        </div>

                    </div>

				<?php endwhile;
			endif; ?>

        </div>

    </div>

</div>
