<?php
/**
 * Template Name: MrMikes Menu Template
 * Description: Custom template for MrMikes Menu pages
 */

// Handle location query parameter first
$location_slug_from_url = isset($_GET['location']) ? sanitize_title($_GET['location']) : '';
$menu_type_from_url = isset($_GET['menu_type']) ? sanitize_text_field($_GET['menu_type']) : '';

// Normalize menu type (handle both 'drink' and 'drinks')
if ($menu_type_from_url === 'drink') {
    $menu_type_from_url = 'drinks';
}

if (!empty($location_slug_from_url)) {
    // Find restaurant by slug
    $restaurant_by_slug = get_posts(array(
        'post_type' => 'restaurant',
        'post_status' => 'publish',
        'name' => $location_slug_from_url,
        'numberposts' => 1
    ));

    if (!empty($restaurant_by_slug)) {
        // Found restaurant by slug, set the cookie
        $location_id = $restaurant_by_slug[0]->ID;

        // Set cookie with location ID (expires in 10 years)
        $expirationDate = time() + (365 * 10 * 24 * 60 * 60); // 10 years
        setcookie('mrmikes_selected_location', $location_id, $expirationDate, '/');

        // Also set it for immediate use in this request
        $_COOKIE['mrmikes_selected_location'] = $location_id;
    } else {
        // Try to find by matching cleaned post title
        $all_restaurants = get_posts(array(
            'post_type' => 'restaurant',
            'post_status' => 'publish',
            'numberposts' => -1
        ));

        foreach ($all_restaurants as $restaurant) {
            // Create slug from post title (same logic as WordPress does)
            $restaurant_slug = sanitize_title($restaurant->post_title);

            // Also try cleaning the title by removing province abbreviations
            $cleaned_title = preg_replace('/,?\s*[A-Z]{2}$/i', '', trim($restaurant->post_title));
            $cleaned_slug = sanitize_title($cleaned_title);

            if ($restaurant_slug === $location_slug_from_url || $cleaned_slug === $location_slug_from_url) {
                $location_id = $restaurant->ID;

                // Set cookie with location ID (expires in 10 years)
                $expirationDate = time() + (365 * 10 * 24 * 60 * 60); // 10 years
                setcookie('mrmikes_selected_location', $location_id, $expirationDate, '/');

                // Also set it for immediate use in this request
                $_COOKIE['mrmikes_selected_location'] = $location_id;
                break;
            }
        }
    }
}

// Get selected location from cookie (now potentially set from URL parameter above)
$selected_location_id = isset($_COOKIE['mrmikes_selected_location']) ? intval($_COOKIE['mrmikes_selected_location']) : 0;
$selected_location = null;

if ($selected_location_id) {
    // Get location details from the restaurant custom post type
    $location_post = get_post($selected_location_id);
    if ($location_post && $location_post->post_type === 'restaurant' && $location_post->post_status === 'publish') {
        // Get regions taxonomy for this location
        $regions = wp_get_post_terms($selected_location_id, 'regions');
        $region_name = '';
        if (!empty($regions) && !is_wp_error($regions)) {
            $region_name = $regions[0]->name; // Get first region
        }

        // Get regions_drinks taxonomy for this location
        $regions_drinks = wp_get_post_terms($selected_location_id, 'regions_drinks');
        $region_drinks_name = '';
        if (!empty($regions_drinks) && !is_wp_error($regions_drinks)) {
            $region_drinks_name = $regions_drinks[0]->name; // Get first drinks region
        }

        $province = get_field('province', $location_post->ID);
        $pricing_tier = get_field('pricing_tier', $location_post->ID);

        $selected_location = array(
            'id' => $location_post->ID,
            'name' => $location_post->post_title,
            'region' => $region_name,
            'region_drinks' => $region_drinks_name,
            'province' => $province,
            'pricing_tier' => $pricing_tier,
            'address' => get_field('address', $location_post->ID),
            'phone' => get_field('phone', $location_post->ID),
            'email' => get_field('email', $location_post->ID),
            'meta_title' => get_field('meta_title', $location_post->ID),
            'meta_description' => get_field('meta_description', $location_post->ID),
            'hide_features' => get_field('hide_features', $location_post->ID),
        );
    }
}

// Set custom page title if location is selected
if ($selected_location) {
    // Get the location name and province
    $location_name = trim($selected_location['name']);
    $province = $selected_location['province'];

    // Clean location name by removing province abbreviations if they exist
    $clean_location_name = preg_replace('/,?\s*[A-Z]{2}$/i', '', $location_name);

    // Build the page title based on menu type
    $page_title = $selected_location['meta_title'];
    $meta_description = $selected_location['meta_description'];

    // Hook into Yoast SEO title filter (highest priority)
    add_filter('wpseo_title', function($title) use ($page_title) {
        return $page_title;
    }, 99);

    // Hook into Yoast SEO opengraph title
    add_filter('wpseo_opengraph_title', function($title) use ($page_title) {
        return $page_title;
    }, 99);

    // Hook into Yoast SEO twitter title
    add_filter('wpseo_twitter_title', function($title) use ($page_title) {
        return $page_title;
    }, 99);

    // Fallback hooks for WordPress core and other SEO plugins
    add_filter('wp_title', function($title, $sep) use ($page_title) {
        return $page_title;
    }, 99, 2);

    add_filter('document_title_parts', function($title_parts) use ($page_title) {
        return array('title' => $page_title);
    }, 99);

    add_filter('pre_get_document_title', function($title) use ($page_title) {
        return $page_title;
    }, 99);


    // Hook into Yoast SEO meta description filter
    add_filter('wpseo_metadesc', function($description) use ($meta_description) {
        return $meta_description;
    }, 99);

    // Hook into Yoast SEO opengraph description
    add_filter('wpseo_opengraph_desc', function($description) use ($meta_description) {
        return $meta_description;
    }, 99);

    // Hook into Yoast SEO twitter description
    add_filter('wpseo_twitter_description', function($description) use ($meta_description) {
        return $meta_description;
    }, 99);


}

element('header'); ?>

<div class="menu-hero-section">
    <div class="menu-hero-left">
        <div class="menu-hero-left-content">
            <!-- Location selector for mobile (only shows below 1024px) -->
            <div class="mrmikes-location-selector mrmikes-hero-location-selector">
                <img src="<?php echo esc_url(MRMIKES_PLUGIN_URL . 'assets/img/pin_dark.png'); ?>" alt="Location pin" class="mrmikes-location-pin">
                <a href="#" class="mrmikes-location-trigger">
                    <?php echo $selected_location ? esc_html__('Change your location', 'mrmikes-menu') : esc_html__('Set your location', 'mrmikes-menu'); ?>
                </a>
            </div>

            <?php if ($selected_location): ?>
            <h1 class="location-title">
                MR MIKES <?php echo esc_html($selected_location['name']); ?> DINE-IN MENU
            </h1>

            <?php if (!empty($selected_location['address'])): ?>
                <p class="location-address">
                    <?php
                    if (is_array($selected_location['address'])) {
                        // Format as: Street Number Street Name, City, State, Post Code, Country
                        $address_parts = array();

                        // Add street address
                        if (!empty($selected_location['address']['street_number']) && !empty($selected_location['address']['street_name'])) {
                            $address_parts[] = $selected_location['address']['street_number'] . ' ' . $selected_location['address']['street_name'];
                        }

                        // Add city
                        if (!empty($selected_location['address']['city'])) {
                            $address_parts[] = $selected_location['address']['city'];
                        }

                        // Add state
                        if (!empty($selected_location['address']['state'])) {
                            $address_parts[] = $selected_location['address']['state'];
                        }

                        // Add postal code
                        if (!empty($selected_location['address']['post_code'])) {
                            $address_parts[] = $selected_location['address']['post_code'];
                        }

                        // Add country
                        if (!empty($selected_location['address']['country'])) {
                            $address_parts[] = $selected_location['address']['country'];
                        }

                        echo esc_html(implode(', ', $address_parts));
                    } else {
                        echo esc_html($selected_location['address']);
                    }
                    ?>
                </p>
            <?php endif; ?>

            <div class="location-contact-actions">
                <div class="location-contact-info">
                    <?php if (!empty($selected_location['phone'])): ?>
                        <p class="location-phone">
                            <a href="tel:<?php echo esc_attr($selected_location['phone']); ?>" class="location-phone-link">
                                <?php echo esc_html($selected_location['phone']); ?>
                            </a>
                        </p>
                    <?php endif; ?>

                    <?php if (!empty($selected_location['email'])): ?>
                        <p class="location-email">
                            <a href="mailto:<?php echo strtolower(esc_attr($selected_location['email'])); ?>" class="location-email-link">
                                <?php echo strtolower(esc_html($selected_location['email'])); ?>
                            </a>
                        </p>
                    <?php endif; ?>
                </div>

                <div class="location-actions">
                    <a href="<?php echo esc_url(get_permalink($selected_location['id'])); ?>" class="hours-details-btn">HOURS & DETAILS</a>
                </div>
            </div>

        <?php else: ?>
            <h1 class="location-title">No location selected</h1>
        <?php endif; ?>
        </div>
    </div>
    <div class="menu-hero-right">
        <!-- Right section content will go here -->
    </div>
</div>

<div class="menu-tabs-section">
    <div class="menu-tabs-container">
        <div class="menu-tabs">
            <h2><button class="menu-tab <?php echo (!$menu_type_from_url || $menu_type_from_url === 'food') ? 'active' : ''; ?>">FOOD</button></h2>
            <h2><button class="menu-tab <?php echo ($menu_type_from_url === 'drinks') ? 'active' : ''; ?>">DRINKS</button></h2>
            <h2><button class="menu-tab <?php echo ($menu_type_from_url === 'daily-specials') ? 'active' : ''; ?>">DAILY SPECIALS</button></h2>
        </div>
    </div>
</div>

<!-- Tab Content Wrapper -->
<div class="menu-tab-content">
    <!-- Food Tab Content (default active) -->
    <div class="tab-pane <?php echo (!$menu_type_from_url || $menu_type_from_url === 'food') ? 'active' : ''; ?>" id="food-tab">

        <?php
        // BUILD SORTED SECTIONS ARRAY FOR FOOD TAB
        $all_food_sections = array();

        // Get regular menu terms
        $menu_terms = get_terms(array(
            'taxonomy' => 'menu',
            'hide_empty' => false,
            'orderby' => 'term_id',
            'order' => 'ASC'
        ));

        // Filter out terms that have children
        $menu_terms = array_filter($menu_terms, function($term) {
            $children = get_terms(array(
                'taxonomy' => 'menu',
                'parent' => $term->term_id,
                'hide_empty' => false
            ));
            return empty($children);
        });

        // Add regular menu terms to sections array
        $term_order = 1;
        foreach ($menu_terms as $term) {

            if($selected_location['hide_features'] && strtolower($term->name) === 'features') continue;

            // Features gets order 1, everything else gets sequential order
            $order = (strtolower($term->name) === 'features') ? 1 : $term_order++;

            $all_food_sections[] = array(
                'type' => 'regular',
                'order' => $order,
                'id' => $term->slug,
                'name' => $term->name,
                'term' => $term,
                'description' => $term->description
            );
        }
           if($_GET['test'] == '1' ) {

           print_r($selected_location);
           print_r($all_food_sections);

           }


        // Get personalized steaks data and add to sections if enabled
        $personalized_data = function_exists('mrmikes_get_personalized_steaks_data') ? mrmikes_get_personalized_steaks_data() : false;
        if ($personalized_data) {
            $all_food_sections[] = array(
                'type' => 'personalized',
                'order' => intval($personalized_data['order']) - 2,
                'id' => 'personalized-steaks',
                'name' => $personalized_data['title'],
                'data' => $personalized_data
            );
        }

        // Get gluten friendly steaks data and add to sections if enabled
        $gluten_friendly_data = function_exists('mrmikes_get_gluten_friendly_steaks_data') ? mrmikes_get_gluten_friendly_steaks_data() : false;
        if ($gluten_friendly_data) {
            // Use sidebar_title if available, otherwise fall back to main title
            $sidebar_name = !empty($gluten_friendly_data['sidebar_title']) ? $gluten_friendly_data['sidebar_title'] : $gluten_friendly_data['title'];

            $all_food_sections[] = array(
                'type' => 'personalized',
                'order' => intval($gluten_friendly_data['order']) - 2,
                'id' => 'gluten-friendly-steaks',
                'name' => $gluten_friendly_data['title'], // Section title
                'sidebar_name' => $sidebar_name, // Sidebar menu item title
                'data' => $gluten_friendly_data
            );
        }

        // Sort all sections by order
        usort($all_food_sections, function($a, $b) {
            return $a['order'] - $b['order'];
        });

        ?>
        <!-- Mobile Category Slider (shows only below 650px) -->
        <div class="mobile-category-slider">
            <div class="mobile-slider-container">
                <button class="mobile-slider-arrow prev" onclick="slideCategories('prev')">?</button>
                <div class="mobile-slider-track" id="mobile-categories-track">
                    <?php foreach ($all_food_sections as $section): ?>
                        <?php
                        // Skip sections that shouldn't show in sidebar/mobile slider
                        if ($section['type'] === 'regular') {
                            $show_in_sidebar = get_field('show_in_menu_sidebar', 'term_' . $section['term']->term_id);
                            if ($show_in_sidebar === false) {
                                continue; // Skip this section in mobile slider too
                            }
                        }
                        ?>
                        <div class="mobile-category-item">
                            <a href="#<?php echo esc_attr($section['id']); ?>" class="mobile-category-link" data-category="<?php echo esc_attr($section['id']); ?>">
                                <?php
                                // Use sidebar_name if available, otherwise use name
                                $display_name = isset($section['sidebar_name']) ? $section['sidebar_name'] : $section['name'];
                                echo esc_html($display_name);
                                ?>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button class="mobile-slider-arrow next" onclick="slideCategories('next')">?</button>
            </div>
        </div>

        <?php
        // RENDER ALL SECTIONS IN SORTED ORDER
        $food_section_index = 1;
        $food_counter = 1;

        foreach ($all_food_sections as $section):
            $is_odd = ($food_counter % 2 == 1);
            $section_class = $is_odd ? 'menu-section-odd' : 'menu-section-even';

            // Add special class for personalized section
            if ($section['type'] === 'personalized') {
                $section_class = 'menu-section-personalized';
            }
            ?>

            <div class="menu-section <?php echo esc_attr($section_class); ?>" id="<?php echo esc_attr($section['id']); ?>">
                <div class="menu-section-content">
                    <div class="menu-section-left">
                        <?php if ($food_section_index === 1): // Only show sidebar in first section ?>
                            <div class="menu-sidebar">
                                <ul class="menu-categories">
                                    <?php foreach ($all_food_sections as $sidebar_section): ?>
                                        <?php
                                        // Skip sections that shouldn't show in sidebar
                                        if ($sidebar_section['type'] === 'regular') {
                                            $show_in_sidebar = get_field('show_in_menu_sidebar', 'term_' . $sidebar_section['term']->term_id);
                                            if ($show_in_sidebar === false) {
                                                continue; // Skip this section in sidebar
                                            }
                                        }
                                        ?>
                                        <li class="menu-category-item">
                                            <a href="#<?php echo esc_attr($sidebar_section['id']); ?>" class="menu-category-link<?php echo ($sidebar_section['id'] === $section['id']) ? ' active' : ''; ?>">
                                                <?php
                                                // Use sidebar_name if available, otherwise use name
                                                $display_name = isset($sidebar_section['sidebar_name']) ? $sidebar_section['sidebar_name'] : $sidebar_section['name'];
                                                echo esc_html($display_name);
                                                ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <!-- Menu Legend -->
                                <div class="menu-legend">
                                    <hr class="legend-divider">

                                    <div class="legend-item">
                                        <img src="<?php echo esc_url(MRMIKES_PLUGIN_URL . 'assets/img/mike-likes-icon.svg'); ?>" alt="Mike Likes" class="legend-icon">
                                        <span class="legend-text">Mike Likes</span>
                                    </div>

                                    <div class="legend-item">
                                        <img src="<?php echo esc_url(MRMIKES_PLUGIN_URL . 'assets/img/vegetarian-icon.svg'); ?>" alt="Vegetarian" class="legend-icon" style="width: 14px;">
                                        <span class="legend-text">Vegetarian</span>
                                    </div>

                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="menu-section-right">
                        <h3 class="menu-section-title"><?php echo esc_html($section['name']); ?></h3>
                        <?php if (!empty($section['description'])): ?>
                            <div class="menu-section-description"><?php echo nl2br(wp_kses_post($section['description'])); ?></div>
                        <?php endif; ?>
                        <?php if (!empty($section['data']['description'])): ?>
                            <div class="menu-section-description menu-section-description-personalized"><?php echo wp_kses_post($section['data']['description']); ?></div>
                        <?php endif; ?>
                        <?php if ($section['type'] === 'regular'): ?>
                            <!-- REGULAR MENU SECTION -->
                            <?php
                            // Get menu items for this category
                            $menu_items = get_posts(array(
                                'post_type' => 'dish',
                                'post_status' => 'publish',
                                'numberposts' => -1,
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'menu',
                                        'field' => 'term_id',
                                        'terms' => $section['term']->term_id,
                                    ),
                                ),
                                'orderby' => 'menu_order',
                                'order' => 'ASC'
                            ));

                            if (!empty($menu_items)): ?>
                                <div class="menu-items-list">
                                    <?php foreach ($menu_items as $menu_item):
                                        // Get menu item fields
                                        $description = get_field('description', $menu_item->ID);
                                        $image = get_field('image', $menu_item->ID);
                                        $flags = get_field('flag', $menu_item->ID);

                                        // Check which flags are set
                                        $show_mike_likes = false;
                                        $show_vegetarian = false;
                                        $show_gluten_friendly = false;

                                        if (!empty($flags) && is_array($flags)) {
                                            $show_mike_likes = in_array('m', $flags);
                                            $show_vegetarian = in_array('vegetarian', $flags);
                                            $show_gluten_friendly = in_array('gluten_friendly', $flags);
                                        }

                                        // Get price based on location's tier setting
                                        $price = '';
                                        if ($selected_location) {
                                            $location_tier = $selected_location['pricing_tier'];
                                            $has_subitems = get_field('subitems', $menu_item->ID);

                                            if ($has_subitems && !empty($location_tier)) {
                                                // Handle subitems
                                                for ($i = 1; $i <= 8; $i++) {
                                                    $subitem_title = get_field("subitem_{$i}_title", $menu_item->ID);
                                                    $subitem_tier_1_price = get_field("subitem_{$i}_tier_1_price", $menu_item->ID);
                                                    $subitem_tier_2_price = get_field("subitem_{$i}_tier_2_price", $menu_item->ID);

                                                    if (!empty($subitem_title)) {
                                                        $subitem_price = '';
                                                        if ($location_tier === 'tier_1' && !empty($subitem_tier_1_price)) {
                                                            $subitem_price = $subitem_tier_1_price;
                                                        } elseif ($location_tier === 'tier_2' && !empty($subitem_tier_2_price)) {
                                                            $subitem_price = $subitem_tier_2_price;
                                                        }

                                                        if (!empty($subitem_price)) {
                                                            $price .= '<div class="menu-item-subitem-wrapper"><span class="menu-item-subitem-price">' . esc_html(number_format((float)str_replace('$', '', $subitem_price), 2)) . '</span><span class="menu-item-subitem-title">' . esc_html($subitem_title) . '</span></div>';
                                                        }
                                                    }
                                                }
                                            } else {
                                                // Handle regular items
                                                if ($location_tier === 'tier_1') {
                                                    $tier_price = get_field('tier_1_price', $menu_item->ID);
                                                } elseif ($location_tier === 'tier_2') {
                                                    $tier_price = get_field('tier_2_price', $menu_item->ID);
                                                } else {
                                                    $tier_price = null;
                                                }

                                                if (!empty($tier_price)) {
                                                    $price = $tier_price;
                                                } else {
                                                    // Fallback to regional pricing
                                                    if (!empty($selected_location['region'])) {
                                                        $regions_data = get_field('regions', $menu_item->ID);
                                                        if ($regions_data && is_array($regions_data)) {
                                                            foreach ($regions_data as $row) {
                                                                if (isset($row['region']) && is_array($row['region'])) {
                                                                    foreach ($row['region'] as $region_obj) {
                                                                        if (isset($region_obj->name) && $region_obj->name === $selected_location['region']) {
                                                                            $price = isset($row['price']) ? str_replace('$', '', $row['price']) : '';
                                                                            break 2;
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        ?>

                                        <div class="menu-item">
                                            <div class="menu-item-content">
                                                <div class="menu-item-header">
                                                    <h3 class="menu-item-title"><?php echo esc_html($menu_item->post_title); ?></h3>
                                                    <div class="menu-item-icons">
                                                        <?php if ($show_mike_likes): ?>
                                                            <img src="<?php echo esc_url(MRMIKES_PLUGIN_URL . 'assets/img/mike-likes-icon.svg'); ?>" alt="Mike Likes" class="menu-item-icon">
                                                        <?php endif; ?>
                                                        <?php if ($show_vegetarian): ?>
                                                            <img src="<?php echo esc_url(MRMIKES_PLUGIN_URL . 'assets/img/vegetarian-icon.svg'); ?>" alt="Vegetarian" class="menu-item-icon" style="width: 14px;">
                                                        <?php endif; ?>
                                                        <?php if ($show_gluten_friendly): ?>
                                                            <img src="<?php echo esc_url(MRMIKES_PLUGIN_URL . 'assets/img/gluten_friendly.png'); ?>" alt="Gluten Friendly" class="menu-item-icon">
                                                        <?php endif; ?>
                                                        <?php if (!empty($image)): ?>
                                                            <img src="<?php echo esc_url(MRMIKES_PLUGIN_URL . 'assets/img/camera_icon.png'); ?>" alt="Camera" class="menu-item-camera">
                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                                <?php if (!empty($description)): ?>
                                                    <div class="menu-item-description"><?php echo wp_kses_post($description); ?></div>
                                                <?php endif; ?>

                                                <?php if (!empty($price)): ?>
                                                    <?php if($has_subitems) { ?>
                                                        <div class="menu-item-price"><?php echo $price; ?></div>
                                                    <?php } else { ?>
                                                        <div class="menu-item-price"><?php echo esc_html(number_format((float)$price, 2)); ?></div>
                                                    <?php } ?>
                                                <?php endif; ?>

                                                <!-- Options section -->
                                                <?php
                                                $options_html = '';
                                                if ($selected_location && !empty($selected_location['pricing_tier'])) {
                                                    $location_tier = $selected_location['pricing_tier'];
                                                    $option_items = array();

                                                    for ($i = 1; $i <= 5; $i++) {
                                                        $option_title = get_field("option_{$i}_title", $menu_item->ID);
                                                        $option_tier_1_price = get_field("option_{$i}_tier_1_price", $menu_item->ID);
                                                        $option_tier_2_price = get_field("option_{$i}_tier_2_price", $menu_item->ID);

                                                        if (!empty($option_title)) {
                                                            $option_price = '';
                                                            if ($location_tier === 'tier_1' && !empty($option_tier_1_price)) {
                                                                $option_price = $option_tier_1_price;
                                                            } elseif ($location_tier === 'tier_2' && !empty($option_tier_2_price)) {
                                                                $option_price = $option_tier_2_price;
                                                            }

                                                            if (strpos($option_price, '%') !== false) {
                                                                $formatted_price = '+' . esc_html($option_price);
                                                            } else {
                                                                $formatted_price = '+' . esc_html(number_format((float)str_replace('$', '', $option_price), 2));
                                                            }
                                                            if(empty($option_price) || $option_price == 0) {
                                                                $formatted_price = '';
                                                            }
                                                            $option_items[] = '<span class="add-option-text">' . esc_html($option_title) . '</span> <span class="add-option-price">' . $formatted_price . '</span>';

                                                        }
                                                    }

                                                    if (!empty($option_items)) {
                                                        $options_html = '<div class="menu-item-options">' . implode(' <br /> ', $option_items) . '</div>';
                                                    }
                                                }
                                                echo $options_html;
                                                ?>

                                                <div class="menu-item-add" data-dish-id="<?php echo esc_attr($menu_item->ID); ?>"></div>
                                            </div>
                                        </div>

                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="no-menu-items"><?php echo esc_html__('No menu items found for this category.', 'mrmikes-menu'); ?></p>
                            <?php endif; ?>

                        <?php elseif ($section['type'] === 'personalized'): ?>
                            <!-- PERSONALIZED STEAKS SECTION -->
                            <div class="personalized-menu-grid">
                                <!-- Step 1: Pick Your Steak -->
                                <?php if (!empty($section['data']['steaks'])): ?>
                                <div class="personalized-section">
                                    <h3 class="personalized-step-title">
                                        <span class="personalized-step-number">STEP 1:</span>
                                        <span class="personalized-step-text">PICK YOUR STEAK</span>
                                    </h3>
                                    <div class="personalized-items">
                                        <?php foreach ($section['data']['steaks'] as $steak):
                                            // Get tier-based price for steaks
                                            $steak_price = '';
                                            if ($selected_location && !empty($selected_location['pricing_tier'])) {
                                                $location_tier = $selected_location['pricing_tier'];
                                                if ($location_tier === 'tier_1' && !empty($steak['steak_tier_1_price'])) {
                                                    $steak_price = $steak['steak_tier_1_price'];
                                                } elseif ($location_tier === 'tier_2' && !empty($steak['steak_tier_2_price'])) {
                                                    $steak_price = $steak['steak_tier_2_price'];
                                                }
                                            }
                                        ?>
                                            <div class="personalized-item">
                                                <div class="personalized-item-header">
                                                    <span class="personalized-item-name"><?php echo esc_html($steak['steak_name']); ?></span>
                                                    <?php if (!empty($steak['steak_size'])): ?>
                                                        <span class="personalized-item-size"><?php echo esc_html($steak['steak_size']); ?></span>
                                                    <?php endif; ?>
                                                    <?php if (!empty($steak_price)): ?>
                                                        <span class="personalized-item-price"><?php echo esc_html(number_format((float)$steak_price, 2)); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if (!empty($steak['steak_description'])): ?>
                                                    <div class="personalized-item-description"><?php echo nl2br(wp_kses_post($steak['steak_description'])); ?></div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <!-- Step 2: Choose Your Style -->
                                <?php if (!empty($section['data']['styles'])): ?>
                                <div class="personalized-section">
                                    <h3 class="personalized-step-title">
                                        <span class="personalized-step-number">STEP 2:</span>
                                        <span class="personalized-step-text">CHOOSE YOUR STYLE</span>
                                    </h3>
                                    <div class="personalized-items">
                                        <?php foreach ($section['data']['styles'] as $style): ?>
                                            <div class="personalized-item personalized-style-item">
                                                <div class="personalized-style-header">
                                                    <?php if (!empty($style['style_icon_image'])):
                                                        $icon_image = wp_get_attachment_image_src($style['style_icon_image'], 'thumbnail');
                                                        if ($icon_image):
                                                    ?>
                                                        <div class="personalized-style-icon">
                                                            <img src="<?php echo esc_url($icon_image[0]); ?>" alt="<?php echo esc_attr($style['style_name']); ?> icon">
                                                        </div>
                                                    <?php endif; endif; ?>
                                                    <div class="personalized-style-content">
                                                        <div class="personalized-style-name-row">
                                                            <span class="personalized-item-name"><?php echo esc_html($style['style_name']); ?></span>
                                                            <?php if (!empty($style['style_additional_cost'])): ?>
                                                                <span class="personalized-additional-cost"><?php echo esc_html($style['style_additional_cost']); ?></span>
                                                            <?php endif; ?>
                                                            <?php if (!empty($style['style_has_camera']) && !empty($style['style_image'])): ?>
                                                                <img src="<?php echo esc_url(MRMIKES_PLUGIN_URL . 'assets/img/camera-icon-light-grey.svg'); ?>" alt="Camera" class="personalized-camera-icon" data-style-image-id="<?php echo esc_attr($style['style_image']); ?>" data-style-name="<?php echo esc_attr($style['style_name']); ?>">
                                                            <?php endif; ?>
                                                        </div>
                                                        <?php if (!empty($style['style_description'])): ?>
                                                            <div class="personalized-item-description"><?php echo nl2br(wp_kses_post($style['style_description'])); ?></div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <!-- Step 3: Personalize It -->
                                <?php if (!empty($section['data']['addons'])): ?>
                                <div class="personalized-section">
                                    <h3 class="personalized-step-title">
                                        <span class="personalized-step-number">STEP 3:</span>
                                        <span class="personalized-step-text">PERSONALIZE IT</span>
                                    </h3>
                                    <div class="personalized-items">
                                        <?php foreach ($section['data']['addons'] as $addon):
                                            // Get tier-based price for addons
                                            $addon_price = '';
                                            if ($selected_location && !empty($selected_location['pricing_tier'])) {
                                                $location_tier = $selected_location['pricing_tier'];
                                                if ($location_tier === 'tier_1' && !empty($addon['addon_tier_1_price'])) {
                                                    $addon_price = $addon['addon_tier_1_price'];
                                                } elseif ($location_tier === 'tier_2' && !empty($addon['addon_tier_2_price'])) {
                                                    $addon_price = $addon['addon_tier_2_price'];
                                                }
                                            }
                                        ?>
                                            <div class="personalized-item">
                                                <div class="personalized-item-header">
                                                    <span class="personalized-item-name"><?php echo esc_html($addon['addon_name']); ?></span>
                                                    <?php if (!empty($addon_price)): ?>
                                                        <span class="personalized-item-price"><?php echo esc_html(number_format((float)$addon_price, 2)); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if (!empty($addon['addon_description'])): ?>
                                                    <div class="personalized-item-description"><?php echo nl2br(wp_kses_post($addon['addon_description'])); ?></div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <!-- Extra Sides -->
                                <?php if (!empty($section['data']['sides'])): ?>
                                <div class="personalized-section">
                                    <h3 class="personalized-step-title">
                                        <span class="personalized-step-number">EXTRA SIDES</span>
                                    </h3>
                                    <div class="personalized-items">
                                        <?php foreach ($section['data']['sides'] as $side):
                                            // Get tier-based price for sides
                                            $side_price = '';
                                            if ($selected_location && !empty($selected_location['pricing_tier'])) {
                                                $location_tier = $selected_location['pricing_tier'];
                                                if ($location_tier === 'tier_1' && !empty($side['side_tier_1_price'])) {
                                                    $side_price = $side['side_tier_1_price'];
                                                } elseif ($location_tier === 'tier_2' && !empty($side['side_tier_2_price'])) {
                                                    $side_price = $side['side_tier_2_price'];
                                                }
                                            }
                                        ?>
                                            <div class="personalized-item">
                                                <div class="personalized-item-header">
                                                    <span class="personalized-item-name"><?php echo esc_html($side['side_name']); ?></span>
                                                    <?php if (!empty($side_price)): ?>
                                                        <span class="personalized-item-price"><?php echo esc_html(number_format((float)$side_price, 2)); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if (!empty($side['side_description'])): ?>
                                                    <div class="personalized-item-description"><?php echo esc_html($side['side_description']); ?></div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>



                                </div>
                                <?php endif; ?>

                            </div>

                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php
            $food_counter++;
            $food_section_index++;
        endforeach;
        ?>

            <div class="menu-section <?php echo $section_class == 'menu-section-even' ? 'menu-section-odd' : 'menu-section-even'; ?>">
                <div class="menu-section-content">
                    <div class="menu-section-left">
                                            </div>

                    <div class="menu-section-right">
                        <h3 class="menu-section-title">Allergy and Nutritional Information</h3>
                                                                                                    <!-- REGULAR MENU SECTION -->
                            <a href="/wp-content/uploads/2025/03/Allergy-Info-Jan2025_v4.pdf" target="_blank" class="info-link"><h3 class="menu-item-title">Allergy Information</h3> <img src="/wp-content/uploads/2025/09/pdf.webp"/></a>
                            <a href="/wp-content/uploads/2024/12/Nutrition-Info-Dec2024_v3.pdf" target="_blank" class="info-link"><h3 class="menu-item-title">Nutritional Information</h3> <img src="/wp-content/uploads/2025/09/pdf.webp"/></a>

                                            </div>
                </div>
            </div>

    </div>
    <!-- End Food Tab Content -->

    <!-- Drinks Tab Content -->
    <div class="tab-pane <?php echo ($menu_type_from_url === 'drinks') ? 'active' : ''; ?>" id="drinks-tab">

        <!-- Mobile Category Slider (shows only below 650px) -->
        <div class="mobile-category-slider">
            <div class="mobile-slider-container">
                <button class="mobile-slider-arrow prev" onclick="slideDrinksCategories('prev')">?</button>
                <div class="mobile-slider-track" id="mobile-categories-track-drinks">
                    <?php
                    // Get drinks menu terms for mobile slider with same Features-first sorting
                    $mobile_drinks_menu_terms = get_terms(array(
                        'taxonomy' => 'menu_drinks',
                        'hide_empty' => false,
                        'orderby' => 'term_id',
                        'order' => 'ASC'
                    ));

                    // Filter out terms that have children
                    /*$mobile_drinks_menu_terms = array_filter($mobile_drinks_menu_terms, function($term) {
                        $children = get_terms(array(
                            'taxonomy' => 'menu_drinks',
                            'parent' => $term->term_id,
                            'hide_empty' => false
                        ));
                        return empty($children);
                    });

                    // Sort mobile drinks menu terms to put "Features" first and Wine children after Features
                    /*if (!empty($mobile_drinks_menu_terms) && !is_wp_error($mobile_drinks_menu_terms)) {
                        $mobile_features_drinks_term = null;
                        $mobile_wine_children_drinks = array();
                        $mobile_other_drinks_terms = array();

                        // First, get the Wine parent term ID from menu_drinks taxonomy
                        $wine_parent_term_drinks = get_term_by('name', 'Wine', 'menu_drinks');
                        $wine_parent_id_drinks = $wine_parent_term_drinks ? $wine_parent_term_drinks->term_id : null;

                        foreach ($mobile_drinks_menu_terms as $mobile_drinks_term) {
                            if (strtolower($mobile_drinks_term->name) === 'features') {
                                $mobile_features_drinks_term = $mobile_drinks_term;
                            } elseif ($wine_parent_id_drinks && $mobile_drinks_term->parent == $wine_parent_id_drinks) {
                                $mobile_wine_children_drinks[] = $mobile_drinks_term;
                            } else {
                                $mobile_other_drinks_terms[] = $mobile_drinks_term;
                            }
                        }

                        // Rebuild array with Features first, then Wine children, then others
                        $mobile_drinks_menu_terms = array();
                        if ($mobile_features_drinks_term) {
                            $mobile_drinks_menu_terms[] = $mobile_features_drinks_term;
                        }
                        $mobile_drinks_menu_terms = array_merge($mobile_drinks_menu_terms, $mobile_wine_children_drinks, $mobile_other_drinks_terms);
                    } */

                    /*foreach ($mobile_drinks_menu_terms as $mobile_drinks_term) {
                        if (strtolower($mobile_drinks_term->name) === 'features') {
                            $mobile_features_drinks_term = $mobile_drinks_term;
                        } elseif ($wine_parent_id_drinks && $mobile_drinks_term->parent == $wine_parent_id_drinks) {
                            // Skip wine children for mobile slider too
                            continue;
                        } else {
                            $mobile_other_drinks_terms[] = $mobile_drinks_term;
                        }
                    }

                    // Rebuild array with Features first, then Wine parent, then others
                    $mobile_drinks_menu_terms = array();
                    if ($mobile_features_drinks_term) {
                        $mobile_drinks_menu_terms[] = $mobile_features_drinks_term;
                    }

                    // Add Wine parent term for mobile
                    if ($wine_parent_id_drinks) {
                        $first_wine_child = get_terms(array(
                            'taxonomy' => 'menu_drinks',
                            'parent' => $wine_parent_id_drinks,
                            'hide_empty' => false,
                            'number' => 1,
                            'orderby' => 'term_id',
                            'order' => 'ASC'
                        ));

                        if (!empty($first_wine_child)) {
                            $wine_parent_term_drinks->first_wine_slug = $first_wine_child[0]->slug;
                            $mobile_drinks_menu_terms[] = $wine_parent_term_drinks;
                        }
                    }

                    $mobile_drinks_menu_terms = array_merge($mobile_drinks_menu_terms, $mobile_other_drinks_terms);   */

                    foreach ($mobile_drinks_menu_terms as $mobile_drinks_term):

                        if($selected_location['hide_features'] && strtolower($mobile_drinks_term->name) === 'features') continue;

                        // Skip sections that shouldn't show in sidebar/mobile slider
                        $show_in_sidebar = get_field('show_in_menu_sidebar', 'term_' . $mobile_drinks_term->term_id);
                        if ($show_in_sidebar === false) {
                            continue;
                        }

                        // Determine the link target for mobile
                        if ($mobile_drinks_term->name == 'Wine') {
                            $link_target = 'white-wine-drinks';
                        } else {
                            $link_target = $mobile_drinks_term->slug . '-drinks';
                        }
                        ?>
                        <div class="mobile-category-item">
                            <a href="#<?php echo esc_attr($link_target); ?>" class="mobile-category-link" data-category="<?php echo esc_attr($link_target); ?>">
                                <?php echo esc_html($mobile_drinks_term->name); ?>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button class="mobile-slider-arrow next" onclick="slideDrinksCategories('next')">?</button>
            </div>
        </div>

<?php
// Get all terms from the 'menu_drinks' taxonomy for creating drinks sections
$drinks_menu_terms = get_terms(array(
    'taxonomy' => 'menu_drinks',
    'hide_empty' => false,
    'orderby' => 'term_id',
    'order' => 'ASC'
));

// Filter out terms that have children
$drinks_menu_terms = array_filter($drinks_menu_terms, function($term) {
    $children = get_terms(array(
        'taxonomy' => 'menu_drinks',
        'parent' => $term->term_id,
        'hide_empty' => false
    ));
    return empty($children);
});

// Sort drinks menu terms to put "Features" first and Wine children after Features
/*if (!empty($drinks_menu_terms) && !is_wp_error($drinks_menu_terms)) {
    $features_drinks_term = null;
    $wine_children_drinks = array();
    $other_drinks_terms = array();

    // First, get the Wine parent term ID from menu_drinks taxonomy
    $wine_parent_term_drinks = get_term_by('name', 'Wine', 'menu_drinks');
    $wine_parent_id_drinks = $wine_parent_term_drinks ? $wine_parent_term_drinks->term_id : null;

    foreach ($drinks_menu_terms as $term) {
        if (strtolower($term->name) === 'features') {
            $features_drinks_term = $term;
        } elseif ($wine_parent_id_drinks && $term->parent == $wine_parent_id_drinks) {
            $wine_children_drinks[] = $term;
        } else {
            $other_drinks_terms[] = $term;
        }
    }

    // Rebuild array with Features first, then Wine children, then others
    $drinks_menu_terms = array();
    if ($features_drinks_term) {
        $drinks_menu_terms[] = $features_drinks_term;
    }
    $drinks_menu_terms = array_merge($drinks_menu_terms, $wine_children_drinks, $other_drinks_terms);
} */

if (!empty($drinks_menu_terms) && !is_wp_error($drinks_menu_terms)):
    $section_index = 1; // Track absolute section position for sidebar
    $standard_counter = 1; // Counter for standard sections only
    $wine_counter = 1; // Counter for wine sections only

    // Get Wine parent term ID for checking wine children
    $wine_parent_term_drinks = get_term_by('name', 'Wine', 'menu_drinks');
    $wine_parent_id_drinks = $wine_parent_term_drinks ? $wine_parent_term_drinks->term_id : null;

    foreach ($drinks_menu_terms as $term):

        if($selected_location['hide_features'] && strtolower($term->name) === 'features') continue;

        // Check if this term is a wine child
        $is_wine_child = ($wine_parent_id_drinks && $term->parent == $wine_parent_id_drinks);

        if ($is_wine_child) {
            // Use wine-specific classes and wine counter
            $is_odd = ($wine_counter % 2 == 1);
            $section_class = $is_odd ? 'menu-section-wine-odd' : 'menu-section-wine-even';
            $wine_counter++;
        } else {
            // Use normal classes and standard counter
            $is_odd = ($standard_counter % 2 == 1);
            $section_class = $is_odd ? 'menu-section-odd' : 'menu-section-even';
            $standard_counter++;
        }
        ?>

        <div class="menu-section <?php echo esc_attr($section_class); ?>" id="<?php echo esc_attr($term->slug); ?>-drinks">
            <div class="menu-section-content">
                <div class="menu-section-left">
                    <?php if ($section_index === 1): // Only show sidebar in first section ?>
                        <div class="menu-sidebar">
                            <ul class="menu-categories">
                                <?php
                                // Get drinks menu terms for sidebar with same Features-first sorting
                                $drinks_sidebar_terms = get_terms(array(
                                    'taxonomy' => 'menu_drinks',
                                    'hide_empty' => false,
                                    'orderby' => 'term_id',
                                    'order' => 'ASC'
                                ));

                                // Filter out terms that have children
                                /*$drinks_sidebar_terms = array_filter($drinks_sidebar_terms, function($term) {
                                    $children = get_terms(array(
                                        'taxonomy' => 'menu_drinks',
                                        'parent' => $term->term_id,
                                        'hide_empty' => false
                                    ));
                                    return empty($children);
                                });*/

                                // Sort drinks sidebar terms to put "Features" first and Wine children after Features
                                /*if (!empty($drinks_sidebar_terms) && !is_wp_error($drinks_sidebar_terms)) {
                                    $features_drinks_sidebar_term = null;
                                    $wine_children_drinks_sidebar = array();
                                    $other_drinks_sidebar_terms = array();

                                    // First, get the Wine parent term ID from menu_drinks taxonomy
                                    $wine_parent_term_drinks = get_term_by('name', 'Wine', 'menu_drinks');
                                    $wine_parent_id_drinks = $wine_parent_term_drinks ? $wine_parent_term_drinks->term_id : null;

                                    foreach ($drinks_sidebar_terms as $drinks_sidebar_term) {
                                        if (strtolower($drinks_sidebar_term->name) === 'features') {
                                            $features_drinks_sidebar_term = $drinks_sidebar_term;
                                        } elseif ($wine_parent_id_drinks && $drinks_sidebar_term->parent == $wine_parent_id_drinks) {
                                            // Instead of adding wine children, we'll add the Wine parent later
                                            continue;
                                        } else {
                                            $other_drinks_sidebar_terms[] = $drinks_sidebar_term;
                                        }
                                    }

                                    // Rebuild array with Features first, then Wine parent, then others
                                    $drinks_sidebar_terms = array();
                                    if ($features_drinks_sidebar_term) {
                                        $drinks_sidebar_terms[] = $features_drinks_sidebar_term;
                                    }

                                    // Add Wine parent term that links to first wine child
                                    if ($wine_parent_id_drinks) {
                                        // Get the first wine child term to link to
                                        $first_wine_child = get_terms(array(
                                            'taxonomy' => 'menu_drinks',
                                            'parent' => $wine_parent_id_drinks,
                                            'hide_empty' => false,
                                            'number' => 1,
                                            'orderby' => 'term_id',
                                            'order' => 'ASC'
                                        ));

                                        if (!empty($first_wine_child)) {
                                            $wine_parent_term_drinks->first_wine_slug = $first_wine_child[0]->slug;
                                            $drinks_sidebar_terms[] = $wine_parent_term_drinks;
                                        }
                                    }

                                    $drinks_sidebar_terms = array_merge($drinks_sidebar_terms, $other_drinks_sidebar_terms);

                                } */

                                foreach ($drinks_sidebar_terms as $drinks_sidebar_term): ?>
                                    <?php

                                    if($selected_location['hide_features'] && strtolower($drinks_sidebar_term->name) === 'features') continue;

                                    // Skip sections that shouldn't show in sidebar
                                    $show_in_sidebar = get_field('show_in_menu_sidebar', 'term_' . $drinks_sidebar_term->term_id);
                                    if ($show_in_sidebar === false) {
                                        continue; // Skip this section in sidebar
                                    }

                                    // Determine the link target
                                    if ($drinks_sidebar_term->name == 'Wine') {
                                        // This is the Wine parent, link to first wine child
                                        $link_target = 'white-wine-drinks';
                                    } else {
                                        // Regular term, link to itself
                                        $link_target = $drinks_sidebar_term->slug . '-drinks';
                                    }
                                    ?>
                                    <li class="menu-category-item">
                                        <a href="#<?php echo esc_attr($link_target); ?>" class="menu-category-link<?php echo ($drinks_sidebar_term->term_id === $term->term_id) ? ' active' : ''; ?>">
                                            <?php echo esc_html($drinks_sidebar_term->name); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="menu-section-right">
                    <h3 class="menu-section-title"><?php echo esc_html($term->name === 'Cocktails' ? 'Classic Cocktails' : $term->name); ?></h3>
                    <?php if (!empty($term->description)): ?>
                        <div class="menu-section-description"><?php echo nl2br(wp_kses_post($term->description)); ?></div>
                    <?php endif; ?>
                    <?php
                    // Get drinks items for this category - using 'drinks' post type for drinks tab
                    $query_args = array(
                        'post_type' => 'drinks',
                        'post_status' => 'publish',
                        'numberposts' => -1,
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'menu_drinks',
                                'field' => 'term_id',
                                'terms' => $term->term_id,
                            ),
                        ),
                        'orderby' => 'menu_order',
                        'order' => 'ASC'
                    );

                    // Filter by province if location is selected
                    /*if ($selected_location && !empty($selected_location['province'])) {
                        // Map full province names to abbreviations
                        $province_mapping = array(
                            'British Columbia' => 'BC',
                            'Alberta' => 'AB',
                            'Saskatchewan' => 'SK',
                            'Manitoba' => 'MB',
                            'Ontario' => 'ON'
                        );

                        $province_abbr = isset($province_mapping[$selected_location['province']]) ?
                                        $province_mapping[$selected_location['province']] :
                                        $selected_location['province'];

                        $query_args['meta_query'] = array(
                            array(
                                'key' => 'provinces',
                                'value' => $province_abbr,
                                'compare' => 'LIKE'
                            )
                        );
                    } */

                    if ($selected_location) {

                            $province = strtoupper(wp_get_post_terms($selected_location['id'], 'provinces')[0]->slug);

                            if(!empty($province)) {
                            // Map full province names to abbreviations
                            $province_mapping = array(
                                'British Columbia' => 'BC',
                                'Alberta' => 'AB',
                                'Saskatchewan' => 'SK',
                                'Manitoba' => 'MB',
                                'Ontario' => 'ON'
                            );

                            $province_abbr = isset($province_mapping[$province]) ?
                                            $province_mapping[$province] :
                                            $province;

                            $query_args['meta_query'] = array(
                                array(
                                    'key' => 'provinces',
                                    'value' => $province_abbr,
                                    'compare' => 'LIKE'
                                )
                            );
                        }
                    }

                    $menu_items = get_posts($query_args);

                    if (!empty($menu_items)): ?>
                        <div class="menu-items-list">
                            <?php foreach ($menu_items as $menu_item):
                                // Get menu item fields
                                $description = get_field('description', $menu_item->ID);
                                $image = get_field('image', $menu_item->ID);
                                $flags = get_field('flag', $menu_item->ID); // Get the flag checkbox field

                                $has_subitems = get_field('subitems', $menu_item->ID);

                                // Get price based on location's pricing tier
                                $price = '';
                                if ($selected_location && !empty($selected_location['pricing_tier'])) {
                                    $location_tier = $selected_location['pricing_tier']; // 'tier_1' or 'tier_2'

                                    if ($has_subitems) {
                                        // Handle subitems - get individual subitem fields (subitem_1 to subitem_5)
                                        for ($i = 1; $i <= 8; $i++) {
                                            $subitem_title = get_field("subitem_{$i}_title", $menu_item->ID);
                                            $subitem_tier_1_price = get_field("subitem_{$i}_tier_1_price", $menu_item->ID);
                                            $subitem_tier_2_price = get_field("subitem_{$i}_tier_2_price", $menu_item->ID);

                                            if (!empty($subitem_title)) {
                                                $subitem_price = '';
                                                if ($location_tier === 'tier_1' && !empty($subitem_tier_1_price)) {
                                                    $subitem_price = $subitem_tier_1_price;
                                                } elseif ($location_tier === 'tier_2' && !empty($subitem_tier_2_price)) {
                                                    $subitem_price = $subitem_tier_2_price;
                                                }

                                                if (!empty($subitem_price)) {
                                                    $price .= '<div class="menu-item-subitem-wrapper"><span class="menu-item-subitem-price">' . esc_html(number_format((float)str_replace('$', '', $subitem_price), 2)) . '</span><span class="menu-item-subitem-title">' . esc_html($subitem_title) . '</span></div>';
                                                }
                                            }
                                        }
                                    } else {
                                        // Handle regular items (no subitems)
                                        if ($location_tier === 'tier_1') {
                                            $tier_price = get_field('tier_1_price', $menu_item->ID);
                                        } elseif ($location_tier === 'tier_2') {
                                            $tier_price = get_field('tier_2_price', $menu_item->ID);
                                        }
                                        if (!empty($tier_price)) {
                                            $price = $tier_price;
                                        }
                                    }
                                }
                                ?>

                                <div class="menu-item">
                                    <div class="menu-item-content">
                                        <div class="menu-item-header">
                                            <h3 class="menu-item-title"><?php echo esc_html($menu_item->post_title); ?></h3>
                                            <div class="menu-item-icons">
                                                <!-- Camera icon only if there's an image -->
                                                <?php if (!empty($image)): ?>
                                                    <img src="<?php echo esc_url(MRMIKES_PLUGIN_URL . 'assets/img/camera_icon.png'); ?>" alt="Camera" class="menu-item-camera">
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <?php if (!empty($description)): ?>
                                            <div class="menu-item-description"><?php echo wp_kses_post($description); ?></div>
                                        <?php endif; ?>

                                        <?php if (!empty($price)): ?>
                                            <?php if($has_subitems) { ?>
                                                <div class="menu-item-price"><?php echo $price; ?></div>
                                            <?php } else { ?>
                                                <div class="menu-item-price"><?php echo esc_html(number_format((float)$price, 2)); ?></div>
                                            <?php } ?>
                                        <?php endif; ?>

                                        <!-- Options section -->
                                        <?php
                                        // Get options for this drinks item based on selected location's pricing tier
                                        $options_html = '';
                                        if ($selected_location && !empty($selected_location['pricing_tier'])) {
                                            $location_tier = $selected_location['pricing_tier']; // 'tier_1' or 'tier_2'

                                            // Get options from individual fields (option_1 to option_5)
                                            $option_items = array();
                                            for ($i = 1; $i <= 5; $i++) {
                                                $option_title = get_field("option_{$i}_title", $menu_item->ID);
                                                $option_tier_1_price = get_field("option_{$i}_tier_1_price", $menu_item->ID);
                                                $option_tier_2_price = get_field("option_{$i}_tier_2_price", $menu_item->ID);

                                                if (!empty($option_title)) {
                                                    $option_price = '';
                                                    if ($location_tier === 'tier_1' && !empty($option_tier_1_price)) {
                                                        $option_price = $option_tier_1_price;
                                                    } elseif ($location_tier === 'tier_2' && !empty($option_tier_2_price)) {
                                                        $option_price = $option_tier_2_price;
                                                    }


                                                        // Check if option price contains a percent sign
                                                    if (strpos($option_price, '%') !== false) {
                                                        // Display as-is if it contains %
                                                        $formatted_price = '+' . esc_html($option_price);
                                                    } else {
                                                        // Format as number if no % sign
                                                        $formatted_price = '+' . esc_html(number_format((float)str_replace('$', '', $option_price), 2));
                                                    }
                                                    if(empty($option_price) || $option_price == 0) {
                                                        $formatted_price = '';
                                                    }
                                                    $option_items[] = '<span class="add-option-text">' . esc_html($option_title) . '</span> <span class="add-option-price">' . $formatted_price . '</span>';

                                                }
                                            }

                                            if (!empty($option_items)) {
                                                $options_html = '<div class="menu-item-options">' . implode(' <br /> ', $option_items) . '</div>';
                                            }
                                        }
                                        echo $options_html;
                                        ?>

                                        <!-- Empty add item section for future use -->
                                        <div class="menu-item-add" data-dish-id="<?php echo esc_attr($menu_item->ID); ?>">
                                            <!-- Content will be added here in the future -->
                                        </div>
                                    </div>
                                </div>

                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="no-menu-items"><?php echo esc_html__('No menu items found for this category.', 'mrmikes-menu'); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php
        // Increment section index for all sections
        $section_index++;
    endforeach;
endif;
?>

    </div>
    <!-- End Drinks Tab Content -->

    <!-- Daily Specials Tab Content -->
<div class="tab-pane <?php echo ($menu_type_from_url === 'daily-specials') ? 'active' : ''; ?>" id="daily-specials-tab">

    <?php

    // Get daily specials data to check if Lodge Night is enabled
    $daily_specials_data = function_exists('mrmikes_get_daily_specials_data') ? mrmikes_get_daily_specials_data() : false;
    $lodge_night_enabled = $daily_specials_data && !empty($daily_specials_data['enable_thursday_lodge_night']);

    // Define the daily specials sections - conditionally include Lodge Night
    $daily_specials_sections = array(
        /*array(
            'id' => 'daily-specials-copy',
            'name' => 'Lunch Like a Local',
            'content' => 'daily_specials_copy'
        ),*/
        array(
            'id' => 'happiest-hours',
            'name' => 'Happiest Hours',
            'content' => 'happiest_hours'
        ),
        array(
            'id' => 'daily-specials',
            'name' => 'Daily Drink Specials',
            'content' => 'daily_specials'
        )
    );

    // Add Lodge Night section only if enabled
    if ($lodge_night_enabled) {
        array_unshift($daily_specials_sections, array(
            'id' => 'thursdays-lodge-night',
            'name' => 'Lunch Like a Local',
            'content' => 'thursdays_lodge_night'
        ));
    }

    ?>

    <!-- Mobile Category Slider (shows only below 650px) -->
    <div class="mobile-category-slider">
        <div class="mobile-slider-container">
            <button class="mobile-slider-arrow prev" onclick="slideSpecialsCategories('prev')">?</button>
            <div class="mobile-slider-track" id="mobile-categories-track-specials">
                <?php foreach ($daily_specials_sections as $mobile_section): ?>
                    <div class="mobile-category-item">
                        <a href="#<?php echo esc_attr($mobile_section['id']); ?>" class="mobile-category-link" data-category="<?php echo esc_attr($mobile_section['id']); ?>">
                            <?php echo esc_html($mobile_section['name']); ?>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="mobile-slider-arrow next" onclick="slideSpecialsCategories('next')">?</button>
        </div>
    </div>

    <?php


    $specials_section_index = 1; // Track absolute section position for sidebar
    $specials_counter = 1; // Track section numbering for alternating classes

    foreach ($daily_specials_sections as $section):
        $is_odd = ($specials_counter % 2 == 1);
        $section_class = $is_odd ? 'menu-section-odd' : 'menu-section-even';

        // Add special spacing class for first section
        if ($specials_section_index === 1) {
            $section_class .= ' menu-section-first-specials';
        }
        ?>

        <div class="menu-section <?php echo esc_attr($section_class); ?>" id="<?php echo esc_attr($section['id']); ?>">
            <div class="menu-section-content">
                <div class="menu-section-left">
                    <?php if ($specials_section_index === 1): // Only show sidebar in first section ?>
                        <div class="menu-sidebar">
                            <ul class="menu-categories">
                                <?php foreach ($daily_specials_sections as $sidebar_section): ?>
                                    <li class="menu-category-item">
                                        <a href="#<?php echo esc_attr($sidebar_section['id']); ?>" class="menu-category-link<?php echo ($sidebar_section['id'] === $section['id']) ? ' active' : ''; ?>">
                                            <?php echo esc_html($sidebar_section['name']); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="menu-section-right">
                    <h3 class="menu-section-title"><?php echo esc_html($section['name']); ?></h3>

                    <?php if ($section['content'] === 'daily_specials_copy'): ?>

                        <!-- Daily Specials Content -->
                        <div class="daily-specials-copy-content">
                            <?php
                            // Get daily specials data
                            $daily_specials_data = function_exists('mrmikes_get_daily_specials_data') ? mrmikes_get_daily_specials_data() : false;

                            // Define the days and their corresponding field names
                            $daily_specials_days = array(
                                array('day' => 'SUN', 'field' => 'sunday_specials_copy_global'),
                                array('day' => 'MON', 'field' => 'monday_specials_copy_global'),
                                array('day' => 'TUES', 'field' => 'tuesday_specials_copy_global'),
                                array('day' => 'WED', 'field' => 'wednesday_specials_copy_global'),
                                array('day' => 'THURS', 'field' => 'thursday_specials_copy_global'),
                                array('day' => 'FRI', 'field' => 'friday_specials_copy_global'),
                                array('day' => 'SAT', 'field' => 'saturday_specials_copy_global')
                            );

                            foreach ($daily_specials_days as $day_info):
                                $day_items = $daily_specials_data && isset($daily_specials_data[$day_info['field']]) ? $daily_specials_data[$day_info['field']] : false;

                                // Only show the day if it has items
                                if ($day_items && !empty($day_items)):
                            ?>
                                <div class="daily-special-day">
                                    <h3 class="daily-special-day-title"><?php echo esc_html($day_info['day']); ?></h3>
                                    <div class="menu-items-list">
                                        <?php foreach ($day_items as $item):
                                        ?>
                                            <div class="menu-item">
                                                <div class="menu-item-content">
                                                    <div class="menu-item-header">
                                                        <h4 class="menu-item-title"><?php echo esc_html($item['item_name']); ?></h4>
                                                    <div class="menu-item-icons">
                                                        <!-- Camera icon only if there's an image -->
                                                        <?php if (!empty($image)): ?>
                                                            <img src="<?php echo esc_url(MRMIKES_PLUGIN_URL . 'assets/img/camera_icon.png'); ?>" alt="Camera" class="menu-item-camera">
                                                        <?php endif; ?>
                                                    </div>
                                                    </div>
                                                    <?php if (!empty($item['description'])): ?>
                                                        <div class="menu-item-description"><?php echo wp_kses_post($item['description']); ?></div>
                                                    <?php endif; ?>
                                                    <?php
                                                    // Get tier-based price for daily specials
                                                    $daily_price = '';
                                                    if ($selected_location && !empty($selected_location['pricing_tier'])) {
                                                        $location_tier = $selected_location['pricing_tier']; // 'tier_1' or 'tier_2'

                                                        if ($location_tier === 'tier_1' && !empty($item['tier_1_price'])) {
                                                            $daily_price = $item['tier_1_price'];
                                                        } elseif ($location_tier === 'tier_2' && !empty($item['tier_2_price'])) {
                                                            $daily_price = $item['tier_2_price'];
                                                        }
                                                    }

                                                    if (!empty($daily_price)): ?>
                                                        <div class="menu-item-price"><?php echo esc_html($daily_price); ?></div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php
                                endif;
                            endforeach;
                            ?>
                        </div>

                    <?php elseif ($section['content'] === 'happiest_hours'): ?>
                        <!-- Happiest Hours Content -->
                        <?php
                        $daily_specials_data = function_exists('mrmikes_get_daily_specials_data') ? mrmikes_get_daily_specials_data() : false;
                        ?>
                        <div class="happiest-hours-sections-container">
                            <!-- Bites Section -->
                            <div class="happiest-hours-section">
                                <h3 class="happiest-hours-section-title">BITES</h3>
                                <div class="menu-items-list">
                                    <?php if ($daily_specials_data && !empty($daily_specials_data['bites'])): ?>
                                        <?php foreach ($daily_specials_data['bites'] as $bite_item):
                                        $image = $bite_item['item_image']; ?>
                                            <div class="menu-item">
                                                <div class="menu-item-content">
                                                    <div class="menu-item-header">
                                                        <h4 class="menu-item-title"><?php echo esc_html($bite_item['item_name']); ?></h4>
                                                    <div class="menu-item-icons">
                                                        <!-- Camera icon only if there's an image -->
                                                        <?php if (!empty($image)): ?>
                                                            <img src="<?php echo esc_url(MRMIKES_PLUGIN_URL . 'assets/img/camera_icon.png'); ?>" alt="Camera" class="menu-item-camera" data-item-image-id="<?php echo $image; ?>">
                                                        <?php endif; ?>
                                                    </div>
                                                    </div>
                                                    <?php if (!empty($bite_item['description'])): ?>
                                                        <div class="menu-item-description"><?php echo wp_kses_post($bite_item['description']); ?></div>
                                                    <?php endif; ?>
                                                    <?php
                                                    // Get tier-based price for bites
                                                    $bite_price = '';
                                                    if ($selected_location && !empty($selected_location['pricing_tier'])) {
                                                        $location_tier = $selected_location['pricing_tier']; // 'tier_1' or 'tier_2'

                                                        if ($location_tier === 'tier_1' && !empty($bite_item['tier_1_price'])) {
                                                            $bite_price = $bite_item['tier_1_price'];
                                                        } elseif ($location_tier === 'tier_2' && !empty($bite_item['tier_2_price'])) {
                                                            $bite_price = $bite_item['tier_2_price'];
                                                        }
                                                    }

                                                    if (!empty($bite_price)): ?>
                                                        <div class="menu-item-price"><?php echo esc_html($bite_price); ?></div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Booze Section -->
                            <div class="happiest-hours-section">
                                <h3 class="happiest-hours-section-title">BOOZE</h3>
                                <div class="menu-items-list">
                                    <?php if ($daily_specials_data && !empty($daily_specials_data['booze'])): ?>
                                        <?php foreach ($daily_specials_data['booze'] as $booze_item):
                                        $image = $booze_item['item_image']; ?>
                                            <div class="menu-item">
                                                <div class="menu-item-content">
                                                    <div class="menu-item-header">
                                                        <h4 class="menu-item-title"><?php echo esc_html($booze_item['item_name']); ?></h4>
                                                    <div class="menu-item-icons">
                                                        <!-- Camera icon only if there's an image -->
                                                        <?php if (!empty($image)): ?>
                                                            <img src="<?php echo esc_url(MRMIKES_PLUGIN_URL . 'assets/img/camera_icon.png'); ?>" alt="Camera" class="menu-item-camera" data-item-image-id="<?php echo $image; ?>">
                                                        <?php endif; ?>
                                                    </div>
                                                    </div>
                                                    <?php if (!empty($booze_item['description'])): ?>
                                                        <div class="menu-item-description"><?php echo wp_kses_post($booze_item['description']); ?></div>
                                                    <?php endif; ?>
                                                    <?php
                                                    // Get tier-based price for booze
                                                    $booze_price = '';
                                                    if ($selected_location && !empty($selected_location['pricing_tier'])) {
                                                        $location_tier = $selected_location['pricing_tier']; // 'tier_1' or 'tier_2'

                                                        if ($location_tier === 'tier_1' && !empty($booze_item['tier_1_price'])) {
                                                            $booze_price = $booze_item['tier_1_price'];
                                                        } elseif ($location_tier === 'tier_2' && !empty($booze_item['tier_2_price'])) {
                                                            $booze_price = $booze_item['tier_2_price'];
                                                        }
                                                    }

                                                    if (!empty($booze_price)): ?>
                                                        <div class="menu-item-price"><?php echo esc_html($booze_price); ?></div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>

                        </div>

                    <?php elseif ($section['content'] === 'daily_specials'): ?>

                        <!-- Daily Specials Content -->
                        <div class="daily-specials-content">
                            <?php
                            // Get daily specials data
                            $daily_specials_data = function_exists('mrmikes_get_daily_specials_data') ? mrmikes_get_daily_specials_data() : false;

                            // Define the days and their corresponding field names
                            $daily_specials_days = array(
                                array('day' => 'SUN', 'field' => 'sunday_specials_global'),
                                array('day' => 'MON', 'field' => 'monday_specials_global'),
                                array('day' => 'TUES', 'field' => 'tuesday_specials_global'),
                                array('day' => 'WED', 'field' => 'wednesday_specials_global'),
                                array('day' => 'THURS', 'field' => 'thursday_specials_global'),
                                array('day' => 'FRI', 'field' => 'friday_specials_global'),
                                array('day' => 'SAT', 'field' => 'saturday_specials_global')
                            );

                            foreach ($daily_specials_days as $day_info):
                                $day_items = $daily_specials_data && isset($daily_specials_data[$day_info['field']]) ? $daily_specials_data[$day_info['field']] : false;

                                // Only show the day if it has items
                                if ($day_items && !empty($day_items)):
                            ?>
                                <div class="daily-special-day">
                                    <h3 class="daily-special-day-title"><?php echo esc_html($day_info['day']); ?></h3>
                                    <div class="menu-items-list">
                                        <?php foreach ($day_items as $item):
                                        $image = $item['item_image'];
                                        ?>
                                            <div class="menu-item">
                                                <div class="menu-item-content">
                                                    <div class="menu-item-header">
                                                        <h4 class="menu-item-title"><?php echo esc_html($item['item_name']); ?></h4>
                                                    <div class="menu-item-icons">
                                                        <!-- Camera icon only if there's an image -->
                                                        <?php if (!empty($image)): ?>
                                                            <img src="<?php echo esc_url(MRMIKES_PLUGIN_URL . 'assets/img/camera_icon.png'); ?>" alt="Camera" class="menu-item-camera" data-item-image-id="<?php echo $image; ?>">
                                                        <?php endif; ?>
                                                    </div>
                                                    </div>
                                                    <?php if (!empty($item['description'])): ?>
                                                        <div class="menu-item-description"><?php echo wp_kses_post($item['description']); ?></div>
                                                    <?php endif; ?>
                                                    <?php
                                                    // Get tier-based price for daily specials
                                                    $daily_price = '';
                                                    if ($selected_location && !empty($selected_location['pricing_tier'])) {
                                                        $location_tier = $selected_location['pricing_tier']; // 'tier_1' or 'tier_2'

                                                        if ($location_tier === 'tier_1' && !empty($item['tier_1_price'])) {
                                                            $daily_price = $item['tier_1_price'];
                                                        } elseif ($location_tier === 'tier_2' && !empty($item['tier_2_price'])) {
                                                            $daily_price = $item['tier_2_price'];
                                                        }
                                                    }

                                                    if (!empty($daily_price)): ?>
                                                        <div class="menu-item-price"><?php echo esc_html($daily_price); ?></div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php
                                endif;
                            endforeach;
                            ?>
                        </div>

                    <?php elseif ($section['content'] === 'thursdays_lodge_night'): ?>

                        <!-- Lunch Like a Local Content -->
                        <?php
                        $daily_specials_data = function_exists('mrmikes_get_daily_specials_data') ? mrmikes_get_daily_specials_data() : false;
                        $lodge_night_items = $daily_specials_data && isset($daily_specials_data['thursday_lodge_night_global']) ? $daily_specials_data['thursday_lodge_night_global'] : false;
                        $lodge_night_subtitle = $daily_specials_data && isset($daily_specials_data['thursday_lodge_night_subtitle']) ? $daily_specials_data['thursday_lodge_night_subtitle'] : 'THURSDAYS 8PM - CLOSE';
                        ?>

                        <?php if ($lodge_night_items && !empty($lodge_night_items)): ?>
                            <div class="daily-specials-content">
                                <div class="daily-special-day">
                                    <h3 class="daily-special-day-title"><?php echo esc_html($lodge_night_subtitle); ?></h3>
                                    <div class="menu-items-list">
                                        <?php foreach ($lodge_night_items as $item):
                                        $image = $item['item_image'];
                                        ?>
                                            <div class="menu-item">
                                                <div class="menu-item-content">
                                                    <div class="menu-item-header">
                                                        <h4 class="menu-item-title"><?php echo esc_html($item['item_name']); ?></h4>
                                                    <?php if (!empty($image)): ?>
                                                            <img src="<?php echo esc_url(MRMIKES_PLUGIN_URL . 'assets/img/camera_icon.png'); ?>" alt="Camera" class="menu-item-camera" data-item-image-id="<?php echo $image; ?>">
                                                        <?php endif; ?>
                                                    </div>
                                                    <?php if (!empty($item['description'])): ?>
                                                        <div class="menu-item-description"><?php echo wp_kses_post($item['description']); ?></div>
                                                    <?php endif; ?>
                                                    <?php
                                                    // Get tier-based price for lodge night
                                                    $lodge_price = '';
                                                    if ($selected_location && !empty($selected_location['pricing_tier'])) {
                                                        $location_tier = $selected_location['pricing_tier']; // 'tier_1' or 'tier_2'

                                                        if ($location_tier === 'tier_1' && !empty($item['tier_1_price'])) {
                                                            $lodge_price = $item['tier_1_price'];
                                                        } elseif ($location_tier === 'tier_2' && !empty($item['tier_2_price'])) {
                                                            $lodge_price = $item['tier_2_price'];
                                                        }
                                                    }

                                                    if (!empty($lodge_price)): ?>
                                                        <div class="menu-item-price"><?php echo esc_html($lodge_price); ?></div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                    <?php else: ?>
                        <!-- Default placeholder content for other sections -->
                        <div class="menu-items-list">
                            <p class="no-menu-items"><?php echo esc_html($section['content']); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php
        $specials_counter++;
        $specials_section_index++;
    endforeach;
    ?>

</div>
<!-- End Daily Specials Tab Content -->
</div>
<!-- End Tab Content Wrapper -->

<?php element('footer'); ?>