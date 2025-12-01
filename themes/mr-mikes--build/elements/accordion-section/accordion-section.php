<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$section_title           		= get_sub_field( 'section_title' );
$section_subtitle        		= get_sub_field( 'section_subtitle' );
$accordion_items         		= get_sub_field( 'accordion_items' );
$section_footer_title    		= get_sub_field( 'section_footer_title' );
$section_footer_subtitle 		= get_sub_field( 'section_footer_subtitle' );
$section_cta_button_link 		= get_sub_field( 'section_cta_button_link' );
$section_cta_button_description = get_sub_field( 'section_cta_button_description' );

$section_styles   = "";
$section_bg_image = get_sub_field( 'section_bg_image' );
$section_bg_color = get_sub_field( 'section_bg_color' );

if ( ! empty( $section_bg_image ) ) {
	$section_styles .= "background-image: url(" . $section_bg_image['url'] . ");";
} elseif ( ! empty( $section_bg_color ) ) {
	$section_styles .= "background-color: " . $section_bg_color . ";";
}

$section_id = get_sub_field( 'section_id' );
?>
<section class="mrm-accordion"
	<?php if ( ! empty( $section_styles ) ) : ?>
        style="<?php echo esc_attr( $section_styles ); ?>"
	<?php endif; ?>
	<?php if ( ! empty( $section_id ) ) : ?>
        id="<?php echo esc_attr( $section_id ); ?>"
	<?php endif; ?>
>
    <div class="mrm-accordion-body">
		<?php if ( ! empty( $section_title ) || ! empty( $section_subtitle ) ) : ?>
            <div class="mrm-accordion-header">

				<?php if ( ! empty( $section_title ) ) : ?>
                    <h2 class="mrm-accordion__title">
						<?php echo esc_html( $section_title ); ?>
                    </h2>
				<?php endif; ?>
				<?php if ( ! empty( $section_subtitle ) ) : ?>
                    <span class="mrm-accordion__subtitle">
                        <?php echo esc_html( $section_subtitle ); ?>
                    </span>
				<?php endif; ?>
            </div>
		<?php endif; ?>

		<?php if ( ! empty( $accordion_items ) ) : ?>
            <div class="mrm-accordion-items">

				<?php foreach ( $accordion_items as $accordion_item ) : ?>
                    <div class="mrm-accordion-items__item">
                        <div class="mrm-accordion-items__item-header">
                            <span class="mrm-accordion-items__item-header-text">
                                <?php echo esc_html( $accordion_item['item_title'] ); ?>
                            </span>
                            <span class="mrm-accordion-items__item-header-icon">
                                <svg width="34" height="15" viewBox="0 0 34 15" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_1301_68)">
                                        <path d="M16.9966 15L0 1.5L1.29285 0L16.9966 12.474L32.7004 0L34 1.5L16.9966 15Z"
                                              fill="#9C182F"/>
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_1301_68">
                                            <rect width="34" height="15" fill="white"/>
                                        </clipPath>
                                    </defs>
                                </svg>
                            </span>
                        </div>
                        <div class="mrm-accordion-items__item-body">
                            <div class="mrm-accordion-items__item-body-text">
								<?php echo wp_kses_post( $accordion_item['item_text'] ); ?>
                            </div>
                        </div>
                    </div>
				<?php endforeach; ?>
            </div>
		<?php endif; ?>

		<?php if ( ! empty( $section_footer_title ) || ! empty( $section_footer_subtitle ) || ! empty( $section_cta_button_link ) ): ?>
            <div class="mrm-accordion-footer">
				<?php if ( ! empty( $section_footer_title ) || ! empty( $section_footer_subtitle ) ): ?>
                    <div class="mrm-accordion-footer__cta-text">
						<?php if ( ! empty( $section_footer_title ) ) : ?>
                            <span class="mrm-accordion-footer__cta-title">
                                <?php echo esc_html( $section_footer_title ); ?>
                            </span>
						<?php endif; ?>
						<?php if ( ! empty( $section_footer_subtitle ) ) : ?>
                            <span class="mrm-accordion-footer__cta-subtitle">
                                <?php echo esc_html( $section_footer_subtitle ); ?>
                            </span>
						<?php endif; ?>
                    </div>
				<?php endif; ?>
				<div class="text-cta-container">
					<?php if ( ! empty($section_cta_button_description ) ): ?>
						<p class="text-cta-description">
							<?php echo $section_cta_button_description; ?>
						</p>
					<?php endif; ?>
					<?php if ( ! empty( $section_cta_button_link ) ) :

						$section_cta_button_link_url = $section_cta_button_link['url'];
						$section_cta_button_link_title = $section_cta_button_link['title'];
						$section_cta_button_link_target = $section_cta_button_link['target'] ?: '_self';

						?>
						<a href="<?php echo esc_url( $section_cta_button_link_url ); ?>"
						   target="<?php echo esc_attr( $section_cta_button_link_target ); ?>"
						   title="<?php echo esc_attr( $section_cta_button_link_title ); ?>"
						   class="mrm-accordion-footer__cta-btn"
						>
							<?php echo esc_html( $section_cta_button_link_title ); ?>
						</a>
					<?php endif; ?>
				</div>
            </div>
		<?php endif; ?>
    </div>
</section>