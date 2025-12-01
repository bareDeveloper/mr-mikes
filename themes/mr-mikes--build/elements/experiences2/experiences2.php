<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$section_id = get_sub_field( 'section_id' );
?>
<!-- Experiences 2 -->
<div class="experiences2"
	<?php if ( ! empty( $section_id ) ) : ?>
        id="<?php echo esc_attr( $section_id ); ?>"
	<?php endif; ?>
>
    <div class="experiences2__items">
		<?php $i = 0; ?>
		<?php if ( have_rows( 'keyfacts' ) ) :
			while ( have_rows( 'keyfacts' ) ) : the_row(); ?>
                <div class="experiences2__item">

                    <!-- blobk bg -->
                    <div class="<?php echo ( $i == 0 ) ? 'experiences2__item-bg' : 'experiences2__item-bg2'; ?>" <?php echo lazy_background( get_sub_field( 'block_background' ) ); ?>>
                    </div>

                    <!-- text bg -->
                    <div class="experiences2__text-bg" <?php echo lazy_background( get_sub_field( 'text_background' ) ); ?>>
                    </div>

                    <!-- text -->
                    <div class="experiences2__text">
						<?php
						echo
						the_sub_field( 'text' );
						?>
						<?php
						$button = get_sub_field( 'button' );

						if ( ! empty( $button['title'] ) && ! empty( $button['url'] ) ) {
							// Render the div if button value exists
							echo "<div class='button__container'>";
							element( 'button', [
								'class'  => 'btn-black',
								'button' => $button['title'],
								'link'   => $button['url']
							] );
							echo "</div>";
						}
						?>

                    </div>
                </div>


				<?php $i = ( $i == 0 ) ? 1 : 0; ?>
			<?php endwhile;
			$i = 0;
		endif; ?>

    </div>


</div>
<!-- End Experiences 2 -->