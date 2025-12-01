<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$section_id = get_sub_field( 'section_id' );
?>
<div class="full-width-image <?php the_field( 'size' ); ?> <?php the_field( 'bg-color' ); ?>"
	<?php if ( ! empty( $section_id ) ) : ?>
        id="<?php echo esc_attr( $section_id ); ?>"
	<?php endif; ?>
>
	<?php
	$image = get_field( 'image' );
	if ( ! empty( $image ) ): ?>
        <img class-"lazyload" src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" />
	<?php endif; ?>
</div>