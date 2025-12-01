<?php
/**
 * Plugin Name: MrMikes Menu
 * Description: A menu management system for MrMikes restaurant chain.
 * Version: 1.0.0
 * Author: Bare
 * License: GPL v2 or later
 * Text Domain: mrmikes-menu
 */
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
// Define plugin constants
define('MRMIKES_PLUGIN_URL', plugin_dir_url(__FILE__));
define('MRMIKES_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('MRMIKES_VERSION', '1.0.6');
/**
 * Main MrMikes Menu Plugin Class
 */
class MrMikesMenu {
    /**
     * Constructor
     */
    public function __construct() {
        add_action('init', array($this, 'init'));
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }
    /**
     * Initialize the plugin
     */
    public function init() {
        // Load text domain for translations
        load_plugin_textdomain('mrmikes-menu', false, dirname(plugin_basename(__FILE__)) . '/languages');

        // Load includes
        $this->load_includes();

        // Add custom rewrite rules - hook directly to init
        $this->add_rewrite_rules();

        // Add query vars
        add_filter('query_vars', array($this, 'add_query_vars'));

        // Handle custom redirects
        add_action('template_redirect', array($this, 'handle_menu_redirects'));

        // Enqueue custom CSS on frontend
        add_action('wp_enqueue_scripts', array($this, 'enqueue_custom_styles'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_template_styles_if_needed'));

        // Add custom page template
        add_filter('theme_page_templates', array($this, 'add_page_template'));
        add_filter('page_template', array($this, 'redirect_page_template'));

        // Add restaurant schema to head
        add_action('wp_head', array($this, 'add_restaurant_schema'));

        // Plugin is initialized but no functionality added yet
        $this->add_admin_notice();
    }

    /**
     * Load include files
     */
    private function load_includes() {
        // Load shortcodes
        if (file_exists(MRMIKES_PLUGIN_PATH . 'inc/shortcodes.php')) {
            require_once MRMIKES_PLUGIN_PATH . 'inc/shortcodes.php';
        }

        // Load AJAX handler
        if (file_exists(MRMIKES_PLUGIN_PATH . 'inc/ajax-handler.php')) {
            require_once MRMIKES_PLUGIN_PATH . 'inc/ajax-handler.php';
        }

        // Load ACF fields
        if (file_exists(MRMIKES_PLUGIN_PATH . 'inc/acf-fields.php')) {
            require_once MRMIKES_PLUGIN_PATH . 'inc/acf-fields.php';
        }
    }

    /**
     * Enqueue custom CSS styles
     */
    public function enqueue_custom_styles() {
        wp_enqueue_style(
            'mrmikes-custom',
            MRMIKES_PLUGIN_URL . 'assets/css/custom.css',
            array(),
            MRMIKES_VERSION
        );
    }

    /**
     * Enqueue template-specific CSS styles if needed
     */
    public function enqueue_template_styles_if_needed() {
        global $post;

        if (is_page() && $post) {
            $page_template = get_post_meta($post->ID, '_wp_page_template', true);
            if ($page_template == 'mrmikes-menu-template.php') {
                $this->enqueue_template_styles();
                $this->enqueue_template_js();
            }
        }
    }

    /**
     * Enqueue template-specific CSS styles
     */
    public function enqueue_template_styles() {
        wp_enqueue_style(
            'mrmikes-template',
            MRMIKES_PLUGIN_URL . 'assets/css/template.css',
            array(),
            MRMIKES_VERSION
        );
    }

    /**
     * Enqueue template-specific JavaScript
     */
    public function enqueue_template_js() {
        wp_enqueue_script(
            'mrmikes-template-js',
            MRMIKES_PLUGIN_URL . 'assets/js/template.js',
            array('jquery'),
            MRMIKES_VERSION,
            true
        );

        // Localize script with AJAX data
        wp_localize_script('mrmikes-template-js', 'mrmikes_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('mrmikes_ajax_nonce')
        ));
    }

    /**
     * Add custom page template to dropdown
     */
    public function add_page_template($templates) {
        global $post;

        // Check if template is already in use by another page
        if ($this->is_template_in_use($post->ID ?? 0)) {
            // Don't add the template to the dropdown
            return $templates;
        }

        $templates['mrmikes-menu-template.php'] = __('MrMikes Menu Template', 'mrmikes-menu');
        return $templates;
    }

    /**
     * Check if the MrMikes menu template is already in use by another page
     */
    private function is_template_in_use($exclude_post_id = 0) {
        $existing_pages = get_posts(array(
            'post_type' => 'page',
            'post_status' => array('publish', 'draft', 'private'),
            'meta_key' => '_wp_page_template',
            'meta_value' => 'mrmikes-menu-template.php',
            'numberposts' => 1,
            'exclude' => array($exclude_post_id)
        ));

        return !empty($existing_pages);
    }

    /**
     * Add custom rewrite rules for location-based menu URLs
     */
    public function add_rewrite_rules() {
        // Add rewrite rule for /locations/{location-slug}/menus/
        add_rewrite_rule(
            '^locations/([^/]+)/menus/?$',
            'index.php?mrmikes_location=$matches[1]',
            'top'
        );

        // Add rewrite rule for /locations/{location-slug}/menus/{menu-type}
        add_rewrite_rule(
            '^locations/([^/]+)/menus/([^/]+)/?$',
            'index.php?mrmikes_location=$matches[1]&mrmikes_menu_type=$matches[2]',
            'top'
        );
    }

    /**
     * Add custom query vars
     */
    public function add_query_vars($vars) {
        $vars[] = 'mrmikes_location';
        $vars[] = 'mrmikes_menu_type';
        return $vars;
    }

    /**
     * Handle custom menu URLs by serving content directly
     */
    public function handle_menu_redirects() {
        $location_slug = get_query_var('mrmikes_location');
        $menu_type = get_query_var('mrmikes_menu_type');

        // If we have our custom query vars, set up to serve the menu template directly
        if (!empty($location_slug)) {

            $restaurant = get_posts([
                'post_type'      => 'restaurant',
                'name'           => $location_slug,
                'posts_per_page' => 1,
                'post_status'    => 'publish',
            ]);

            if ($restaurant) {
                $restaurant_id = $restaurant[0]->ID;
                // Check if opening_soon ACF field is true
                if (get_field('location_status', $restaurant_id) == 'opening_soon') {
                    // Redirect to the restaurant's single page
                    wp_safe_redirect(get_permalink($restaurant_id), 301);
                    exit;
                }
            }

            // Set the query vars in $_GET so the template can access them
            $_GET['location'] = $location_slug;
            if (!empty($menu_type)) {
                $_GET['menu_type'] = $menu_type;
            }

            // Find the menu page that uses our template
            $menu_page = $this->get_menu_page();

            if ($menu_page) {
                // Set up WordPress to think we're viewing the menu page
                global $post, $wp_query;

                // Override the main query
                $wp_query->query_vars['page_id'] = $menu_page->ID;
                $wp_query->queried_object = $menu_page;
                $wp_query->queried_object_id = $menu_page->ID;
                $wp_query->is_page = true;
                $wp_query->is_singular = true;
                $wp_query->is_home = false;
                $wp_query->is_archive = false;
                $wp_query->is_category = false;
                $wp_query->is_404 = false;

                // Set the global post
                $post = $menu_page;
                setup_postdata($post);

                // Let WordPress handle the rest naturally
                // The template will be loaded by our existing redirect_page_template function
            }
        }
    }

    /**
     * Get the page object using the MrMikes menu template
     */
    private function get_menu_page() {
        $menu_pages = get_posts(array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'meta_key' => '_wp_page_template',
            'meta_value' => 'mrmikes-menu-template.php',
            'numberposts' => 1
        ));

        if (!empty($menu_pages)) {
            return $menu_pages[0];
        }

        return false;
    }

    /**
     * Get the URL of the page using the MrMikes menu template
     */
    private function get_menu_page_url() {
        $menu_page = $this->get_menu_page();
        if ($menu_page) {
            return get_permalink($menu_page->ID);
        }
        return false;
    }

    /**
     * Debug function to check rewrite rules (remove this in production)
     */
    public function debug_rewrite_rules() {
        global $wp_rewrite;
        error_log("=== Rewrite Rules Debug ===");
        error_log("All rewrite rules: " . print_r($wp_rewrite->wp_rewrite_rules(), true));

        // Check if our specific rules exist
        $rules = get_option('rewrite_rules');
        error_log("Our rules in database:");
        foreach ($rules as $pattern => $rule) {
            if (strpos($pattern, 'locations') !== false) {
                error_log("Pattern: " . $pattern . " => Rule: " . $rule);
            }
        }
    }

    /**
     * Redirect to our custom template when selected
     */
    public function redirect_page_template($template) {
        global $post;
        // Get the template slug
        $page_template = get_post_meta($post->ID, '_wp_page_template', true);
        // If our template is selected, use our custom template file
        if ($page_template == 'mrmikes-menu-template.php') {
            $custom_template = MRMIKES_PLUGIN_PATH . 'templates/mrmikes-menu-template.php';
            // Check if template file exists
            if (file_exists($custom_template)) {
                $template = $custom_template;
            }
        }
        return $template;
    }

    public function add_admin_notice() {
        add_action('admin_notices', function() {
            if (get_transient('mrmikes_menu_activated')) {
                echo '<div class="notice notice-success is-dismissible">';
                echo '<p><strong>MrMikes Menu</strong> plugin has been activated successfully!</p>';
                echo '</div>';
                delete_transient('mrmikes_menu_activated');
            }
        });
    }

    /**
     * Add Restaurant Schema JSON-LD to single restaurant pages
     */
    public function add_restaurant_schema() {
        // Only add schema on single restaurant pages
        if (!is_singular('restaurant')) {
            return;
        }

        global $post;
        $restaurant_id = $post->ID;

        // Get all the ACF fields for this restaurant
        $headline = get_field('headline', $restaurant_id);
        $address = get_field('address', $restaurant_id);
        $override_address = get_field('override_address', $restaurant_id);
        $raw_address = get_field('raw_address', $restaurant_id);
        $street = get_field('street', $restaurant_id);
        $city = get_field('city', $restaurant_id);
        $postal_code = get_field('postal_code', $restaurant_id);
        $province = get_field('province', $restaurant_id);
        $pricing_tier = get_field('pricing_tier', $restaurant_id);
        $email = get_field('email', $restaurant_id);
        $phone = get_field('phone', $restaurant_id);
        $social_media = get_field('social_media', $restaurant_id);
        $open_table_id = get_field('open_table_id', $restaurant_id);
        $delivery_url = get_field('skip_the_dishes', $restaurant_id); // This field is used for all delivery now
        $xdineapp = get_field('xdineapp', $restaurant_id);
        $takeout_menu = get_field('takeout_menu', $restaurant_id);
        $take_out_or_delivery = get_field('take_out_or_delivery', $restaurant_id);
        $door_dash = get_field('door_dash', $restaurant_id);
        $feastify = get_field('feastify', $restaurant_id);
        $dash_delivers = get_field('dash_delivers', $restaurant_id);

        // Get featured image
        $featured_image = get_the_post_thumbnail_url($restaurant_id, 'large');

        // Build the schema
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Restaurant',
            'name' => get_the_title($restaurant_id),
            'description' => $headline ? $headline : 'Casual steakhouse restaurant serving quality steaks and comfort food in a relaxed atmosphere.',
            'url' => get_permalink($restaurant_id)
        );

        // Add contact information
        if ($phone) {
            $schema['telephone'] = $phone;
        }
        if ($email) {
            $schema['email'] = $email;
        }

        // Add featured image
        if ($featured_image) {
            $schema['image'] = $featured_image;
        }

        // Build address
        $address_data = array(
            '@type' => 'PostalAddress',
            'addressCountry' => 'CA'
        );

        if ($override_address && $raw_address) {
            // Use raw address if override is enabled
            $address_parts = explode(',', $raw_address);
            if (count($address_parts) >= 1) {
                $address_data['streetAddress'] = trim($address_parts[0]);
            }
            if (count($address_parts) >= 2) {
                $address_data['addressLocality'] = trim($address_parts[1]);
            }
            if (count($address_parts) >= 3) {
                $address_data['addressRegion'] = trim($address_parts[2]);
            }
        } else {
            // Use individual address fields
            if ($street) {
                $address_data['streetAddress'] = $street;
            }
            if ($city) {
                $address_data['addressLocality'] = $city;
            }
            if ($province) {
                $address_data['addressRegion'] = $province;
            }
            if ($postal_code) {
                $address_data['postalCode'] = $postal_code;
            }
        }

        // Add address if we have at least street address
        if (isset($address_data['streetAddress'])) {
            $schema['address'] = $address_data;
        }

        // Add geographic coordinates if available from Google Map field
        if ($address && is_array($address)) {
            if (isset($address['lat']) && isset($address['lng'])) {
                $schema['geo'] = array(
                    '@type' => 'GeoCoordinates',
                    'latitude' => $address['lat'],
                    'longitude' => $address['lng']
                );
            }
        }

        // Add cuisine and price range
        $schema['servesCuisine'] = array('Steakhouse', 'Canadian');

        // Business properties
        $schema['acceptsReservations'] = !empty($open_table_id);
        $schema['takeaway'] = true;
        $schema['delivery'] = !empty($delivery_url);
        $schema['smokingAllowed'] = false;
        $schema['paymentAccepted'] = array('Cash', 'Credit Card', 'Debit Card');
        $schema['currenciesAccepted'] = 'CAD';

        // Add menu URL - construct the menu URL for this location
        $location_slug = sanitize_title(get_the_title($restaurant_id));
        $menu_url = home_url('/locations/' . $location_slug . '/menus/food/');
        $schema['hasMenu'] = $menu_url;
        $schema['menu'] = $menu_url;

        // Add social media links
        $same_as = array();
        if ($social_media) {
            if (!empty($social_media['facebook'])) {
                $same_as[] = $social_media['facebook'];
            }
            if (!empty($social_media['instagram'])) {
                $same_as[] = $social_media['instagram'];
            }
        }
        if (!empty($same_as)) {
            $schema['sameAs'] = $same_as;
        }

        // Collect delivery partners
        $delivery_partners = array();
        if ($delivery_url) $delivery_partners[] = 'Skip The Dishes';
        if ($door_dash && is_array($door_dash) && !empty($door_dash['url'])) $delivery_partners[] = 'DoorDash';
        if ($feastify && is_array($feastify) && !empty($feastify['url'])) $delivery_partners[] = 'Feastify';
        if ($dash_delivers && is_array($dash_delivers) && !empty($dash_delivers['url'])) $delivery_partners[] = 'Dash Delivers';

        if (!empty($delivery_partners)) {
            $additional_properties[] = array(
                '@type' => 'PropertyValue',
                'name' => 'Delivery Partners',
                'value' => $delivery_partners
            );
        }

        if (!empty($additional_properties)) {
            $schema['additionalProperty'] = $additional_properties;
        }

        // Add offers
        $offers = array();

        // Takeout menu offer
        if ($takeout_menu) {
            $takeout_description = $take_out_or_delivery ? 'Order for Take Out or Delivery' : 'Takeout Menu (phone us + pickup)';
            $offers[] = array(
                '@type' => 'Offer',
                'name' => 'Takeout Menu',
                'description' => $takeout_description,
                'url' => $takeout_menu
            );
        }

        // Delivery offers
        if ($delivery_url) {
            $offers[] = array(
                '@type' => 'Offer',
                'name' => 'Online Ordering',
                'description' => 'Order online through delivery partners',
                'url' => is_array($delivery_url) ? $delivery_url['url'] : $delivery_url
            );
        }

        // XDineApp offer
        if ($xdineapp) {
            $offers[] = array(
                '@type' => 'Offer',
                'name' => 'XDineApp',
                'description' => 'Order through XDineApp',
                'url' => is_array($xdineapp) ? $xdineapp['url'] : $xdineapp
            );
        }

        if (!empty($offers)) {
            $schema['makesOffer'] = $offers;
        }

        // Add potential actions
        $potential_actions = array();

        // Reservation action
        if ($open_table_id) {
            $opentable_url = 'https://www.opentable.com/restref/client/?restref=' . $open_table_id;
            $potential_actions[] = array(
                '@type' => 'ReserveAction',
                'target' => array(
                    '@type' => 'EntryPoint',
                    'urlTemplate' => $opentable_url,
                    'name' => 'Make Reservation'
                )
            );
        }

        // Order action
        if ($delivery_url) {
            $potential_actions[] = array(
                '@type' => 'OrderAction',
                'target' => array(
                    '@type' => 'EntryPoint',
                    'urlTemplate' => is_array($delivery_url) ? $delivery_url['url'] : $delivery_url,
                    'name' => 'Order Delivery'
                )
            );
        }

        if (!empty($potential_actions)) {
            $schema['potentialAction'] = $potential_actions;
        }

        /*// Add opening hours - generic for now, could be enhanced with actual hours data
        $schema['openingHours'] = array('Mo-Su 11:00-23:00');
        */

        // Get opening hours data
        $opening_hours = get_field('opening_hours', $restaurant_id);

        // Build opening hours array for schema
        $opening_hours_schema = array();

        if ($opening_hours) {
            // Days mapping for schema format
            $days_mapping = array(
                'mo' => 'Mo',
                'tu' => 'Tu',
                'we' => 'We',
                'th' => 'Th',
                'fr' => 'Fr',
                'sa' => 'Sa',
                'su' => 'Su'
            );

            foreach ($days_mapping as $day_key => $day_abbr) {
                // Check if day is marked as closed
                $is_closed = isset($opening_hours[$day_key . '_closed']) ? $opening_hours[$day_key . '_closed'] : false;

                if (!$is_closed) {
                    // Get from and to times
                    $from_time = isset($opening_hours[$day_key . '_from']) ? $opening_hours[$day_key . '_from'] : '';
                    $to_time = isset($opening_hours[$day_key . '_to']) ? $opening_hours[$day_key . '_to'] : '';

                    if (!empty($from_time) && !empty($to_time)) {
                        // Convert time format from "11:00 am" to "11:00"
                        $from_24h = date('H:i', strtotime($from_time));
                        $to_24h = date('H:i', strtotime($to_time));

                        $opening_hours_schema[] = $day_abbr . ' ' . $from_24h . '-' . $to_24h;
                    }
                }
            }
        }

        // Add opening hours to schema, fallback to generic if no specific hours
        if (!empty($opening_hours_schema)) {
            $schema['openingHours'] = $opening_hours_schema;
        } else {
            $schema['openingHours'] = array('Mo-Su 11:00-23:00');
        }

        // Output the schema
        echo '<script type="application/ld+json">' . "\n";
        echo wp_json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
        echo '</script>' . "\n";
    }

    /**
     * Plugin activation
     */
    public function activate() {
        // Add rewrite rules
        $this->add_rewrite_rules();

        // Set a transient to show activation notice
        set_transient('mrmikes_menu_activated', true, 30);

        // Flush rewrite rules to ensure they're registered
        flush_rewrite_rules();

        // Debug - check if rules were added (remove in production)
        $this->debug_rewrite_rules();
    }

    /**
     * Plugin deactivation
     */
    public function deactivate() {
        // Clean up on deactivation
        flush_rewrite_rules();
    }
}
// Initialize the plugin
new MrMikesMenu();