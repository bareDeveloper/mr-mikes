<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$section_id = get_sub_field( 'section_id' );
?>
<div class="front"
	<?php if ( ! empty( $section_id ) ) : ?>
        id="<?php echo esc_attr( $section_id ); ?>"
	<?php endif; ?>
>

    <div class="front-2__slider">

		<?php if ( have_rows( 'slider' ) ) : ?>
			<?php $slide_no = 0; ?>
			<?php while ( have_rows( 'slider' ) ) : the_row(); ?>
				<?php $slide_no ++; ?>
                <div class="front-2__main-area front-2__main-area--<?php the_sub_field( 'style' ); ?>">

					<?php if ( get_sub_field( 'background_image_main' ) ): ?>

                        <div class="front-2__main-area-desktop front-2__main-area-desktop lazyload <?php if ( ! get_sub_field( 'background_image_mobile' ) ): echo 'front-2__main-area-desktop-and-mobile'; endif;
						if ( $slide_no == 1 ) {
							echo ' lazyload';
						}
						?>
           " <?php echo lazy_background( get_sub_field( 'background_image_main' ) ); ?>></div>

                        <div class="front-2__main-text">

							<?php if ( get_sub_field( 'image' ) ) : ?>
                                <div class="front-2__main-image">
                                    <div class="front-2__main-image-container">
                                        <img class="lazyload"<?php echo " " . mrm_get_lazy_loaded_image_attrs( get_sub_field( 'image' ) ); ?>/>
                                    </div>
                                </div>
							<?php endif; ?>

                            <div class="front-2__main-content">
								<?php element( 'headline', [
									'text'  => get_sub_field( 'headline_main_headline' ),
									'style' => get_sub_field( 'headline_main_headline_style' )
								] ); ?>

								<?php element( 'headline', [
									'text'  => get_sub_field( 'subheadline_headline' ),
									'style' => get_sub_field( 'subheadline_headline_style' )
								] ); ?>

                                <p><?php the_sub_field( 'text_main' ); ?></p>

								<?php element( 'button', [
									'class'  => 'btn-white',
									'button' => get_sub_field( 'button_main_button' ),
									'link'   => get_sub_field( 'button_main_link' )
								] ); ?>
                            </div>
                        </div>

					<?php endif; ?>

					<?php if ( get_sub_field( 'background_image_mobile' ) ): ?>

                        <div class="front-2__main-area-mobile <?php if ( ! get_sub_field( 'background_image_main' ) ): echo 'front-2__main-area-desktop-and-mobile'; endif; ?> lazyload" <?php echo lazy_background( get_sub_field( 'background_image_mobile' ) ); ?>>
                            <div class="front-2__main-text">

								<?php if ( get_sub_field( 'image' ) ) : ?>
                                    <div class="front-2__main-image">
                                        <div class="front-2__main-image-container">
                                            <img class="lazyload"<?php echo " " . mrm_get_lazy_loaded_image_attrs( get_sub_field( 'image' ) ); ?>/>
                                        </div>
                                    </div>
								<?php endif; ?>

                                <div class="front-2__main-content">
									<?php element( 'headline', [
										'text'  => get_sub_field( 'headline_main_headline' ),
										'style' => get_sub_field( 'headline_main_headline_style' )
									] ); ?>

									<?php element( 'headline', [
										'text'  => get_sub_field( 'subheadline_headline' ),
										'style' => get_sub_field( 'subheadline_headline_style' )
									] ); ?>

                                    <p><?php the_sub_field( 'text_main' ); ?></p>

									<?php element( 'button', [
										'class'  => 'btn-white',
										'button' => get_sub_field( 'button_main_button' ),
										'link'   => get_sub_field( 'button_main_link' )
									] ); ?>
                                </div>
                            </div>
                        </div>

					<?php endif; ?>

                </div>
			<?php endwhile; ?>
		<?php endif; ?>

    </div>

    <div class="front-2__feature front__feature">
        <div
                class="front-2__feature-bg-left"
                style='background-image: url(<?php echo wp_get_attachment_image_src( 1511, 'large-2' )[0]; ?>)'
        >

			<?php echo svg( "grunge/2-feature-bottom-left" ); ?>
        </div>
        <div
                class="front-2__feature-bg-right"
                style='background-image: url(<?php echo wp_get_attachment_image_src( 1512, 'large-2' )[0]; ?>)'
        >
			<?php echo svg( "grunge/8-feature-bottom-right" ); ?>
        </div>

        <div class="front-2__wrap-feature wrap-feature">
            <div class="wrap-feature__grunge">
				<?php echo svg( "grunge/5-centre-top" ); ?>
            </div>
            <div class="front-2__add-shadow">
                <div class="front-2__feature-tile">
                    <div class="lazyload front-2__feature-image" <?php echo lazy_background( get_sub_field( 'background_image_feature_1' ) ); ?>>
                        <div class="front-2__feature-content__grunge-left">
							<?php echo svg( "grunge/6-centre-right-inside" ); ?>
                        </div>
                    </div>

                    <div class="front-2__feature-content">

                        <div class="front-2__feature-content__grunge-right">
							<?php echo svg( "grunge/7-centre-outside-right" ); ?>
                        </div>
                        <div class="front-2__feature-content-inner">
                            <div class="front-2__feature-text-1">
								<?php element( 'headline', [
									'text'  => get_sub_field( 'headline_featured_1_headline' ),
									'style' => get_sub_field( 'headline_featured_1_headline_style' )
								] ); ?>

								<?php if ( get_sub_field( 'text_feature_1' ) ) : ?>
                                    <p><?php the_sub_field( 'text_feature_1' ); ?></p>
								<?php endif; ?>

								<?php element( 'button', [
									'class'  => 'btn-black',
									'button' => get_sub_field( 'button_featured_1_button' ),
									'link'   => get_sub_field( 'button_featured_1_link' )
								] ); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="front__bottom-contents">
        <div class="front__mrmikes">
            <div class="front__mrmikes-img mrmikes-img lazyload" <?php echo lazy_background( get_sub_field( 'background_image_mrmikes' ) ); ?>>
                <div class="mrmikes-img__grunge">
					<?php echo svg( "grunge/10-bottom-left" ); ?>
                </div>
                <div class="mrmikes-img__grunge">
					<?php echo svg( "grunge/11-bottom-centre" ); ?>
                </div>
            </div>

            <div class="front__wrap-mrmikes-text">
                <div class="front__mrmikes-text">
					<?php element( 'headline', [
						'text'  => get_sub_field( 'headline_mrmikes_headline' ),
						'style' => get_sub_field( 'headline_mrmikes_headline_style' )
					] ); ?>

                    <p><?php the_sub_field( 'text_mrmikes' ); ?></p>

					<?php element( 'button', [
						'class'  => 'btn-black',
						'button' => get_sub_field( 'button_mrmikes_button' ),
						'link'   => get_sub_field( 'button_mrmikes_link' )
					] ); ?>
                </div>
            </div>
        </div>
        <div class="front__steaks">
            <div class="front__steaks-img lazyload" <?php echo lazy_background( get_sub_field( 'background_image_steaks' ) ); ?>></div>
            <div
                    class="front__wrap-steaks-text"
                    style="background-image: url(<?php
					echo wp_get_attachment_image_src( 1514, 'large-2' )[0];
					?>)"
            >
                <div class="front__steaks-text">
					<?php element( 'headline', [
						'text'  => get_sub_field( 'headline_steaks_headline' ),
						'style' => get_sub_field( 'headline_steaks_headline_style' )
					] ); ?>

                    <p><?php the_sub_field( 'text_steaks' ); ?></p>

					<?php element( 'button', [
						'class'  => 'btn-white',
						'button' => get_sub_field( 'button_steaks_button' ),
						'link'   => get_sub_field( 'button_steaks_link' )
					] ); ?>
                </div>
            </div>
        </div>
    </div>

</div>
