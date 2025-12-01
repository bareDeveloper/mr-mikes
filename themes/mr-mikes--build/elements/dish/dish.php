<?php
global $post;
global $default_restaurant_region_slug;

// Get menu categories
$menus = wp_get_post_terms(get_the_id(), $taxonomy);
$menu_categories = '';

foreach($menus as $menu):
    $menu_categories .= 'data-category-' . $menu->term_id . ' ';
endforeach;


// Get regions
$params = '';
$default_price = '';

if( have_rows('regions') ):
    while ( have_rows('regions') ) : the_row();

        $region_object = get_sub_field('region');
        $region_slug = $region_object[0]->slug;

        // Hide dishes without price (they are hidden without params)
        $subitems = get_sub_field('subitems');
        if(get_sub_field('price') OR $subitems[0]['price'] > "" OR get_field('download')){
            // Save data attributes into variable
            $params .= ' data-' . $region_slug . '="' . get_sub_field('price') . '"';
        }

        //Get default price by default region (set in the menu module)
        if(
            isset($default_restaurant_region_slug) 
            && 
            $region_slug == $default_restaurant_region_slug
        ):
            $default_price = get_sub_field('price');
        endif;

    endwhile;

endif; ?>

<div class="dish" <?php echo $menu_categories; ?> <?php echo $params; ?>>

    <div class="dish__flags">
        <?php
        $flags = get_field('flag');

        if($flags):

            foreach($flags as $flag):

                if($flag == 'new'):
                    echo '<span class="dish__new">'.svg('new').'</span>';
                endif;


                if($flag == 'm'):
                    echo '<span class="dish__m">'.svg('m').'</span>';
                endif;
                
                if($flag == 'vegetarian'):
                    echo '<span class="dish__veggie">'.svg('veggie').'</span>';
                endif;

            endforeach;

        endif;
        ?>
    </div>

    <div class="dish__header">

        <?php element('headline', [
            'text' => get_the_title(),
            'style' => 'h3'
        ]); ?>

        <div class="dish__price">
            <?php echo $default_price; ?>
        </div>

    </div>

    <div class="dish__description">
        <?php the_field('description'); ?>

        <?php
            $download = get_field('download');
            if($download):
                echo '<p class="dish__download"><a href="'.wp_get_attachment_url($download).'" target="_blank">Show menu'.svg('open').'</a></p>';
            endif;
        ?>
    </div>

    <?php if ( have_rows('regions') ) : ?>
        <?php while( have_rows('regions') ) : the_row(); ?>
            <?php 
            $region_object = get_sub_field('region');
            $region_slug = $region_object[0]->slug;

            if(
                isset($default_restaurant_region_slug)
                &&
                $default_restaurant_region_slug == $region_slug
            ){
                $class = "";
            }else{
                $class = " is-hidden";
            }

            if (have_rows('subitems')) : ?>
                <?php while( have_rows('subitems') ) : the_row(); ?>
                    <?php if(get_sub_field('price')) :?>
                        <div class="dish__price-subitem is-hidden" data-region="<?php echo $region_slug; ?>">
                            <div><?php the_sub_field('title'); ?></div>
                            <div><?php the_sub_field('price'); ?></div>
                        </div>
                    <?php endif; ?>
                <?php endwhile; ?>
            <?php endif; ?>
        <?php endwhile; ?>
    <?php endif; ?>

</div> 