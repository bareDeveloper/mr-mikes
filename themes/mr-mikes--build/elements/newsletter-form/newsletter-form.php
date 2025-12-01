<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$newsletter_form_header_title       = get_sub_field( 'newsletter_form_header_title' );
$newsletter_form_header_title_color = get_sub_field( 'newsletter_form_header_title_color' );
$newsletter_form_header_bg_image    = get_sub_field( 'newsletter_form_header_bg_image' );
$newsletter_form_header_bg_color    = get_sub_field( 'newsletter_form_header_bg_color' );


// Building the shortcode
$newsletter_form_shortcode = '[newsletter_form';

if ( ! empty( $newsletter_form_header_bg_image ) ) {
	$newsletter_form_shortcode .= ' img_id="' . $newsletter_form_header_bg_image . '"';
}

if ( ! empty( $newsletter_form_header_title ) ) {
	$newsletter_form_shortcode .= ' title="' . $newsletter_form_header_title . '"';
}

if ( ! empty( $newsletter_form_header_bg_color ) ) {
	$newsletter_form_shortcode .= ' header_bg_color="' . $newsletter_form_header_bg_color . '"';
}

if ( ! empty( $newsletter_form_header_title_color ) ) {
	$newsletter_form_shortcode .= ' title_color="' . $newsletter_form_header_title_color . '"';
}

$newsletter_form_shortcode .= ']';

?>
<section class="mrm-newsletter-form">
    <div class="text__container">
		<?php echo do_shortcode( $newsletter_form_shortcode ); ?>
    </div>
</section>