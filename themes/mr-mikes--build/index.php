<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// TODO: Refactor later with get_header() default WP function
// Getting the Page Header Type
$header_type = get_field( 'header_type' );

if ( $header_type === 'lp_header' ) {
	element( 'header-lp' );
} else {
	element( 'header' );
}

?>

<?php if ( get_field( 'background_color_for_container' ) ) {
	$bgColor = get_field( 'background_color_for_container' );
} else {
	$bgColor = '';
} ?>

<div class="index">

    <div class="index__container <?php echo 'index__container--' . $bgColor; ?>">
		<?php
		if ( function_exists( 'get_field' ) && get_field( 'modules' ) !== null ):
			modules();
		endif;
		?>
    </div>

</div>

<?php element( 'footer' ); ?>
