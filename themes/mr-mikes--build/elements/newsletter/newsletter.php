<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly or $args is empty
}

if ( empty( $args ) ) {
	return false;
}

$text_content_before_the_form = get_sub_field( 'text_content_before_the_form' );

// Get all restaurants for the dropdown (select) field
$query_args = [
	'post_type'      => 'restaurant',
	'posts_per_page' => - 1,
	'order'          => 'ASC',
	'orderby'        => 'title'
];

$query = new WP_Query( $query_args );

$header_img = wp_get_attachment_url( $args['img_id'] );
?>
<div class="mrm-newsletter">
	<?php if ( ! empty( $args['title'] ) ) : ?>
        <div class="mrm-newsletter-header"
			<?php if ( ! empty( $header_img ) ) : ?>
                style="background-image: url('<?php echo esc_url( $header_img ); ?>')"
			<?php elseif ( ! empty( $args['header_bg_color'] ) ) : ?>
                style="background-color: <?php echo esc_attr( $args['header_bg_color'] ); ?>"
			<?php endif; ?>
        >
            <h2 class="mrm-newsletter-header__title"
				<?php if ( ! empty( $args['title_color'] ) ) : ?>
                    style="color: <?php echo esc_attr( $args['title_color'] ); ?>"
				<?php endif; ?>
            >
				<?php echo esc_html( $args['title'] ); ?>
            </h2>
        </div>
	<?php endif; ?>

    <div class="mrm-newsletter-body">
		<?php if ( ! empty( $text_content_before_the_form ) ) : ?>
            <div class="mrm-newsletter__text-before-form">
				<?php echo( $text_content_before_the_form ); ?>
            </div>
		<?php endif; ?>
        <form class="mrm-newsletter-form" accept-charset="UTF-8"
              action="https://link.datacandy.com/oi/443/3cea930a60a4da8d02657b296df21760" method="get" name="oi_form">

            <div class="mrm-newsletter-form-field-group">
                <label for="mrm-newsletter-email">
					<?php esc_html_e( 'Email:', 'mrmikes' ); ?>
                </label>
                <input id="mrm-newsletter-email" name="email" type="text" required/>
            </div>

            <div class="mrm-newsletter-form-field-group">
                <label for="mrm-newsletter-first-name">
					<?php esc_html_e( 'First Name:', 'mrmikes' ); ?>
                </label>
                <input id="mrm-newsletter-first-name" name="FirstName" type="text" required/>
            </div>
            <div class="mrm-newsletter-form-field-group">
                <label for="mrm-newsletter-last-name">
					<?php esc_html_e( 'Last Name:', 'mrmikes' ); ?>
                </label>
                <input id="mrm-newsletter-last-name" name="LastName" type="text" required/>
            </div>
            <div class="mrm-newsletter-form-field-group">
                <label for="mrm-newsletter-location">
					<?php esc_html_e( 'Your Favourite Location:', 'mrmikes' ); ?>
                </label>
                <select id="mrm-newsletter-location" name="Location" required>
                    <option value="" disabled selected hidden>
						<?php esc_html_e( 'Select your location', 'mrmikes' ); ?>
                    </option>
					<?php if ( $query->have_posts() ) : ?>
						<?php while ( $query->have_posts() ) : $query->the_post(); ?>
                            <option value="<?php echo esc_attr( get_the_title() ); ?>"><?php echo esc_html( get_the_title() ); ?></option>
						<?php endwhile;
						wp_reset_postdata(); ?>
					<?php endif; ?>
                </select>
            </div>

            <input name="goto" type="hidden" value=""/>
            <input name="iehack" type="hidden" value="☠"/>

            <span class="mrm-newsletter__caption">
                <?php esc_html_e( 'We use a marketing automation platform in order to manage our client
        relationship and send you commercial electronic messages. By clicking to "Subscribe", you acknowledge that the
        information you provide will be transferred to us in accordance to our Privacy Policy and Terms of Use.', 'mrmikes' ); ?>
            </span>

            <button class="mrm-newsletter-form__submit" type="submit">
				<?php esc_html_e( 'Subscribe', 'mrmikes' ); ?>
            </button>
        </form>
    </div>
</div>