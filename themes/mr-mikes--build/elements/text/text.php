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

$cta_button_link = get_sub_field( 'cta_button_link' );
$cta_button_description = get_sub_field( 'cta_button_description' );

$second_cta_button_link = get_sub_field( 'second_cta_button_link' );
$second_cta_button_description = get_sub_field( 'second_cta_button_description' );

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

?>

<div class="text <?php echo $hasBg; ?> <?php echo $wallGradient; ?>"
     style="<?php echo $bgImgUrl; ?>"
	<?php if ( ! empty( $section_id ) ) : ?>
        id="<?php echo esc_attr( $section_id ); ?>"
	<?php endif; ?>
>
    <div class="text__container <?php echo $classes; ?> <?php echo $contentBgClass; ?>"
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
		<?php $page_ids = array(66532);
            $heading_tag = ( is_page() && in_array( get_the_ID(), $page_ids ) ) ? 'h3' : 'h2';
            if ( $title ): ?>
                            <<?php echo $heading_tag; ?> class="text__container__title">
                                <?php echo $title; ?>
                            </<?php echo $heading_tag; ?>>
            <?php endif; ?>

			<?php the_sub_field( 'text' ); ?>
			
			<div class="text-cta-group">
				<?php if ( ! empty( $cta_button_link) || !empty( $cta_button_description)): ?>
					<div class="text-cta-container">
						<?php if ( ! empty( $cta_button_description ) ): ?>
							<p class="text-cta-description"><?php echo $cta_button_description; ?></p>
						<?php endif; ?>
						<?php if ( ! empty( $cta_button_link ) ):

							$cta_button_link_url = $cta_button_link['url'];
							$cta_button_link_title = $cta_button_link['title'];
							$cta_button_link_target = $cta_button_link['target'] ?: '_self';

							?>
							<div class="text-cta-wrapper">
								<a href="<?php echo esc_url( $cta_button_link_url ); ?>"
								   target="<?php echo esc_attr( $cta_button_link_target ); ?>"
								   title="<?php echo esc_attr( $cta_button_link_title ); ?>"
								   class="text-cta__button"
								>
									<?php echo esc_html( $cta_button_link_title ); ?>
								</a>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>
				<?php if ( ! empty( $second_cta_button_link) || !empty( $second_cta_button_description)): ?>
					<div class="text-cta-container">
						<?php if ( ! empty( $second_cta_button_description ) ): ?>
							<p class="text-cta-description"><?php echo $second_cta_button_description; ?></p>
						<?php endif; ?>
						<?php if ( ! empty( $cta_button_link ) ):

							$second_cta_button_link_url = $second_cta_button_link['url'];
							$second_cta_button_link_title = $second_cta_button_link['title'];
							$second_cta_button_link_target = $second_cta_button_link['target'] ?: '_self';

							?>
							<div class="text-cta-wrapper">
								<a href="<?php echo esc_url( $second_cta_button_link_url ); ?>"
								   target="<?php echo esc_attr( $second_cta_button_link_target ); ?>"
								   title="<?php echo esc_attr( $second_cta_button_link_title ); ?>"
								   class="text-cta__button"
								>
									<?php echo esc_html( $second_cta_button_link_title ); ?>
								</a>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>

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