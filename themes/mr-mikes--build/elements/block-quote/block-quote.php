<?php

$bgImg             = get_sub_field( 'background_image' );
$contentBg         = get_sub_field( 'content_background' );
$grunge            = get_sub_field( 'grunge' );
$textColour        = get_sub_field( 'text_colour' );
$textStyle         = get_sub_field( 'text_style' );
$title             = get_sub_field( 'optional_title' );
$vPad              = get_sub_field( 'vertical_padding' );
$wallpaperGradient = get_sub_field( 'wallpaper_gradient' );
$width             = get_sub_field( 'width' );

$reverse_arrow = get_sub_field( 'reverse_direction' );
$default_arrow = get_sub_field( 'default_arrow' );

if ( $default_arrow ) {
	$url_default = get_static_url( 'images/arrow-step.png' );
}

$reverse_class = '';
if ( $reverse_arrow ) {
	$reverse_class = 'reverse-arrow';
}

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

if ( $contentBg == "gradient" ):
	$contentBgClass = "bg-gradient-content";
else:
	$contentBgClass = "";
endif;

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
elseif ( $vPad == "extra-top" ):
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

$section_id = get_sub_field( 'section_id' );

?>

<div class="text <?php echo $hasBg; ?> <?php echo $wallGradient; ?>"
     style="<?php echo $bgImgUrl; ?>"
	<?php if ( ! empty( $section_id ) ) : ?>
        id="<?php echo esc_attr( $section_id ); ?>"
	<?php endif; ?>
>
    <div class="text__container <?php echo $classes; ?> <?php echo $contentBgClass; ?> block-quote step-oportunity">
		<?php
		$image = get_sub_field( 'extra_image' );

		if ( $default_arrow ) {
			?>
            <img
                    alt="Arrow Step"
                    class="text__extra-image right-side arrow-step <?php echo $reverse_class; ?>"
                    src="<?php echo esc_url( $url_default ); ?>"
            />
			<?php
		} else {
			if ( ! empty( $image ) ) {
				$url = $image['url'];
				$alt = $image['alt'];
				?>
                <img
                        alt="<?php echo $alt ?>"
                        class="text__extra-image right-side arrow-step <?php echo $reverse_class; ?>"
                        src="<?php echo $url; ?>"
                />
				<?php
			}
		}
		?>

        <div
                class="text__contents <?php echo $contentWidth; ?> <?php echo $textClass; ?> <?php echo $textColourClass; ?> <?php echo $vPadClass; ?>"
        >
			<?php if ( $title ): ?>
                <h2 class="text__container__title">
					<?php echo $title; ?>
                </h2>
			<?php endif; ?>
			<?php the_sub_field( 'text' ); ?>
        </div>

		<?php if ( $grunge != "none" ): ?>
            <div class="text__grunge <?php echo $grunge; ?>">
				<?php echo svg( "grunge/8-feature-bottom-right" ); ?>
            </div>
		<?php endif; ?>
    </div>
</div>

