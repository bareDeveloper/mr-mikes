<?php

$title     = get_sub_field( 'et_title' );
$text      = get_sub_field( 'et_text' );
$eventList = get_sub_field( 'et_list' );

$section_id = get_sub_field( 'section_id' );
?>
<div class="event-table"
	<?php if ( ! empty( $section_id ) ) : ?>
        id="<?php echo esc_attr( $section_id ); ?>"
	<?php endif; ?>
>
    <div class="event-table__container grid-edges--medium">
        <div class="event-table__content">

            <h2 class="event-table__title">
				<?php echo $title; ?>
            </h2>

            <div class="event-table__text">
				<?php echo $text; ?>
            </div>

            <div class="event-table__list">
				<?php if ( have_rows( 'et_list' ) ): while ( have_rows( 'et_list' ) ): the_row();
					$name     = get_sub_field( 'event_name' );
					$link     = get_sub_field( 'event_link' );
					$location = get_sub_field( 'event_location' );
					?>
                    <div class="event-table__list-row">
                        <div class="event-table__list-col">
							<?php echo $name; ?>
                        </div>
                        <div class="event-table__list-col">
                            <a target="_blank" href="<?php echo $link['url']; ?>"><?php echo $link['title']; ?></a>
                        </div>
                        <div class="event-table__list-col">
							<?php echo $location; ?>
                        </div>
                    </div>
				<?php endwhile; ?>
				<?php endif; ?>
            </div>
        </div>
    </div>
</div>

