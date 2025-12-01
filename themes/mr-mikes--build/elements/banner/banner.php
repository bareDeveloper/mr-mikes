<?php
if ( ! isset( $background_image ) ) {
	if ( get_sub_field( 'background_image' ) ) {
		$background_image = get_sub_field( 'background_image' );
	} else {
		$background_image = false;
	}
}

if ( ! isset( $headline ) ) {
	if ( get_sub_field( 'headline' ) ) {
		$headline = get_sub_field( 'headline' );
	} else {
		$headline = '';
	}
}

if ( get_sub_field( 'remove_gradient' ) ) {
	$gradient = "remove-gradient";
} else {
	$gradient = "";
}

$removeGrunge   = get_sub_field( 'remove_grunge' );
$height         = get_sub_field( 'height' );
$bannerPosition = get_sub_field( 'background_image_position' );

if ( $height == "large" ) {
	$heightClass = "height-" . $height;
} else {
	$heightClass = "";
}

$section_id = get_sub_field( 'section_id' );

?>

<div class="banner banner__image <?php echo $bannerPosition; ?> <?php echo $gradient; ?> <?php echo $heightClass; ?> lazyload"
	<?php echo lazy_background( $background_image ); ?>
	<?php if ( ! empty( $section_id ) ) : ?>
        id="<?php echo esc_attr( $section_id ); ?>"
	<?php endif; ?>
>
    <div class="banner__container">

		<?php
		$align = get_sub_field( 'align' );
		if ( ! $align ) {
			$align = 'left';
		}
		?>

		<?php if ( $headline !== '' ): ?>
            <div class="headline__container">
                <h1 class="headline" style="text-align: <?php echo $align; ?>">
					<?php echo $headline; ?>
                </h1>
            </div>
		<?php endif; ?>

    </div>
	<?php if ( ! $removeGrunge ): ?>
        <div class="banner__grunge">
			<?php echo svg( "grunge/8-feature-bottom-right" ); ?>
        </div>
	<?php endif; ?>
</div>