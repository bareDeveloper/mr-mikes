<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$section_id = get_sub_field( 'section_id' );
?>
<div class="page-teaser lazyload" <?php echo lazy_background( get_sub_field( 'background_image' ) ); ?>
	<?php if ( ! empty( $section_id ) ) : ?>
        id="<?php echo esc_attr( $section_id ); ?>"
	<?php endif; ?>
>
    <div class="page-teaser__container grid-edges">

		<?php if ( have_rows( 'pages' ) ) : ?>
			<?php while ( have_rows( 'pages' ) ) : the_row(); ?>

                <div class="card">

                    <div class="card__image lazyload" <?php echo lazy_background( get_sub_field( 'thumbnail' ) ); ?>></div>

                    <div class="card__content">

                        <div class="card__text">
							<?php the_sub_field( 'text' ); ?>
                        </div>

						<?php
						$link = get_sub_field( 'link' );

						if ( $link ) :
							if ( props( $link, 'title' ) ):
								$title = $link['title'];
							else :
								$title = 'Feed me more...';
							endif;

							element( 'button', [
								'id'     => '',
								'class'  => '',
								'button' => $title,
								'link'   => $link['url']
							] );
						endif;
						?>

                    </div>

                </div>

			<?php endwhile; ?>
		<?php endif; ?>
    </div>
</div>