<!-- update -->
<?php
element( 'header' );

$location = get_field( 'address' );

// Generating address string from address segments
$address_string = '';
$override_address = get_field( 'override_address' );
if ( $override_address ) {
    $address_string = get_field( 'raw_address' );
} else if ( ! empty( $location ) ) {

    // Loop over segments and construct HTML.
    foreach (
        array(
            'street_number',
            'street_name',
            'city',
            'state',
            'post_code',
            'country'
        ) as $arr_key
    ) {
        if ( ! empty( $location[ $arr_key ] ) ) {
            if ($arr_key != 'street_number') {
                $address_string .= $location[ $arr_key ] . ', ';
            } else {
                $address_string .= $location[ $arr_key ] . ' ';
            }
        }
    }

    // Trim trailing comma and space
    $address_string = trim( $address_string, ', ' );

    // If no address string generated, use the address field from ACF Google Maps field
    if ( empty( $address_string ) && ! empty( $location['address'] ) ) {
        $address_string = $location['address'];
    }

}

$sidebar_cta_settings = get_field( 'sidebar_cta' );
if ( ! empty( $sidebar_cta_settings ) ) {
    $show_sidebar_cta = $sidebar_cta_settings['show_cta'];
}
?>

<div class="single-restaurant">

    <?php if ( isset( $location['lat'] ) && isset( $location['lng'] ) ): ?>
        <input type="hidden" class="latitude" value="<?php echo $location['lat']; ?>"/>
        <input type="hidden" class="longitude" value="<?php echo $location['lng']; ?>"/>

        <div id="single-restaurant" class="single-restaurant__map">
            <div id="map"></div>
        </div>

    <?php endif; ?>

    <div class="single-restaurant__content">

        <div class="single-restaurant__container">

            <?php if ( get_field( "open" ) && get_field( "open" ) != 'Closed' && get_field( 'open' ) != 'Open for Takeout/Delivery' && get_field( 'open' ) != 'Hide Button' && get_field( "region_menu_link" ) ): ?>
                <span class="single-restaurant__headline-tag">
                    <?php echo get_field( "open" ); ?>
                </span>
            <?php endif; ?>
            <h1 class="headline">
                Visit MR MIKES <?php the_title(); ?><?php if ( get_field( "open" ) == 'Closed' ): echo " (Closed)"; endif; ?>
            </h1>
        </div>

        <div class="single-restaurant__container">
            <div class="single-restaurant__column1">
                <div class="single-restaurant__general">

                    <?php if ( ! empty( $address_string ) ) : ?>
                        <strong>
                            <?php esc_html_e( 'Address', 'mrmikes' ); ?>
                        </strong>
                        <p>
                            <?php echo esc_html( $address_string ); ?>
                        </p>
                    <?php endif; ?>

                    <?php if ( get_field( 'email' ) or get_field( 'phone' ) ) : ?>
                        <strong>Contact</strong>
                        <p>
                            <?php if ( get_field( 'email' ) ) : ?>
                                Email: <a
                                        href="mailto:<?php the_field( 'email' ); ?>"><?php the_field( 'email' ); ?></a>
                                <br/>
                            <?php endif; ?>

                            <?php if ( get_field( 'phone' ) ) : ?>
                                Phone: <a href="tel:<?php the_field( 'phone' ); ?>"><?php the_field( 'phone' ); ?></a>
                            <?php endif; ?>
                        </p>
                    <?php endif; ?>

                    <?php if (get_field( 'headline' )) : ?>
                    <h2 class='sub-headline'>
                        <?php the_field( 'headline' );?>
                    </h2>
                    <?php endif; ?>


                    <?php

                    $description = get_field( 'description' );

                    if ( ! $description ) {
                        $description = get_field( 'description', 'options' );
                    }

                    $title     = get_the_title();
                    $provinces = wp_get_post_terms( get_the_ID(), 'provinces' );
                    $province  = $provinces[0]->name;

                    $description = str_replace( '{{title}}', $title, $description );
                    $description = str_replace( '{{province}}', $province, $description );

                    echo '<p>' . $description . '</p>';

                    ?>
                </div>

                <div class="single-restaurant__buttons">

                    <?php if ( get_field( "open" ) && get_field( "open" ) != "OPENING SOON" ): ?>
                        <?php if ( get_field('location_status') != 'opening_soon' ): ?>
                            <div class="button__container btn-black <?php if ( get_field( 'open' ) == 'Closed' || get_field( 'open' ) == 'Open for Takeout/Delivery' ): echo "btn-deactivated"; endif; ?>">
                                <a href="<?php echo get_permalink( get_the_ID() ); ?>/menus/" class="button">
                                    See Dine-in Menu
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <?php
                        $breakfast_menu = get_field( "breakfast_menu" );
                        if ( get_field('location_status') != 'opening_soon' && $breakfast_menu ): ?>
                            <div class="button__container btn-black <?php if ( get_field( 'open' ) == 'Closed' || get_field( 'open' ) == 'Open for Takeout/Delivery' ): echo "btn-deactivated"; endif; ?>">
                                <a href="<?php echo $breakfast_menu; ?>" target="_blank" class="button">
                                    See Breakfast Menu
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if ( get_field( 'open_table_id' ) && get_field( 'open' ) != 'Closed' && get_field( 'open' ) != 'Open for Takeout/Delivery' ): ?>
                            <div class="button__container btn-black">
                                <a class="button" data-featherlight=".single-restaurant__open-table"
                                   data-featherlight-variant="single-restaurant__featherlight">
                                    Make a Reservation
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if ( get_field( "xdineapp" ) ): ?>
                            <div class="button__container btn-black <?php if ( get_field( 'open' ) == 'Closed' ): echo "btn-deactivated"; endif; ?>">
                                <a href="<?php the_field( 'xdineapp' ) ?>" target="_blank" class="button">
                                    Order for Takeout
                                </a>
                            </div>
                        <?php elseif ( get_field( 'takeout_menu' ) ): ?>
                            <div class="button__container btn-black <?php if ( get_field( 'open' ) == 'Closed' ): echo "btn-deactivated"; endif; ?>">
                                <a href="<?php the_field( 'takeout_menu' ) ?>" target="_blank" class="button">
                                    <?php if ( get_field( 'take_out_or_delivery' ) ) { ?>Order for Take Out<?php } else { ?>Takeout Menu (phone us + pickup)<?php } ?>
                                </a>
                            </div>
                        <?php else: ?>
                            <?php element( 'button', [
                                'class'  => get_field( 'open' ) == 'Closed' ? "btn-black btn-deactivated" : "show-menu btn-black",
                                'button' => 'Takeout Menu (phone us + pickup)',
                                'link'   => site_url() . '/menus?location=' . $post->post_name
                            ] ); ?>
                        <?php endif; ?>

                        <?php if ( get_field( 'skip_the_dishes' ) ): ?>
                            <div class="button__container btn-black <?php if ( get_field( 'open' ) == 'Closed' ): echo "btn-deactivated"; endif; ?>">
                                <a href="<?php the_field( 'skip_the_dishes' ) ?>" target="_blank" class="button">
                                    Order for Delivery
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php /*element( 'button', [
                        'class'  => 'back-to-locations btn-black',
                        'button' => 'Back to location list',
                        'link'   => site_url() . '/locations/'
                    ] );*/ ?>

                </div>
            </div>

            <div class="single-restaurant__column2">

                <?php if ( get_field( 'open' ) != 'Closed' ): ?>
                    <div class="single-restaurant__hours">
                        <strong>Hours</strong>

                        <?php
                        $hours         = get_field( 'opening_hours' );
                        $hours_options = get_field( 'opening_hours', 'options' );

                        if ( $hours['mo_from'] ) : $mo_from = $hours['mo_from'];
                        else : $mo_from = $hours_options['mo_from']; endif;
                        if ( $hours['mo_to'] ) : $mo_to = $hours['mo_to'];
                        else : $mo_to = $hours_options['mo_to']; endif;
                        if ( $hours['mo_closed'] ): $mo = "Closed";
                        else : $mo = $mo_from . " - " . $mo_to; endif;

                        if ( $hours['tu_from'] ) : $tu_from = $hours['tu_from'];
                        else : $tu_from = $hours_options['tu_from']; endif;
                        if ( $hours['tu_to'] ) : $tu_to = $hours['tu_to'];
                        else : $tu_to = $hours_options['tu_to']; endif;
                        if ( $hours['tu_closed'] ): $tu = "Closed";
                        else : $tu = $tu_from . " - " . $tu_to; endif;

                        if ( $hours['we_from'] ) : $we_from = $hours['we_from'];
                        else : $we_from = $hours_options['we_from']; endif;
                        if ( $hours['we_to'] ) : $we_to = $hours['we_to'];
                        else : $we_to = $hours_options['we_to']; endif;
                        if ( $hours['we_closed'] ): $we = "Closed";
                        else : $we = $we_from . " - " . $we_to; endif;

                        if ( $hours['th_from'] ) : $th_from = $hours['th_from'];
                        else : $th_from = $hours_options['th_from']; endif;
                        if ( $hours['th_to'] ) : $th_to = $hours['th_to'];
                        else : $th_to = $hours_options['th_to']; endif;
                        if ( $hours['th_closed'] ): $th = "Closed";
                        else : $th = $th_from . " - " . $th_to; endif;

                        if ( $hours['fr_from'] ) : $fr_from = $hours['fr_from'];
                        else : $fr_from = $hours_options['fr_from']; endif;
                        if ( $hours['fr_to'] ) : $fr_to = $hours['fr_to'];
                        else : $fr_to = $hours_options['fr_to']; endif;
                        if ( $hours['fr_closed'] ): $fr = "Closed";
                        else : $fr = $fr_from . " - " . $fr_to; endif;

                        if ( $hours['sa_from'] ) : $sa_from = $hours['sa_from'];
                        else : $sa_from = $hours_options['sa_from']; endif;
                        if ( $hours['sa_to'] ) : $sa_to = $hours['sa_to'];
                        else : $sa_to = $hours_options['sa_to']; endif;
                        if ( $hours['sa_closed'] ): $sa = "Closed";
                        else : $sa = $sa_from . " - " . $sa_to; endif;

                        if ( $hours['su_from'] ) : $su_from = $hours['su_from'];
                        else : $su_from = $hours_options['su_from']; endif;
                        if ( $hours['su_to'] ) : $su_to = $hours['su_to'];
                        else : $su_to = $hours_options['su_to']; endif;
                        if ( $hours['su_closed'] ): $su = "Closed";
                        else : $su = $su_from . " - " . $su_to; endif;

                        echo "<table>";
                        echo "<tr>";
                        echo "<td>" . $mo . "</td>";
                        echo "<td>Monday</td>";
                        echo "</tr>";

                        echo "<tr>";
                        echo "<td>" . $tu . "</td>";
                        echo "<td>Tuesday</td>";
                        echo "</tr>";

                        echo "<tr>";
                        echo "<td>" . $we . "</td>";
                        echo "<td>Wednesday</td>";
                        echo "</tr>";

                        echo "<tr>";
                        echo "<td>" . $th . "</td>";
                        echo "<td>Thursday</td>";
                        echo "</tr>";

                        echo "<tr>";
                        echo "<td>" . $fr . "</td>";
                        echo "<td>Friday</td>";
                        echo "</tr>";

                        echo "<tr>";
                        echo "<td>" . $sa . "</td>";
                        echo "<td>Saturday</td>";
                        echo "</tr>";

                        echo "<tr>";
                        echo "<td>" . $su . "</td>";
                        echo "<td>Sunday</td>";
                        echo "</tr>";

                        echo "</table>";

                        ?>
                    </div>
                <?php endif; ?>

                <div class="single-restaurant__social-media">

                    <?php
                    $social = get_field( 'social_media' );

                    if ( $social['facebook'] or $social['instagram'] ):

                        echo '<div>Follow this location:</div>';

                        if ( $social['facebook'] ):
                            echo '<a href="' . $social['facebook'] . '" target="_blank">' . svg( 'icon_fb' ) . '</a>';
                        endif;

                        if ( $social['instagram'] ):
                            echo '<a href="' . $social['instagram'] . '" target="_blank">' . svg( 'icon_instagram' ) . '</a>';
                        endif;

                    endif;

                    ?>
                </div>

                <?php if (get_field( 'chat_meter' )) : ?>
                <div class="single-restaurant__chatmeter">
                    <?php echo get_field( 'chat_meter' ); ?>
                </div>
                <?php endif; ?>

                <?php if ( ! empty( $show_sidebar_cta ) ):
                    $sidebar_cta_text = $sidebar_cta_settings['cta_text'];
                    $sidebar_cta_link = $sidebar_cta_settings['cta_link'];
                    ?>
                    <div class="single-restaurant__cta">
                        <span>
                            <?php if ( ! empty( $sidebar_cta_text ) ) : ?>
                                <?php echo esc_html( $sidebar_cta_text ); ?><br>
                            <?php endif; ?>
                            <?php if ( ! empty( $sidebar_cta_link ) ) :

                                $sidebar_cta_link_url = $sidebar_cta_link['url'];
                                $sidebar_cta_link_title = $sidebar_cta_link['title'];
                                $sidebar_cta_link_target = $sidebar_cta_link['target'] ?: '_self';

                                ?>
                                <a href="<?php echo esc_url( $sidebar_cta_link_url ) ?>"
                                   title="<?php echo esc_attr( $sidebar_cta_link_title ) ?>"
                                   target="<?php echo esc_attr( $sidebar_cta_link_target ) ?>"
                                >
                                    <?php echo esc_html( $sidebar_cta_link_title ) ?>
                                </a>
                            <?php endif; ?>
                        </span>
                    </div>
                <?php endif; ?>

            </div>

        </div>

        <div class="single-restaurant__container">
            <div class="single-restaurant__image">

                <?php
                if ( get_field( 'store_details_image' ) ):
                    $image_id = get_field( 'store_details_image' );
                else :
                    $image_id = get_field( 'store_details_image', 'options' );
                endif;
                ?>

                <img class="lazyload"<?php echo " " . mrm_get_lazy_loaded_image_attrs( $image_id ); ?> />
            </div>
        </div>
    </div>
</div>

<?php if ( get_field( 'open_table_id' ) ): ?>

    <div class="single-restaurant__open-table">
        <iframe
                src="https://www.opentable.ca/widget/reservation/canvas?rid=<?php the_field( 'open_table_id' ); ?>&amp;type=standard&amp;theme=tall&amp;overlay=false&amp;domain=ca&amp;lang=en-CA&amp;r3abvariant=false&amp;r3uid=Xk-13oE9Uk&amp;newtab=false&amp;disablega=false"
                width="288" height="490" tabindex="-1"></iframe>
    </div>

<?php endif; ?>

<?php element( 'footer' ); ?>