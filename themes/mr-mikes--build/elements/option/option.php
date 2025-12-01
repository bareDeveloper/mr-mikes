<?php

global $post; 

// Get regions for food
$regions = wp_get_post_terms( get_the_ID(), 'regions' );
if($regions):
    $region_slug = $regions[0]->slug;
else: 
    $region_slug = '';
endif;

// Get regions for drinks
$regions_drinks = wp_get_post_terms( get_the_ID(), 'regions_drinks' );
if($regions_drinks):
    $region_drinks_slug = $regions_drinks[0]->slug;
else: 
    $region_drinks_slug = '';
endif;

// Get provinces
$provinces = wp_get_post_terms( get_the_ID(), 'provinces' );
if($provinces):
    $province_slug = $provinces[0]->slug;
else: 
    $province_slug = '';
endif;

// if($province_slug != $default_restaurant_province):
//     $class = 'is-hidden';
// else :
//     $class = '';
// endif;


// Set selected attribute
if($post->post_name == $default_restaurant_name) :
    $selected = 'selected';
else :
    $selected = '';
endif;

// Get food menus for restaurant. If field is empty grab the menus from the site options
$food_menus = get_field('menus');
if(!$food_menus):
    $food_menus = get_field('menus', 'options');
endif;
$food_menus = implode(" ",$food_menus);

// Get drink menus for restaurant. If field is empty grab the menus from the site options
$drink_menus = get_field('menus_drinks');
if(!$drink_menus):
    $drink_menus = get_field('menus_drinks', 'options');
endif;
$drink_menus = implode(" ",$drink_menus);

?>

<option class="<?php //echo $class; ?>" 
        data-province="<?php echo $province_slug; ?>" 
        data-region="<?php echo $region_slug; ?>" 
        data-region-drinks="<?php echo $region_drinks_slug; ?>"
        data-menu-food="<?php echo $food_menus; ?>"
        data-menu-drinks="<?php echo $drink_menus; ?>"
        <?php echo $selected; ?>
>
    <?php echo get_the_title(); ?>
</option>