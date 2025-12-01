<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$section_id        = get_sub_field( 'section_id' );
$type_of_the_video = get_sub_field( 'type_of_the_video' );
?>
<section class="mrm-video-section"
	<?php if ( ! empty( $section_id ) ) : ?>
        id="<?php echo esc_attr( $section_id ); ?>"
	<?php endif; ?>
>
    <div class="mrm-video-section-video-wrapper">
		<?php if ( ! empty( $type_of_the_video ) ) : ?>
			<?php if ( $type_of_the_video === 'local_file' ) :

				$section_video_link = get_sub_field( 'section_video' );

				if ( ! empty( $section_video_link ) ) : ?>
                    <video src="<?php echo esc_url( $section_video_link['url'] ); ?>"
                           controls
                           class="mrm-video-section__video"
                    ></video>
				<?php endif; ?>
			<?php elseif ( $type_of_the_video === 'embed' ) :

				$section_video_embed = get_sub_field( 'section_video_embed' );

				if ( ! empty( $section_video_embed ) ) :
					echo $section_video_embed;
				endif;

			endif; ?>

		<?php endif; ?>
    </div>
</section>