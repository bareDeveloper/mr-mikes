<?php
/**
 * MrMikes Menu Shortcodes
 *
 * This file contains all shortcode functionality for the MrMikes Menu plugin
 */
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
/**
 * MrMikes Menu Shortcodes Class
 */
class MrMikes_Shortcodes {
    /**
     * Constructor
     */
    public function __construct() {
        $this->register_shortcodes();
    }
    /**
     * Register all shortcodes
     */
    public function register_shortcodes() {
        add_shortcode('mrmikes_location', array($this, 'location_shortcode'));
    }
    /**
     * Location shortcode
     * Usage: [mrmikes_location]
     */
    public function location_shortcode($atts) {
        // Enqueue assets for this shortcode
        $this->enqueue_location_assets();

        // Get plugin URL
        $plugin_url = plugin_dir_url(__FILE__);
        $plugin_url = str_replace('/inc/', '/', $plugin_url);

        // Parse shortcode attributes
        $atts = shortcode_atts(array(
            'class' => '',
            'style' => ''
        ), $atts, 'mrmikes_location');
        // Build CSS classes
        $css_classes = 'mrmikes-location-selector';
        if (!empty($atts['class'])) {
            $css_classes .= ' ' . sanitize_html_class($atts['class']);
        }
        // Build inline styles
        $inline_styles = '';
        if (!empty($atts['style'])) {
            $inline_styles = ' style="' . esc_attr($atts['style']) . '"';
        }

        // Get provinces data for JavaScript
        $provinces_data = $this->get_provinces_with_locations();

        // Get selected location info for display
        $selected_location_text = __('Set your location', 'mrmikes-menu');
        $selected_location_id = isset($_COOKIE['mrmikes_selected_location']) ? intval($_COOKIE['mrmikes_selected_location']) : 0;

        if ($selected_location_id) {
            $location_post = get_post($selected_location_id);
            if ($location_post && $location_post->post_type === 'restaurant' && $location_post->post_status === 'publish') {
                $selected_location_text = $location_post->post_title;
            }
        }

        // Output the shortcode content (trigger only)
        ob_start();
        ?>
        <div class="<?php echo esc_attr($css_classes); ?>"<?php echo $inline_styles; ?>>
            <img src="<?php echo esc_url($plugin_url . 'assets/img/mrm-location-pin.svg'); ?>" alt="Location pin" class="mrmikes-location-pin">
            <a href="#" class="mrmikes-location-trigger"><?php echo esc_html($selected_location_text); ?></a>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Enqueue location selector assets
     */
    private function enqueue_location_assets() {
        // Enqueue CSS
        wp_enqueue_style(
            'mrmikes-location-popup',
            MRMIKES_PLUGIN_URL . 'assets/css/location-popup.css',
            array(),
            rand(1000, 9999)
        );

        // Enqueue JavaScript
        wp_enqueue_script(
            'mrmikes-location-popup',
            MRMIKES_PLUGIN_URL . 'assets/js/location-popup.js',
            array('jquery'),
            rand(1000, 9999),
            true
        );

        // Pass data to JavaScript
        $menu_page_url = $this->get_menu_page_url();
        $provinces_data = $this->get_provinces_with_locations();

        // Get location slugs for redirect
        $location_slugs = $this->get_location_slugs();

        wp_localize_script('mrmikes-location-popup', 'mrmikes_vars', array(
            'menu_page_url' => $menu_page_url ? $menu_page_url : '',
            'plugin_url' => MRMIKES_PLUGIN_URL,
            'provinces_data' => $provinces_data,
            'location_slugs' => $location_slugs, // Pass location slugs
            'strings' => array(
                'set_location' => __('Set your location', 'mrmikes-menu'),
                'search_placeholder' => __('Search', 'mrmikes-menu'),
                'no_locations' => __('No locations available.', 'mrmikes-menu')
            )
        ));
    }

    /**
     * Find page that uses MrMikes template
     */
    private function get_menu_page_url() {
        $menu_pages = get_posts(array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'meta_key' => '_wp_page_template',
            'meta_value' => 'mrmikes-menu-template.php',
            'numberposts' => 1
        ));

        if (!empty($menu_pages)) {
            return get_permalink($menu_pages[0]->ID);
        }

        return false;
    }

    /**
     * Get location slugs mapped by location ID
     */
    private function get_location_slugs() {
        // Query all published restaurant posts
        $restaurants = get_posts(array(
            'post_type' => 'restaurant',
            'post_status' => 'publish',
            'numberposts' => -1
        ));

        $location_slugs = array();

        foreach ($restaurants as $restaurant) {
            // Use the full post title including province abbreviation
            $full_slug = sanitize_title($restaurant->post_title);
            $location_slugs[$restaurant->ID] = $full_slug;
        }

        return $location_slugs;
    }

    /**
     * Get list of provinces from restaurant locations
     */
    private function get_provinces_list() {
        $provinces_data = $this->get_provinces_with_locations();

        if (empty($provinces_data)) {
            return '<p>' . esc_html__('No locations available.', 'mrmikes-menu') . '</p>';
        }

        $output = '<div class="mrmikes-provinces-accordion">';

        foreach ($provinces_data as $province => $locations) {
            if (!empty($province)) {
                $output .= '<div class="mrmikes-province-section">';

                // Province header with dropdown icon
                $output .= '<div class="mrmikes-province-header" data-province="' . esc_attr($province) . '">';
                $output .= '<span class="province-name">' . esc_html($province) . '</span>';
                $output .= '<img src="' . MRMIKES_PLUGIN_URL . 'assets/img/dropdown_arrow.png" alt="" class="dropdown-icon" />'; // Down arrow
                $output .= '</div>';

                // Locations list (initially hidden)
                $output .= '<div class="mrmikes-locations-list" style="display: none;">';
                foreach ($locations as $location) {
                    $output .= '<div class="mrmikes-location-item">';
                    $output .= '<a href="#" class="mrmikes-location-link" data-location-id="' . esc_attr($location['id']) . '" data-location-slug="' . esc_attr($location['slug']) . '">';
                    $output .= '<span class="location-city">' . esc_html($location['name']) . '</span>';
                    if (!empty($location['street'])) {
                        $output .= '<span class="location-street">' . esc_html($location['street']) . '</span>';
                    }
                    $output .= '</a>';
                    $output .= '</div>';
                }
                $output .= '</div>';

                $output .= '</div>';
            }
        }

        $output .= '</div>';

        return $output;
    }

    /**
     * Get provinces with their locations
     */
    private function get_provinces_with_locations() {
        // Query all published restaurant posts that have provinces taxonomy assigned
        $restaurants = get_posts(array(
            'post_type' => 'restaurant',
            'post_status' => 'publish',
            'numberposts' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
            'tax_query' => array(
                array(
                    'taxonomy' => 'provinces',
                    'operator' => 'EXISTS'
                )
            )
        ));

        $provinces_data = array();

        // Group locations by province
        foreach ($restaurants as $restaurant) {
            // Get the provinces taxonomy terms for this restaurant
            $province_terms = wp_get_post_terms($restaurant->ID, 'provinces');

            if (!empty($province_terms) && !is_wp_error($province_terms)) {
                // Use the first province term (assuming restaurants belong to one province)
                $province = $province_terms[0]->name;

                $address = get_field('address', $restaurant->ID);

                if (!empty($province)) {
                    if (!isset($provinces_data[$province])) {
                        $provinces_data[$province] = array();
                    }

                    // Get street address for display
                    $street_address = '';
                    if (!empty($address)) {
                        if (is_array($address)) {
                            // If address is an array, get street number and street name
                            if (!empty($address['street_number']) && !empty($address['street_name'])) {
                                $street_address = $address['street_number'] . ' ' . $address['street_name'];
                            }
                        } else {
                            // If address is a string, extract the first part (street address)
                            $address_parts = explode(',', $address);
                            $street_address = trim($address_parts[0]);
                        }
                    }

                    // Generate location slug
                    $location_slug = $this->generate_location_slug($restaurant->post_title);

                    $provinces_data[$province][] = array(
                        'id' => $restaurant->ID,
                        'name' => $this->clean_location_name($restaurant->post_title),
                        'location_status' => get_field('location_status', $restaurant->ID),
                        'street' => $street_address,
                        'slug' => $location_slug,
                        'permalink' => get_permalink($restaurant->ID)
                    );
                }
            }
        }

        // Sort provinces alphabetically
        ksort($provinces_data);

        return $provinces_data;
    }

    /**
     * Generate location slug from restaurant title
     */
    private function generate_location_slug($restaurant_title) {
        // Use the full title including province abbreviation
        // Just sanitize it as a slug, keeping the province
        $slug = sanitize_title($restaurant_title);

        return $slug;
    }

    /**
     * Clean location name by removing province abbreviations
     */
    private function clean_location_name($location_name) {
        // Remove province abbreviations with or without comma
        // Patterns: ", AB", ", BC", " AB", " BC", etc.
        $cleaned_name = preg_replace('/,?\s*[A-Z]{2}$/i', '', trim($location_name));

        return $cleaned_name;
    }
}
// Initialize the shortcodes class
new MrMikes_Shortcodes();