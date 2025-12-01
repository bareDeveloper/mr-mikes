<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$section_id        = get_sub_field( 'section_id' );
$bgImg             = get_sub_field( 'background_image' );
$contentBgType     = get_sub_field( 'content_background' );
$giantQuote        = get_sub_field( 'giant_quote' );
$giantQuoteName    = get_sub_field( 'giant_quote_name' );
$giantQuoteTitle   = get_sub_field( 'giant_quote_title' );
$grunge            = get_sub_field( 'grunge' );
$textColour        = get_sub_field( 'text_colour' );
$textStyle         = get_sub_field( 'text_style' );
$title             = get_sub_field( 'optional_title' );
$vPad              = get_sub_field( 'vertical_padding' );
$wallpaperGradient = get_sub_field( 'wallpaper_gradient' );
$width             = get_sub_field( 'width' );
$exteriorBeforeImage = get_sub_field( 'exterior_before_image' );
$exteriorAfterImage = get_sub_field( 'exterior_after_image' );

$cta_button_link = get_sub_field( 'cta_button_link' );

if ( $textStyle == "large" ):
	$textClass = "text-large";
else:
	$textClass = "";
endif;

if ( $textColour == "white" ):
	$textColourClass = "text-white";
else:
	$textColourClass = "";
endif;

$contentBgClass = "";
if ( $contentBgType === "gradient" ) {
	$contentBgClass = "bg-gradient-content";
} else {
	$contentBgColor = get_sub_field( 'content_bg_color' );
	if ( ! empty( $contentBgColor ) ) {
		$contentContainerStyles = "background-color: " . $contentBgColor . ';';
	}
}

if ( $wallpaperGradient == "black-gradient" ):
	$wallGradient = "wallpaper-gradient wallpaper-gradient--black";
elseif ( $wallpaperGradient == "white-gradient" ):
	$wallGradient = "wallpaper-gradient wallpaper-gradient--white";
else:
	$wallGradient = "";
endif;

if ( $bgImg ):
	$bgImgUrl = "background-image:url(" . $bgImg['url'] . ");";
	$hasBg    = "has-bg";
else :
	$bgImgUrl = "";
	$hasBg    = "";
endif;

if ( $width == "narrow" ):
	$contentWidth = "text__contents--narrow";
else :
	$contentWidth = "";
endif;

if ( $vPad == "extra" ):
	$vPadClass = "vpad-extra";
elseif ( $vPad == "extra-bot" ):
	$vPadClass = "";
endif;

if ( $vPad == "extra" || $vPad == "extra-top" || $vPad == "extra-bot" ):
	$vPadClass = "vpad-" . $vPad;
else:
	$vPadClass = "";
endif;

if ( get_sub_field( 'classes' ) ):
	$classes = get_sub_field( 'classes' );
else :
	$classes = "";
endif;

$classes = get_sub_field( 'remove_padding' ) ?
	"$classes really-no-padding" :
	$classes;

$exteriorAfterImageUrl = $exteriorAfterImage['url'];
$exteriorBeforeImageUrl = $exteriorBeforeImage['url'];

?>

<div class="text gallery <?php echo $hasBg; ?> <?php echo $wallGradient; ?>"
     style="<?php echo $bgImgUrl; ?>"
	<?php if ( ! empty( $section_id ) ) : ?>
        id="<?php echo esc_attr( $section_id ); ?>"
	<?php endif; ?>
>
    <div class="gallery__container">
        <?php if ( ! empty( $cta_button_link ) ):

            $cta_button_link_url = $cta_button_link['url'];
            $cta_button_link_title = $cta_button_link['title'];
            $cta_button_link_target = $cta_button_link['target'] ?: '_self';

            ?>
			<div class="mobile">
				<div class="text-cta-wrapper">
					<a href="<?php echo esc_url( $cta_button_link_url ); ?>"
					target="<?php echo esc_attr( $cta_button_link_target ); ?>"
					title="<?php echo esc_attr( $cta_button_link_title ); ?>"
					class="text-cta__button"
					>
						<?php echo esc_html( $cta_button_link_title ); ?>
					</a>
				</div>
			</div>
        <?php endif; ?>
        <div class="images">
            <div class="interior">
                <div class="content-header">
                    <p class="title">
                        Interior
                    </p>
                    <div class="tabs">
                        <div class="tab-item interior-links active" id="interior-before-tab">Before</div>
                        <div class="tab-item interior-links" id="interior-after-tab">After</div>
                    </div>
                </div>
				<div class="interior-swiper-before-container" id="interior-before-swiper" >
					<div class="interior-swiper-before swiper">
						<div class="swiper-wrapper">
							<?php if ( have_rows('interior_before_images') ): ?>
								<?php while ( have_rows('interior_before_images') ): the_row(); ?>
									<?php 
										$image = get_sub_field('image');
										$image_url = esc_url($image['url']);
									?>
									<div class="swiper-slide">
										<img src="<?php echo $image_url?>"/>
									</div>
								<?php endwhile;?>
							<?php endif; ?>
						</div>
					</div>

					<div class="interior-slider-before-controller">
						<div>
							<button class="interior-slider-before__prev-btn">
								<svg width="35" height="69" viewBox="0 0 35 69" fill="none" xmlns="http://www.w3.org/2000/svg">
								<g clip-path="url(#clip0_3737_24)">
									<path d="M32.2341 5.56348V14.4189L12.1495 34.5L32.2341 54.5811V63.4366L3.25953 34.5L32.2341 5.56348Z" fill="white"/>
									<path d="M32.2343 5.56345L3.29259 34.5L32.2343 63.4365V54.5811L12.1496 34.5L32.2343 14.4189V5.56345ZM34.5391 0V15.3736L33.8476 16.0649L15.4092 34.5L33.8476 52.968L34.5391 53.6594V69.0329L30.6209 65.1155L1.67923 36.1789L0.065876 34.5658L1.67923 32.9528L30.588 3.91746L34.5391 0Z" fill="black"/>
								</g>
								<defs>
									<clipPath id="clip0_3737_24">
										<rect width="35" height="69" fill="white" transform="matrix(-1 0 0 1 35 0)"/>
									</clipPath>
								</defs>
								</svg>
							</button>
							<button class="interior-slider-before__next-btn">
								<svg width="35" height="69" viewBox="0 0 35 69" fill="none" xmlns="http://www.w3.org/2000/svg">
									<g clip-path="url(#clip0_3737_21)">
										<path d="M2.76587 5.56348V14.4189L22.8505 34.5L2.76587 54.5811V63.4366L31.7405 34.5L2.76587 5.56348Z" fill="white"/>
										<path d="M2.76573 5.56345L31.7074 34.5L2.76573 63.4365V54.5811L22.8504 34.5L2.76573 14.4189V5.56345ZM0.460938 0V15.3736L1.15238 16.0649L19.5908 34.5L1.15238 52.968L0.460938 53.6594V69.0329L4.37909 65.1155L33.3208 36.1789L34.9341 34.5658L33.3208 32.9528L4.41202 3.91746L0.460938 0Z" fill="black"/>
									</g>
									<defs>
										<clipPath id="clip0_3737_21">
											<rect width="35" height="69" fill="white"/>
										</clipPath>
									</defs>
								</svg>
							</button>
						</div>
					</div>
				</div>
				<div class="interior-swiper-after-container" id="interior-after-swiper">
					<div class="interior-swiper-after swiper">
						<div class="swiper-wrapper">
							<?php if ( have_rows('interior_after_images') ): ?>
								<?php while ( have_rows('interior_after_images') ): the_row(); ?>
									<?php 
										$image = get_sub_field('image');
										$image_url = esc_url($image['url']);
									?>
									<div class="swiper-slide">
										<img src="<?php echo $image_url?>"/>
									</div>
								<?php endwhile;?>
							<?php endif; ?>
						</div>
					</div>

					<div class="interior-slider-after-controller">
						<div>
							<button class="interior-slider-after__prev-btn">
								<svg width="35" height="69" viewBox="0 0 35 69" fill="none" xmlns="http://www.w3.org/2000/svg">
								<g clip-path="url(#clip0_3737_24)">
									<path d="M32.2341 5.56348V14.4189L12.1495 34.5L32.2341 54.5811V63.4366L3.25953 34.5L32.2341 5.56348Z" fill="white"/>
									<path d="M32.2343 5.56345L3.29259 34.5L32.2343 63.4365V54.5811L12.1496 34.5L32.2343 14.4189V5.56345ZM34.5391 0V15.3736L33.8476 16.0649L15.4092 34.5L33.8476 52.968L34.5391 53.6594V69.0329L30.6209 65.1155L1.67923 36.1789L0.065876 34.5658L1.67923 32.9528L30.588 3.91746L34.5391 0Z" fill="black"/>
								</g>
								<defs>
									<clipPath id="clip0_3737_24">
										<rect width="35" height="69" fill="white" transform="matrix(-1 0 0 1 35 0)"/>
									</clipPath>
								</defs>
								</svg>
							</button>
							<button class="interior-slider-after__next-btn">
								<svg width="35" height="69" viewBox="0 0 35 69" fill="none" xmlns="http://www.w3.org/2000/svg">
									<g clip-path="url(#clip0_3737_21)">
										<path d="M2.76587 5.56348V14.4189L22.8505 34.5L2.76587 54.5811V63.4366L31.7405 34.5L2.76587 5.56348Z" fill="white"/>
										<path d="M2.76573 5.56345L31.7074 34.5L2.76573 63.4365V54.5811L22.8504 34.5L2.76573 14.4189V5.56345ZM0.460938 0V15.3736L1.15238 16.0649L19.5908 34.5L1.15238 52.968L0.460938 53.6594V69.0329L4.37909 65.1155L33.3208 36.1789L34.9341 34.5658L33.3208 32.9528L4.41202 3.91746L0.460938 0Z" fill="black"/>
									</g>
									<defs>
										<clipPath id="clip0_3737_21">
											<rect width="35" height="69" fill="white"/>
										</clipPath>
									</defs>
								</svg>
							</button>
						</div>
					</div>
				</div>
            </div>
            <div class="exterior">
                <div class="content-header">
                    <p class="title">
                        Exterior
                    </p>
                    <div class="tabs">
                        <div class="tab-item exterior-links active" onclick="openExterior(event, 'before')" id="exterior-before-tab">Before</div>
                        <div class="tab-item exterior-links" onclick="openExterior(event, 'after')" id="exterior-after-tab">After</div>
                    </div>
                </div>
                <div>
                    <div class="exterior-content" id="before">
                        <img src="<?php echo $exteriorBeforeImageUrl?>"/>
                    </div>
                    <div class="exterior-content" id="after">
                        <img src="<?php echo $exteriorAfterImageUrl?>"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="text__container <?php echo $classes; ?>"
            <?php if ( ! empty( $contentContainerStyles ) ) : ?>
                style="<?php echo esc_attr( $contentContainerStyles ); ?>"
            <?php endif; ?>
        >
            <?php
            $image = get_sub_field( 'extra_image' );

            if ( ! empty( $image ) ) {
                $url = $image['url'];
                $alt = $image['alt']; ?>

                <img
                        alt="<?php echo $alt ?>"
                        class="text__extra-image right-side"
                        src="<?php echo $url; ?>"
                />
            <?php } ?>

            <div class="text__contents <?php echo $contentWidth; ?> <?php echo $textClass; ?> <?php echo $textColourClass; ?> <?php echo $vPadClass; ?>">
                <?php if ( $title ): ?>
                    <h2 class="text__container__title">
                        <?php echo $title; ?>
                    </h2>
                <?php endif; ?>

                <?php the_sub_field( 'text' ); ?>

                <?php if ( ! empty( $cta_button_link ) ):

                    $cta_button_link_url = $cta_button_link['url'];
                    $cta_button_link_title = $cta_button_link['title'];
                    $cta_button_link_target = $cta_button_link['target'] ?: '_self';

                    ?>
                    <div class="text-cta-wrapper" style="padding-top: 30px;">
                        <a href="<?php echo esc_url( $cta_button_link_url ); ?>"
                        target="<?php echo esc_attr( $cta_button_link_target ); ?>"
                        title="<?php echo esc_attr( $cta_button_link_title ); ?>"
                        class="text-cta__button"
                        >
                            <?php echo esc_html( $cta_button_link_title ); ?>
                        </a>
                    </div>
                <?php endif; ?>

                <?php if ( $giantQuote ): ?>
                    <div class="text__giant-quote">
                        <div class="text__giant-quote__quote">
                            <?php echo $giantQuote; ?>
                        </div>
                        <div class="text__giant-quote__name">
                            <?php echo $giantQuoteName; ?>
                        </div>
                        <div class="text__giant-quote__title">
                            <?php echo $giantQuoteTitle; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ( $grunge != "none" ): ?>
                <div class="text__grunge <?php echo $grunge; ?>">
                    <?php echo svg( "grunge/8-feature-bottom-right" ); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>