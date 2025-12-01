<?php

if(isset($_GET['location'])):
    $default_restaurant_slug = $_GET['location'];
    echo '<script>var defaultRestaurant = "'.$default_restaurant_slug.'";</script>';
else :
    $default_restaurant_slug = "";
    echo '<script>var defaultRestaurant = "";</script>';
endif;

if (isset($page_slug)) {
    $page = get_page_by_path($page_slug);
} else {
    $page = false;
}

if ($page) {
    $default_restaurant_id = $page->ID;
} else {
    $default_restaurant_id = false;
}

$default_restaurant_regions = wp_get_post_terms( $default_restaurant_id, 'regions' );
if($default_restaurant_regions) :
    $default_restaurant_region_slug = $default_restaurant_regions[0]->slug;
else :
    $default_restaurant_region_slug = "";
endif;

$default_restaurant_provinces = wp_get_post_terms( $default_restaurant_id, 'provinces' ); 

if($default_restaurant_provinces) :
    $default_restaurant_province_id = $default_restaurant_provinces[0]->ID;
    $default_restaurant_province_slug = $default_restaurant_provinces[0]->slug;
else :
    $default_restaurant_province_id = "";
    $default_restaurant_province_slug = "";
endif;

$default_menu = get_sub_field('default_menu');
$default_menu_id = $default_menu[0]->term_id;
$default_menu_name = $default_menu[0]->name;

?>

<?php $menu_background = get_field('background_image') ? get_field('background_image') : get_field('body_background_image', 'options');?>

<div class="menu lazyload" <?php echo lazy_background(
        $menu_background
    ); ?>>

    <div class="menu__container">
        <div class="menu__navigation-container">

            <div class="menu__navigation">

                <div class="custom-select" style="display: none;">
                    <select class="menu-province">

                        <option class="placeholder">Select a province...</option>

                        <?php 

                            $provinces = get_terms([
                                'taxonomy' => 'provinces',
                                'parent' => '0',
                                'hide_empty' => true,
                            ]);

                            foreach($provinces as $province):

                                if($province->slug == $default_restaurant_province_slug): 
                                    $selected = 'selected';
                                else :
                                    $selected = '';
                                endif;
                                
                                echo '<option data-value="'.$province->slug.'" '.$selected.'>'.$province->name.'</option>';

                            endforeach;

                        ?>
                    </select>
                </div>

                <div class="custom-select" style="display: none;">
                    <div class="custom-select-restaurant">
                        <select class="menu-restaurant">

                            <option class="placeholder">Select a restaurant...</option>

                            <?php

                                $args = [
                                    'element'       => 'option',
                                    'query'         => [
                                        'post_type' => 'restaurant',
                                        'posts_per_page' => -1,
                                        'meta_query'	=> array(
                                            'relation'		=> 'OR',
                                            array(
                                                'key'	  	=> 'closed',
                                                'value'	  	=> true,
                                                'compare' 	=> '!=',
                                            ),
                                            array(
                                                'key'	  	=> 'closed',
                                                'compare' 	=> 'NOT EXISTS',
                                            ),
                                        ),
                                    ],
                                    'props'         => [
                                        'default_restaurant_name' => $default_restaurant_slug,
                                        'default_restaurant_province' => $default_restaurant_province_slug
                                    ]
                                ];

                                queryPosts($args);
                            ?>

                        </select>
                    </div>
                </div>

                <div class="custom-select">

                    <select class="menu-type">

                        <optgroup label="Food">

                            <?php
                                // Get default menus from site options
                                $default_menus = get_field('menus', 'options');

                                // Get selected menu categories
                                $menus = get_terms( array(
                                    'taxonomy' => 'menu',
                                    'hide_empty' => true,
                                ));

                                foreach($menus as $menu):
                                
                                    
                                                                        
                                    // Set srcset for banner if exists
                                    $banner = get_field('banner_image', $menu);

                                    if($banner):
                                        $srcset = get_lazy_background($banner);
                                    else:
                                        $srcset = '';
                                    endif;

                                    //Select default menu on page load
                                    if($menu->slug == $default_menu[0]->slug):
                                        $selected = 'selected';
                                    else :
                                        $selected = '';
                                    endif;

                                    // Hide all options which are not set on default (to allow special menus)
                                    if(in_array($menu->term_id, $default_menus)):
                                        $class = '';
                                        $disabled = '';
                                    else :
                                        $class = ' is-hidden';
                                        $disabled = ' disabled="disabled"';
                                    endif;

                                    echo '<option data-'.$srcset.' class="food'.$class.'" data-description="'.$menu->description.'" data-value="'.$menu->term_id.'" value="'.$menu->term_id.'" '. $selected . $disabled .'>' .$menu->name. '</option>';

                                endforeach;
                            ?>

                        </optgroup>

                        <optgroup label="Drinks">

                            <?php

                            // Get default menus from site options
                            $default_menus_drinks = get_field('menus_drinks', 'options');
                        
                            // Get selected menu categories
                            $menus = get_terms( array(
                                'taxonomy' => 'menu_drinks',
                                'hide_empty' => true,
                                'parent' => 0

                            ));

                            foreach($menus as $menu):

                                // Set srcset for banner if exists
                                $banner = get_field('banner_image', $menu);

                                if($banner):
                                    $srcset = get_lazy_background($banner);
                                else:
                                    $srcset = '';
                                endif;

                                //Select default menu on page load
                                if($menu->slug == $default_menu[0]->slug):
                                    $selected = 'selected';
                                else :
                                    $selected = '';
                                endif;

                                // Hide all options which are not set on default (to allow special menus)
                                if(in_array($menu->term_id, $default_menus_drinks)):
                                    $class= '';
                                    $disabled = '';
                                else :
                                    $class= ' is-hidden';
                                    $disabled = ' disabled="disabled"';
                                endif;

                                echo '<option data-'.$srcset.' class="drink'.$class.'" data-description="'.$menu->description.'" 
                                data-value="'.$menu->term_id.'" value="'.$menu->term_id.'" '. $selected . $disabled . '>' .$menu->name. '</option>';

                            endforeach;
                        ?>

                        </optgroup>

                    </select>
                </div>
            </div>
        </div>
    </div>






    <div class="menu__container">
        <div class="menu__grunge menu__grunge--top">
            <?php echo svg("grunge/15-menu+inside-pages-header-right"); ?>
        </div>
        <div class="menu__grunge menu__grunge--left">
            <?php echo svg("grunge/13-menu-left"); ?>
        </div>
        <div class="menu__grunge menu__grunge--center">
            <?php echo svg("grunge/14-menu-centre"); ?>
        </div>
        <div class="menu__grunge menu__grunge--right">
            <?php echo svg("grunge/16-menu-right"); ?>
        </div>
        <?php 
            $get_parent_cats = array(
                'taxonomy' => array('menu', 'menu_drinks'),
                'parent' => '0' //get top level categories only
            ); 

            //get parent categories 
            $all_categories = get_categories( $get_parent_cats );

            foreach( $all_categories as $single_category ){
                //for each category, get the ID
                $catID = $single_category->cat_ID;
                $taxonomy = $single_category->taxonomy;
                $menu_image = get_field('image', $single_category);

                $class = '';

                if($catID != $default_menu_id):
                    $class = 'is-hidden';
                endif;

                if($menu_image):
                    $class .= ' menu__menu-container--has-image';
                endif;
                
                ?>

        <div class="menu__menu-container <?php echo $class; ?>" data-value="<?php echo $catID; ?>">

            <div class="menu__menu">

                <div class="menu__menu-header">

                    <div class="menu__title">

                        <div class="headline__container ">
                            <h2 class="headline"><?php echo $single_category->name; ?></h2>
                            <h3 class="menu__description"><?php echo $single_category->description; ?></h3>
                        </div>

                        <div class="menu__placeholder">

                            <?php if(!$default_restaurant_id): ?>

                            <div class="menu__select-cta">
                                <p>Select your <span>MR Mikes</span> restaurant to see prices</p>
                            </div>

                            <?php endif; ?>

                        </div>

                    </div>

                </div>

                <div class="menu__menu-body">

                    <?php

                            $children = get_term_children($catID, $taxonomy);


                            if(!$children) {
                                
                                $args = [
                                    'element'       => 'dish',

                                    'query'         => [
                                        'post_type' => array('dish', 'drinks'),
                                        'posts_per_page'=> -1,
                                        'tax_query' => array(
                                            array (
                                                'taxonomy' => $taxonomy,
                                                'field' => 'term_id',
                                                'terms' => $catID
                                            )
                                        )
                                    ],

                                    'props'         => [
                                        'taxonomy' => $taxonomy
                                    ]
                                ];

                                queryPosts($args);
                            }

                            $get_children_cats = array(
                                'taxonomy' => $taxonomy,
                                'child_of' => $catID //get children of this parent using the catID variable from earlier
                            );

                            $child_cats = get_categories( $get_children_cats );//get children of parent category

                            foreach( $child_cats as $child_cat ) :
                                //for each child category, get the ID
                                $childID = $child_cat->cat_ID; ?>

                    <div class="menu__menu-subheadline">
                        <div class="menu__title">
                            <?php 
                                            element('headline', [
                                                'text' => $child_cat->name,
                                                'style' => 'h3'
                                            ]);
                                        ?>
                            <div class="menu__placeholder"></div>

                        </div>
                    </div>

                    <?php

                                $args = [
                                    'element'       => 'dish',

                                    'query'         => [
                                        'post_type' => array('dish', 'drinks'),
                                        'posts_per_page'=> -1,
                                        'tax_query' => array(
                                            array (
                                                'taxonomy' => $taxonomy,
                                                'field' => 'term_id',
                                                'terms' => $childID
                                            )
                                        )
                                    ],

                                    'props'         => [
                                        'taxonomy' => $taxonomy
                                    ]
                                ];

                                queryPosts($args);


                                ?>




                    <?php endforeach; ?>

                    <?php
                    $footnote = get_field('footnote', $single_category->taxonomy . "_" . $single_category->term_id);
          
                    if ($footnote):
                ?>
                    <div class="dish">
                        <p><strong><?= $footnote; ?></strong></p>
                    </div>

                    <?php endif; ?>
                </div>


            </div>

            <?php if($menu_image): ?>
            <div class="menu__image-container">
                <div class="menu__image lazyload"
                    style="background-image: url('<?php echo wp_get_attachment_image_url($menu_image, 'large')?>')">
                </div>
            </div>
            <?php endif;  ?>


        </div>
        <?php  } ?>

        <?php $nute_title = get_field('nutritional_information_title', 'option'); ?>
        <?php $nute_file = get_field('nutritional_information_pdf', 'option'); ?>

        <?php $gf_title = get_field('glutenfree_menu_title', 'option'); ?>
        <?php $gf_file = get_field('glutenfree_menu_pdf', 'option'); ?>

        <?php if (($nute_title && $nute_file) || ($gf_title && $gf_file)): ?>

        <div class="menu__menu-footer">

            <div class="menu__title">

                <div class="menu__menu-footer-container">

                    <?php if ($nute_title && $nute_file): ?>
                    <a href="<?php echo $nute_file; ?>" class="nutritional-info__link" download="download">
                        <?php echo $nute_title; ?>
                    </a>
                    <?php endif; ?>

                    <?php if ($gf_title && $gf_file): ?>
                    <a href="<?php echo $gf_file; ?>" class="nutritional-info__link" download="download">
                        <?php echo $gf_title; ?>
                    </a>
                    <?php endif; ?>

                </div>

            </div>
        </div>

        <?php endif; ?>
    </div>

</div>