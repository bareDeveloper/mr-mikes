<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$testimonials = get_sub_field( 'testimonials' );
if ( ! empty( $testimonials ) ) :

	$section_id = get_sub_field( 'section_id' );

	?>
    <section class="mrm-testimonials"
		<?php if ( ! empty( $section_id ) ) : ?>
            id="<?php echo esc_attr( $section_id ); ?>"
		<?php endif; ?>
    >

        <div class="mrm-testimonials-slider swiper">
            <div class="mrm-testimonials-slider-wrapper swiper-wrapper">
				<?php for ($i = 0; $i < 3; $i ++): ?>
				<?php foreach ( $testimonials as $testimonial ) : ?>
                    <div class="mrm-testimonials-slider-slide swiper-slide">
                        <div class="mrm-testimonials-slider-slide-body">
							<?php if ( ! empty( $testimonial['testimonial_text'] ) ) : ?>
                                <span class="mrm-testimonials-slider-slide__text">
                                    <?php echo esc_html( $testimonial['testimonial_text'] ); ?>
                                </span>
							<?php endif; ?>
                            <div class="mrm-testimonials-slider-slide-footer">
								<?php if ( ! empty( $testimonial['author_name'] ) ) : ?>
                                    <span class="mrm-testimonials-slider-slide__author">
                                        <?php echo esc_html( " – " . $testimonial['author_name'] ); ?>
                                    </span>
								<?php endif; ?>
								<?php if ( ! empty( $testimonial['author_caption'] ) ): ?>
                                    <span class="mrm-testimonials-slider-slide__author-caption">
                                        <?php echo esc_html( $testimonial['author_caption'] ); ?>
                                    </span>
								<?php endif; ?>
                            </div>
                        </div>
                    </div>
				<?php endforeach; ?>
				<?php endfor; ?>
            </div>

            <div class="swiper-pagination swiper-pagination-bullets swiper-pagination-horizontal" id="mrm-testimonial-swiper-pagination">
				<?php foreach ( $testimonials as $testimonial ) : ?>
					<span class="swiper-pagination-bullet swiper-pagination-bullet-active"></span>
				<?php endforeach;?>
			</div>

            <button class="mrm-testimonials-slider__prev-btn">
                <svg width="122" height="322" viewBox="0 0 122 322" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M121.42 322H91.71C83.72 322 77.21 315.5 77.21 307.5V15.5C77.21 7.51 83.71 1 91.71 1H121.42V2H91.71C84.27 2 78.21 8.05 78.21 15.5V307.5C78.21 314.94 84.26 321 91.71 321H121.42V322ZM25.22 200.75L1.17 161.5L25.23 122.25L24.38 121.73L0 161.5L24.38 201.27L25.22 200.75Z"
                          fill="black" stroke="black" stroke-miterlimit="10"/>
                </svg>
            </button>
            <button class="mrm-testimonials-slider__next-btn">
                <svg width="123" height="322" viewBox="0 0 123 322" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0.499916 321.5H30.2099C38.1999 321.5 44.7099 315 44.7099 307V15C44.7099 7.01 38.2099 0.5 30.2099 0.5H0.499916V1.5H30.2099C37.6499 1.5 43.7099 7.55 43.7099 15V307C43.7099 314.44 37.6599 320.5 30.2099 320.5H0.499916V321.5ZM96.6999 200.25L120.75 161L96.6899 121.75L97.5399 121.23L121.92 161L97.5399 200.77L96.6999 200.25Z"
                          fill="black" stroke="black" stroke-miterlimit="10"/>
                </svg>
            </button>

        </div>
    </section>
<?php endif; ?>