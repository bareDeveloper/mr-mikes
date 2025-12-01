<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$section_id = get_sub_field( 'section_id' );
?>
<div class="front-simplified"
	<?php if ( ! empty( $section_id ) ) : ?>
        id="<?php echo esc_attr( $section_id ); ?>"
	<?php endif; ?>
>
    <div class="front-simplified__main-area">
        <div class="front-simplified__main-area-desktop lazyload" <?php echo lazy_background( get_sub_field( 'image_desktop' ) ); ?>>
            <div class="front-simplified__main-area-edges">
                <div class="front-simplified__main-area-content">
					<?php element( 'headline', [
						'text'  => get_sub_field( 'headline' ),
						'style' => 'h1'
					] ); ?>

					<?php element( 'headline', [
						'text'  => get_sub_field( 'subline' ),
						'style' => 'h4',
						'class' => 'subline'
					] ); ?>

					<?php element( 'button', [
						'class'  => 'btn-white',
						'button' => get_sub_field( 'button' )['title'],
						'link'   => get_sub_field( 'button' )['url']
					] ); ?>
                </div>
            </div>
        </div>

        <div class="front-simplified__main-area-mobile lazyload" <?php echo lazy_background( get_sub_field( 'image_mobile' ) ); ?>>
            <div class="front-simplified__main-area-edges">
                <div class="front-simplified__main-area-content">
					<?php element( 'headline', [
						'text'  => get_sub_field( 'headline' ),
						'style' => 'h1'
					] ); ?>

					<?php element( 'headline', [
						'text'  => get_sub_field( 'subline' ),
						'style' => 'h4',
						'class' => 'subline'
					] ); ?>

					<?php element( 'button', [
						'class'  => 'btn-white',
						'button' => get_sub_field( 'button' )['title'],
						'link'   => get_sub_field( 'button' )['url']
					] ); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="front-simplified__text-area">
        <div class="front-simplified__text-area-inner">
            <div class="front-simplified__text-area-text">
				<?php the_sub_field( 'text' ); ?>
            </div>
        </div>
    </div>

</div>  