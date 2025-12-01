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

    <div class="front__slider">

		<?php if ( have_rows( 'slider' ) ) : ?>
			<?php $slide_no = 0; ?>
			<?php while ( have_rows( 'slider' ) ) : the_row(); ?>
				<?php $slide_no ++; ?>
                <div class="front__main-area front__main-area--<?php the_sub_field( 'style' ); ?>">

					<?php if ( get_sub_field( 'background_image_main' ) ): ?>

                        <div class="front__main-area-desktop <?php if ( ! get_sub_field( 'background_image_mobile' ) ): echo 'front__main-area-desktop-and-mobile'; endif;
						if ( $slide_no == 1 ) {
							echo ' lazyload';
						}
						?>
           " <?php echo lazy_background( get_sub_field( 'background_image_main' ) ); ?>></div>

                        <div class="front__main-text">

							<?php if ( get_sub_field( 'image' ) ) : ?>
                                <div class="front__main-image">
                                    <div class="front__main-image-container">
                                        <img class="lazyload"<?php echo " " . mrm_get_lazy_loaded_image_attrs( get_sub_field( 'image' ) ); ?>/>
                                    </div>
                                </div>
							<?php endif; ?>

                            <div class="front__main-content">
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

                        <div class="front__main-area-mobile <?php if ( ! get_sub_field( 'background_image_main' ) ): echo 'front__main-area-desktop-and-mobile'; endif; ?> lazyload" <?php echo lazy_background( get_sub_field( 'background_image_mobile' ) ); ?>>
                            <div class="front__main-text">

								<?php if ( get_sub_field( 'image' ) ) : ?>
                                    <div class="front__main-image">
                                        <div class="front__main-image-container">
                                            <img class="lazyload"<?php echo " " . mrm_get_lazy_loaded_image_attrs( get_sub_field( 'image' ) ); ?>/>
                                        </div>
                                    </div>
								<?php endif; ?>

                                <div class="front__main-content">
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

    <div class="front__feature">
        <div
                class="front__feature-bg-left"
                style='background-image: url(<?php echo wp_get_attachment_image_src( 1511, 'large-2' )[0]; ?>)'
        >

			<?php echo svg( "grunge/2-feature-bottom-left" ); ?>
        </div>
        <div
                class="front__feature-bg-right"
                style='background-image: url(<?php echo wp_get_attachment_image_src( 1512, 'large-2' )[0]; ?>)'
        >
			<?php echo svg( "grunge/8-feature-bottom-right" ); ?>
        </div>

        <div class="front__wrap-feature wrap-feature">
            <div class="wrap-feature__grunge">
				<?php echo svg( "grunge/5-centre-top" ); ?>
            </div>
            <div class="front__add-shadow">
                <div class="front__feature-left">
                    <div
                            class="lazyload front__feature-tile feature-tile feature-tile--left-top"
						<?php echo lazy_background( get_sub_field( 'background_image_feature_1' ) ); ?>
                    >
                        <div class="feature-tile__grunge">
							<?php echo svg( "grunge/4-centre-left-centre" ); ?>
                        </div>
                        <div class="front__feature-text-1">
							<?php element( 'headline', [
								'text'  => get_sub_field( 'headline_featured_1_headline' ),
								'style' => get_sub_field( 'headline_featured_1_headline_style' )
							] ); ?>

							<?php if ( get_sub_field( 'text_feature_1' ) ) : ?>
                                <p><?php the_sub_field( 'text_feature_1' ); ?></p>
							<?php endif; ?>

							<?php element( 'button', [
								'class'  => 'btn-white',
								'button' => get_sub_field( 'button_featured_1_button' ),
								'link'   => get_sub_field( 'button_featured_1_link' )
							] ); ?>
                        </div>
                        <div class="front__feature-tile-grunge-svg">
							<?php echo svg( "grunge/3-centre-left" ); ?>
                        </div>
                    </div>
                    <div class="lazyload front-feature__feature-tile feature-tile feature-tile--left-bottom" <?php echo lazy_background( get_sub_field( 'background_image_feature_2' ) ); ?>>
                        <div class="feature-tile__grunge">
							<?php echo svg( "grunge/9-centre-bottom" ); ?>
                        </div>

                        <div class="front__feature-text-2">

							<?php element( 'headline', [
								'text'  => get_sub_field( 'headline_featured_2_headline' ),
								'style' => get_sub_field( 'headline_featured_2_headline_style' )
							] ); ?>

                            <p><?php the_sub_field( 'text_feature_2' ); ?></p>

							<?php element( 'button', [
								'class'  => 'btn-white',
								'button' => get_sub_field( 'button_featured_2_button' ),
								'link'   => get_sub_field( 'button_featured_2_link' )
							] ); ?>

                        </div>
                    </div>
                </div>
                <div class="front__feature-right">
                    <div class="front__feature-img lazyload feature-image" <?php echo lazy_background( get_sub_field( 'background_image_feature_3' ) ); ?>>
                        <div class="feature-image__grunge">
							<?php echo svg( "grunge/6-centre-right-inside" ); ?>
                        </div>
                    </div>
                    <div class="front__feature-text-3 feature-text">
                        <div class="feature-text__grunge">
							<?php echo svg( "grunge/7-centre-outside-right" ); ?>
                        </div>
                        <div>
							<?php element( 'headline', [
								'text'  => get_sub_field( 'headline_featured_3_headline' ),
								'style' => get_sub_field( 'headline_featured_3_headline_style' )
							] ); ?>

                            <p><?php the_sub_field( 'text_feature_3' ); ?></p>

							<?php element( 'button', [
								'class'  => 'btn-black',
								'button' => get_sub_field( 'button_featured_3_button' ),
								'link'   => get_sub_field( 'button_featured_3_link' )
							] ); ?>
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
