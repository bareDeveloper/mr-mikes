<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$section_title           		= get_sub_field( "section_title" );
$section_subtitle        		= get_sub_field( "section_subtitle" );
$section_cta_button_link 		= get_sub_field( "section_cta_button_link" );
$section_cta_button_description = get_sub_field( "section_cta_button_description" );
$section_bg_video        		= get_sub_field( "section_bg_video" );

if ( ! empty( $section_title ) || ! empty( $section_subtitle ) || ! empty( $section_cta_button_link ) || ! empty( $section_bg_video ) ) :

	$section_id = get_sub_field( 'section_id' );

	?>
    <section class="mrm-lp-hero"
		<?php if ( ! empty( $section_id ) ) : ?>
            id="<?php echo esc_attr( $section_id ); ?>"
		<?php endif; ?>
    >
		<?php if ( ! empty( $section_bg_video ) ) : ?>
            <video
                    src="<?php echo esc_url( $section_bg_video['url'] ); ?>"
                    autoplay
                    muted
                    loop
                    playsinline
                    class="mrm-lp-hero__bg-video"
            ></video>
		<?php endif; ?>

        <div class="mrm-lp-hero-content">
			<?php if ( ! empty( $section_title ) ) : ?>
                <h1 class="mrm-lp-hero-content__title">
					<?php echo esc_html( $section_title ); ?>
                </h1>
			<?php endif; ?>
			<?php if ( ! empty( $section_subtitle ) ) : ?>
                <span class="mrm-lp-hero-content__subtitle">
                    <?php echo esc_html( $section_subtitle ); ?>
                </span>
			<?php endif; ?>
			
			<div class="text-cta-container">
				<?php if ( ! empty( $section_cta_button_description ) ): ?>
					<p class="text-cta-description mrm-lp-hero-content__cta-button-description">
						<?php echo $section_cta_button_description; ?>
					</p>
				<?php endif; ?>

				<?php if ( ! empty( $section_cta_button_link ) ) :

					$section_cta_button_link_url = $section_cta_button_link['url'];
					$section_cta_button_link_title = $section_cta_button_link['title'];
					$section_cta_button_link_target = $section_cta_button_link['target'] ?: '_self';

					?>
					<a
							href="<?php echo esc_url( $section_cta_button_link_url ); ?>"
							target="<?php echo esc_attr( $section_cta_button_link_target ); ?>"
							title="<?php echo esc_attr( $section_cta_button_link_title ); ?>"
							class="mrm-lp-hero-content__cta-btn"
					>
						<?php echo esc_html( $section_cta_button_link_title ); ?>
					</a>
				<?php endif; ?>
			</div>
        </div>
    </section>
<?php endif; ?>