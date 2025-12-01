
<div class="restaurant restaurantWidget restaurantWidget--skip">

    <div class="restaurantWidget__header">
        <div class="restaurantWidget__headline">
            Delivery
        </div>
        <div class="restaurantWidget__mrm">
            <img src="<?php echo get_template_directory_uri() . '/static/images/mrm_white.png'; ?>" alt="Mr Mikes Logo" />
        </div>
    </div>

    <div class="listFilter">

        <div class="restaurantWidget__search-container">
            <div class="restaurantWidget__input-container">
                <?php echo svg('magnifier'); ?>
                <input class="search" placeholder="Search by city or province" />
            </div>
        </div>

        <ul class="restaurantWidget__list list">

            <?php
            $args = [
                'post_type' =>  'restaurant',
                'posts_per_page' => -1,
                'orderby' => 'title',
                'order' => 'ASC',
                'meta_query'	=> array(
                    array(
                        'key'	  	=> 'skip_the_dishes',
                        'value'	  	=> '',
                        'compare' 	=> '>',
                    ),
                )
            ];

            $postslist = get_posts( $args );
            $open_table_ids = [];

            foreach ($postslist as $post) :  
                setup_postdata($post);
                $post_id = $post->ID;
                $title = get_the_title($post_id);
                $address = get_field('address', $post_id);
                $link = get_field('skip_the_dishes', $post_id);
                $open = get_field('open', $post_id);
                $terms = get_the_terms( $post_id, 'provinces' );

                if (!is_wp_error($terms) && $open != 'closed') {
                    $term = array_pop($terms);
                    ?>
                    <li class="restaurantWidget__link-container">
                        <a class="restaurantWidget__link" href="<?php echo $link; ?>" target="_blank">
                            <div class="restaurantWidget__marker">
                                <?php echo svg('marker'); ?>
                            </div>
                            <div class="restaurantWidget__content">
                                <span class="restaurantWidget__title place"><?php echo $title; ?></span>
                                <p class="address"><?php echo $address['address']; ?></p>
                                <span class="province"><?php if(!empty($terms)): echo $terms[0]->name; endif; ?></span>
                            </div>
                        </a>
                    </li>
                    <?php
                }
            endforeach;
            ?>

        </ul>
    </div>
</div>