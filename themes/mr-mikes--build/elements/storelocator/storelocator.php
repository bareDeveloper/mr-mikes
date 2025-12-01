<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$section_id = get_sub_field( 'section_id' );
?>
<div class="storelocator"
	<?php if ( ! empty( $section_id ) ) : ?>
        id="<?php echo esc_attr( $section_id ); ?>"
	<?php endif; ?>
>

    <div class="storelocator__container">
		<?php the_sub_field( 'text' ); ?>
    </div>

    <div class="storelocator__container">

		<?php

		$terms = get_terms( [
			'taxonomy'   => 'provinces',
			'hide_empty' => true,
		] );

		foreach ( $terms as $term ):

			echo '<h3 class="storelocator__province">' . $term->name . '</h3>';
			echo '<div class="storelocator__provinces">';

			$term_id = $term->term_id;

			$args = [
				'post_type'      => 'restaurant',
				'posts_per_page' => - 1,
				'tax_query'      => array(
					array(
						'taxonomy' => 'provinces',
						'field'    => 'term_id',
						'terms'    => $term_id,
					)
				)
			];

			$postslist = get_posts( $args );
			foreach ( $postslist as $post ) : setup_postdata( $post );

				$post_id          = $post->ID;
				$address          = get_field( "address", $post_id );
				$open_table_id    = get_field( "open_table_id", $post_id );
				$delivery_link    = get_field( "skip_the_dishes", $post_id );
				$takeout_menu     = get_field( "takeout_menu", $post_id );
				$phone            = get_field( "phone", $post_id );
				$xdineapp         = get_field( "xdineapp", $post_id );
				$open             = get_field( "open", $post_id );
				$region_menu_link = get_field( "region_menu_link", $post_id );

				?>

                <div class="storelocator__store" href="<?php echo get_permalink( $post_id ); ?>">
                    <div class="storelocator__store-top">
						<?php if ( $open == 'Closed' ): ?>
                            <p class="storelocator__store-headline--closed">
								<?php echo get_the_title( $post_id ); ?> (Closed)
                            </p>
						<?php else: ?>
                            <a class="storelocator__store-headline" href="<?php echo get_permalink( $post_id ); ?>">
								<?php echo get_the_title( $post_id ); ?>
                            </a>
						<?php endif; ?>

						<?php if ( $open && $open != 'Closed' && $open != 'Open for Takeout/Delivery' ): ?>
							<?php if ( $region_menu_link || $open == 'OPENING SOON' ): ?>
                                <span class="storelocator__store-headline-tag">
                                            <?php if ( $open !== 'Hide Button' ): echo $open; ?>
                                            <?php endif; ?>
                                        </span>
							<?php endif; ?>
						<?php endif; ?>
                    </div>
                    <!--div-->
					<?php /*if(isset($address["address"])):*/ ?>
					<?php /*echo $address['address'];*/ ?>
					<?php /*endif;*/ ?>
                    <!--/div-->

					<?php if ( $open != 'Closed' ): ?>
                        <div>
                            <p class="storelocator__store-link-container">
								<?php if ( $phone ) : ?>
                                    <a class="storelocator__store-link"
                                       href="tel:<?php echo $phone; ?>"><?php echo $phone; ?></a>
								<?php endif; ?>
                            </p>
                            <p class="storelocator__store-link-container">
								<?php if ( $xdineapp ): ?>
                                    <a class="storelocator__store-link" target="_blank"
                                       href="<?= $xdineapp ?>"><?php if ( get_field( 'take_out_or_delivery', $post_id ) ) { ?>Order for Pickup <?php } else { ?>Order for Pickup<?php } ?></a>
								<?php elseif ( $takeout_menu ): ?>
                                    <a class="storelocator__store-link" target="_blank"
                                       href="<?= $takeout_menu ?>"><?php if ( get_field( 'take_out_or_delivery', $post_id ) ) { ?>Order for Pickup <?php } else { ?>Takeout Menu (phone us + pickup)<?php } ?></a>
								<?php elseif ( $open != 'OPENING SOON' ) : ?>
                                    <a class="storelocator__store-link" target="_blank"
                                       href="<?= site_url() . '/menus?location=' . $post->post_name; ?>"> <?php if ( get_field( 'take_out_or_delivery', $post_id ) ) { ?>Order for Pickup or Delivery<?php } else { ?>Takeout Menu (phone us + pickup)<?php } ?>
                                    </a>
								<?php endif; ?>
                            </p>

							<?php if ( $delivery_link ): ?>
                                <p class="storelocator__store-link-container">
                                    <a class="storelocator__store-link" target="_blank" href="<?= $delivery_link ?>">Order
                                        for Delivery</a>
                                </p>
							<?php endif; ?>



							<?php if ( $open && $open != 'Open for Takeout/Delivery' && $region_menu_link ): ?>
                                <p class="storelocator__store-link-container">
                                    <a class="storelocator__store-link" href="<?php echo $region_menu_link; ?>">See
                                        Dine-in Menu</a>
                                </p>
							<?php endif; ?>

							<?php if ( $open && $open != 'Open for Takeout/Delivery' && $open_table_id ): ?>
                                <p class="storelocator__store-link-container">
                                    <a class="storelocator__store-link storelocator__store-opentable"
                                       data-opentableid="<?= $open_table_id ?>"
                                       data-featherlight-variant="single-restaurant__featherlight"
                                       data-featherlight=".storelocator__opentable">Reservations</a>
                                </p>
							<?php endif; ?>
                        </div>
					<?php endif; ?>
                </div>

			<?php
			endforeach;

			echo '</div>';

		endforeach;
		?>

    </div>

</div>

<div class="storelocator__opentable">

</div>