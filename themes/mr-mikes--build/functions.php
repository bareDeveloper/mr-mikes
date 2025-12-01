<?php

// 
// Include PHP functions by path from the basefolder. 
// 
// The first argument is a folder/file path (without .php) 
//      ex: "admin/" (include trailing slash)
// The second argument is an optional array of filenames (without .php) [ex. ]
//      ex: ["adminCss", "otherFile"]
//

add_theme_support( 'title-tag' );

// Admin CSS
inc( "admin/adminCSS" );

inc( "gravityforms/populate-restaurants" );
inc( "gravityforms/replace-ajax-spinner" );
inc( "gravityforms/custom-css-class-form-buttons" );

// Import custom advanced forms function
inc( "form/customRecipient", "", "elements/" );

// Import Ajax functions
inc( "newsletter/mailchimpSignup", "", "elements/" );

// Import ACF elements
inc( "button/acf", "", "elements/" );
inc( "headline/acf", "", "elements/" );

// Advanced Custom Fields
inc( "acf/googleMapKey" );
inc( "acf/optionsPages" );
inc( "acf/flexibleContentArea" );
inc( "acf/renameUploadedFiles" );
inc( "acf/moduleScreenshot" );
inc( "acf/af_validation" );

inc( "schema/addCustomField" );

// Atomic Components functions
inc( "atomic/addAtomic" );
inc( "atomic/flexibleContentFields" );
inc( "atomic/props" );

// Queries
inc( "queries/queryPosts" );
inc( "queries/postsPagination" );

// Inline SVG
inc( "svg/inlineSVG" );

// Menus
inc( "menus/bemMenu" );

// Optimization
inc( "optimization/", [
	"asyncBase64Fonts",
	"favicon",
	"sitemapGenerator",
	"getStaticUrl",
	"responsiveLazyloading"
] );

// Add WordPress functionalities
inc( "wordpress/activate/", [
	"featuredImage",
	"enqueueScripts",
	"registerMenus"
] );

// Remove WordPress functionalities
inc( "wordpress/disable/", [
	"emojicons",
	"oembed",
	"postInlineCSS",
	"themeUpdateCheck",
	"wpEmbed",
	"xmlRPC"
] );

inc( 'write_log' );

// add shortcode for newsletter footer form and popup form
inc( "newsletter/shortcode", "", "elements/" );

// add tracking pixel to Promotions page
inc( "tracking/", [
	"acuity",
] );


function inc( $filePath, $files = '', $baseFolder = "inc/" ) {

	if ( is_array( $files ) ) {
		// if an array is passed, loop through the array and include each file.
		foreach ( $files as $file ) {
			include_once( $baseFolder . $filePath . $file . ".php" );
		}

		return false;
	}

	// No array is found so just include from the filepath once
	return include_once( $baseFolder . $filePath . ".php" );

}


function wpdocs_theme_name_scripts() {
	//add rules for particular page templates
	wp_enqueue_style( 'franchise', get_template_directory_uri() . '/css/franchise.css' );
	//wp_enqueue_script( 'script-name', get_template_directory_uri() . '/js/example.js', array(), '1.0.0', true );
}

add_action( 'wp_enqueue_scripts', 'wpdocs_theme_name_scripts' );

//Register menu for blocks
function wpb_custom_new_menu() {
	register_nav_menu( 'footer-block-menu', __( 'Footer Block Gutenberg Menu' ) );
}

add_action( 'init', 'wpb_custom_new_menu' );

// Adding required Scripts and Styles for the Related Apply GF Form for Jobs Post Type
function gf_apply_form_enqueue_required_files() {
	GFCommon::log_debug( __METHOD__ . '(): running.' );

	if ( is_single() && get_post_type() === 'jobs' ) { // Do it only for Jobs

		// Pulling here the Related Apply Form
		$related_apply_form = get_field( 'related_apply_form', get_the_ID() );

		if ( ! empty( $related_apply_form ) ) {
			gravity_form_enqueue_scripts( $related_apply_form );
		}
	}
}

add_action( 'get_header', 'gf_apply_form_enqueue_required_files' );

// Add required Scripts and Styles for the Related GF Form for Form modules on the page
function gf_form_enqueue_required_files() {
	GFCommon::log_debug( __METHOD__ . '(): running.' );

	// Getting ACF Flexible field layouts
	$flexible_layouts = get_post_meta( get_the_ID(), 'modules', true );
	if ( ! empty( $flexible_layouts ) ) {

		$enqueued_form_ids = [];

		// Going through the all Layouts
		foreach ( $flexible_layouts as $row_index => $flexible_layout ) {

			// Looking for 'form' layout
			if ( $flexible_layout === 'form' ) {

				// Getting the GF Form ID
				$related_form_id = get_field( "modules_{$row_index}_related_gf_form" );

				// Enqueuing the necessary scripts if it was not enqueued before
				if ( ! empty( $related_form_id ) && ! in_array( $related_form_id, $enqueued_form_ids ) ) {
					gravity_form_enqueue_scripts( $related_form_id, true );
					$enqueued_form_ids[] = $related_form_id;
				}

			}

		}

	}

}

add_action( 'get_header', 'gf_form_enqueue_required_files' );

//Blocks
add_action( 'acf/init', 'register_card_l_block' );
function register_card_l_block() {

	if ( function_exists( 'acf_register_block_type' ) ) {

		// Register Card L block
		acf_register_block_type( array(
			'name'            => 'card-l',
			'title'           => __( 'Card L' ),
			'description'     => __( 'A custom Card L block.' ),
			'category'        => 'formatting',
			'icon'            => 'layout',
			'keywords'        => array( 'card', 'l' ),
			'post_types'      => array( 'post', 'page' ),
			'mode'            => 'auto',
			// 'align'				=> 'wide',
			'render_template' => 'template-parts/blocks/card-l.php',
			// 'render_callback'	=> 'card_l_block_render_callback',
			'enqueue_style'   => get_template_directory_uri() . '/css/franchise.css',
			// 'enqueue_script' 	=> get_template_directory_uri() . '/template-parts/blocks/card-l/card-l.js',
			// 'enqueue_assets' 	=> 'card_l_block_enqueue_assets',
		) );

	}

}

add_action( 'acf/init', 'register_card_r_block' );
function register_card_r_block() {

	if ( function_exists( 'acf_register_block_type' ) ) {

		// Register Card L block
		acf_register_block_type( array(
			'name'            => 'card-r',
			'title'           => __( 'Card R' ),
			'description'     => __( 'A custom Card R block.' ),
			'category'        => 'formatting',
			'icon'            => 'layout',
			'keywords'        => array( 'card', 'r' ),
			'post_types'      => array( 'post', 'page' ),
			'mode'            => 'auto',
			// 'align'				=> 'wide',
			'render_template' => 'template-parts/blocks/card-r.php',
			// 'render_callback'	=> 'card_l_block_render_callback',
			'enqueue_style'   => get_template_directory_uri() . '/css/franchise.css',
			// 'enqueue_script' 	=> get_template_directory_uri() . '/template-parts/blocks/card-r/card-r.js',
			// 'enqueue_assets' 	=> 'card_l_block_enqueue_assets',
		) );

	}

}

add_action( 'acf/init', 'register_reports_card_block' );
function register_reports_card_block() {

	if ( function_exists( 'acf_register_block_type' ) ) {

		// Register Reports Card block
		acf_register_block_type( array(
			'name'            => 'reports-card',
			'title'           => __( 'Reports Card' ),
			'description'     => __( 'A custom Reports Card block.' ),
			'category'        => 'formatting',
			'icon'            => 'layout',
			'keywords'        => array( 'reports', 'card' ),
			'post_types'      => array( 'post', 'page' ),
			'mode'            => 'auto',
			// 'align'				=> 'wide',
			'render_template' => 'template-parts/blocks/reports-card.php',
			// 'render_callback'	=> 'reports_card_block_render_callback',
			'enqueue_style'   => get_template_directory_uri() . '/css/franchise.css',
			// 'enqueue_script' 	=> get_template_directory_uri() . '/template-parts/blocks/reports-card/reports-card.js',
			// 'enqueue_assets' 	=> 'reports_card_block_enqueue_assets',
		) );

	}

}

add_action( 'acf/init', 'register_footer_block_block' );
function register_footer_block_block() {

	if ( function_exists( 'acf_register_block_type' ) ) {

		// Register Footer Block block
		acf_register_block_type( array(
			'name'            => 'footer-block',
			'title'           => __( 'Footer Block' ),
			'description'     => __( 'A custom Footer Block block.' ),
			'category'        => 'formatting',
			'icon'            => 'layout',
			'keywords'        => array( 'footer', 'block' ),
			'post_types'      => array( 'post', 'page' ),
			'mode'            => 'auto',
			// 'align'				=> 'wide',
			'render_template' => 'template-parts/blocks/footer-block.php',
			// 'render_callback'	=> 'footer_block_block_render_callback',
			'enqueue_style'   => get_template_directory_uri() . '/css/franchise.css',
			// 'enqueue_script' 	=> get_template_directory_uri() . '/template-parts/blocks/footer-block/footer-block.js',
			// 'enqueue_assets' 	=> 'footer_block_block_enqueue_assets',
		) );

	}

}

add_action( 'acf/init', 'register_page_title_block' );
function register_page_title_block() {

	if ( function_exists( 'acf_register_block_type' ) ) {

		// Register Page Title block
		acf_register_block_type( array(
			'name'            => 'page-title',
			'title'           => __( 'Page Title' ),
			'description'     => __( 'A custom Page Title block.' ),
			'category'        => 'formatting',
			'icon'            => 'layout',
			'keywords'        => array( 'page', 'title' ),
			'post_types'      => array( 'post', 'page' ),
			'mode'            => 'auto',
			// 'align'				=> 'wide',
			'render_template' => 'template-parts/blocks/page-title.php',
			// 'render_callback'	=> 'page_title_block_render_callback',
			'enqueue_style'   => get_template_directory_uri() . '/css/franchise.css',
			// 'enqueue_script' 	=> get_template_directory_uri() . '/template-parts/blocks/page-title/page-title.js',
			// 'enqueue_assets' 	=> 'page_title_block_enqueue_assets',
		) );

	}

}

add_action( 'acf/init', 'register_full_width_image_block' );
function register_full_width_image_block() {

	if ( function_exists( 'acf_register_block_type' ) ) {

		// Register Full Width Image block
		acf_register_block_type( array(
			'name'            => 'full-width-image',
			'title'           => __( 'Full Width Image' ),
			'description'     => __( 'A custom Full Width Image block.' ),
			'category'        => 'formatting',
			'icon'            => 'layout',
			'keywords'        => array( 'full', 'width', 'image' ),
			'post_types'      => array( 'post', 'page' ),
			'mode'            => 'auto',
			// 'align'				=> 'wide',
			'render_template' => 'template-parts/blocks/full-width-image.php',
			// 'render_callback'	=> 'full_width_image_block_render_callback',
			'enqueue_style'   => get_template_directory_uri() . '/css/franchise.css',
			// 'enqueue_script' 	=> get_template_directory_uri() . '/template-parts/blocks/full-width-image/full-width-image.js',
			// 'enqueue_assets' 	=> 'full_width_image_block_enqueue_assets',
		) );

	}

}

add_action( 'acf/init', 'register_own_separator_block' );
function register_own_separator_block() {

	if ( function_exists( 'acf_register_block_type' ) ) {

		// Register Own Separator block
		acf_register_block_type( array(
			'name'            => 'own-separator',
			'title'           => __( 'Own Separator' ),
			'description'     => __( 'A custom Own Separator block.' ),
			'category'        => 'formatting',
			'icon'            => 'layout',
			'keywords'        => array( 'own', 'separator' ),
			'post_types'      => array( 'post', 'page' ),
			'mode'            => 'auto',
			// 'align'				=> 'wide',
			'render_template' => 'template-parts/blocks/own-separator.php',
			// 'render_callback'	=> 'own_separator_block_render_callback',
			'enqueue_style'   => get_template_directory_uri() . '/css/franchise.css',
			// 'enqueue_script' 	=> get_template_directory_uri() . '/template-parts/blocks/own-separator/own-separator.js',
			// 'enqueue_assets' 	=> 'own_separator_block_enqueue_assets',
		) );

	}

}

add_action( 'acf/init', 'register_grey_box_block' );
function register_grey_box_block() {

	if ( function_exists( 'acf_register_block_type' ) ) {

		// Register Grey Box block
		acf_register_block_type( array(
			'name'            => 'grey-box',
			'title'           => __( 'Grey Box' ),
			'description'     => __( 'A custom Grey Box block.' ),
			'category'        => 'formatting',
			'icon'            => 'layout',
			'keywords'        => array( 'grey', 'box' ),
			'post_types'      => array( 'post', 'page' ),
			'mode'            => 'auto',
			// 'align'				=> 'wide',
			'render_template' => 'template-parts/blocks/grey-box.php',
			// 'render_callback'	=> 'grey_box_block_render_callback',
			'enqueue_style'   => get_template_directory_uri() . '/css/franchise.css',
			// 'enqueue_script' 	=> get_template_directory_uri() . '/template-parts/blocks/grey-box/grey-box.js',
			// 'enqueue_assets' 	=> 'grey_box_block_enqueue_assets',
		) );

	}

}

add_action( 'acf/init', 'register_form_box_block' );
function register_form_box_block() {

	if ( function_exists( 'acf_register_block_type' ) ) {

		// Register Form Box block
		acf_register_block_type( array(
			'name'            => 'form-box',
			'title'           => __( 'Form Box' ),
			'description'     => __( 'A custom Form Box block.' ),
			'category'        => 'formatting',
			'icon'            => 'layout',
			'keywords'        => array( 'form', 'box' ),
			'post_types'      => array( 'post', 'page' ),
			'mode'            => 'auto',
			// 'align'				=> 'wide',
			'render_template' => 'template-parts/blocks/form-box.php',
			// 'render_callback'	=> 'form_box_block_render_callback',
			'enqueue_style'   => get_template_directory_uri() . '/css/franchise.css',
			// 'enqueue_script' 	=> get_template_directory_uri() . '/template-parts/blocks/form-box/form-box.js',
			// 'enqueue_assets' 	=> 'form_box_block_enqueue_assets',
		) );

	}

}

add_action( 'acf/init', 'register_location_block_block' );
function register_location_block_block() {

	if ( function_exists( 'acf_register_block_type' ) ) {

		// Register Location Block block
		acf_register_block_type( array(
			'name'            => 'location-block',
			'title'           => __( 'Location Block' ),
			'description'     => __( 'A custom Location Block block.' ),
			'category'        => 'formatting',
			'icon'            => 'layout',
			'keywords'        => array( 'location', 'block' ),
			'post_types'      => array( 'post', 'page' ),
			'mode'            => 'auto',
			// 'align'				=> 'wide',
			'render_template' => 'template-parts/blocks/location-block.php',
			// 'render_callback'	=> 'location_block_block_render_callback',
			'enqueue_style'   => get_template_directory_uri() . '/css/franchise.css',
			// 'enqueue_script' 	=> get_template_directory_uri() . '/template-parts/blocks/location-block/location-block.js',
			// 'enqueue_assets' 	=> 'location_block_block_enqueue_assets',
		) );

	}

}

function add_second_tab_rewrite_rules() {
    // Check if ACF functions exist before using them
    if (!function_exists('have_rows')) {
        return;
    }
    
    // Query all pages where the module is used
    $args = array(
        'post_type' => 'page',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'modules',
                'compare' => 'EXISTS',
            ),
        ),
    );

    $pages = get_posts($args);

    if ($pages) {
        foreach ($pages as $page) {
            if (have_rows('modules', $page->ID)) {
                while (have_rows('modules', $page->ID)) {
                    the_row();
                    if (get_row_layout() == 'gift-card') {
                        $second_tab_path = get_sub_field('second_tab_path');
                        if ($second_tab_path) {
                            $second_tab_path = trim($second_tab_path, '/');
                            // Add rewrite rule
                            add_rewrite_rule(
                                '^' . $second_tab_path . '/?$',
                                'index.php?page_id=' . $page->ID,
                                'top'
                            );
                        }
                    }
                }
            }
        }
    }
}
add_action('init', 'add_second_tab_rewrite_rules', 20);

function flush_rewrite_rules_on_module_update($post_id) {
    // Check if it's a page and if the modules field is updated
    if (get_post_type($post_id) == 'page' && isset($_POST['acf'])) {
        // You might need to check if the specific module field is updated
        // For simplicity, we'll flush rewrite rules whenever a page is updated
        add_second_tab_rewrite_rules();
        flush_rewrite_rules();
    }
}
add_action('acf/save_post', 'flush_rewrite_rules_on_module_update', 20);


add_theme_support( 'enable-custom-font-sizes' );
add_theme_support( 'custom-spacing' );
add_theme_support( 'align-wide' );


function add_restaurant_schema() {
    if (is_singular('restaurant')) {
		global $post;
        $name 		= get_the_title($post);
        $address 	= get_field( 'clinic_address' );
        $phone 		= get_field( 'phone' );
        $email 		= get_field( 'email' );
		$menu 		= get_field( 'region_menu_link' );
		$location 	= get_field( 'address' );
		$hours         = get_field( 'opening_hours' );
		$hours_options = get_field( 'opening_hours', 'options' );
		
		// Ensure arrays are valid before accessing
		$hours = is_array($hours) ? $hours : [];
		$hours_options = is_array($hours_options) ? $hours_options : [];
		
		if ( !empty($hours['mo_from']) ) : $mo_from = $hours['mo_from'];
		else : $mo_from = $hours_options['mo_from'] ?? ''; endif;
		if ( !empty($hours['mo_to']) ) : $mo_to = $hours['mo_to'];
		if ( !empty($hours['tu_from']) ) : $tu_from = $hours['tu_from'];
		else : $tu_from = $hours_options['tu_from'] ?? ''; endif;
		if ( !empty($hours['tu_to']) ) : $tu_to = $hours['tu_to'];
		else : $tu_to = $hours_options['tu_to'] ?? ''; endif;
		if ( !empty($hours['tu_closed']) ): $tu = "Closed";
		else : $tu = $tu_from . " - " . $tu_to; endif;

		if ( !empty($hours['we_from']) ) : $we_from = $hours['we_from'];
		else : $we_from = $hours_options['we_from'] ?? ''; endif;
		if ( !empty($hours['we_to']) ) : $we_to = $hours['we_to'];
		else : $we_to = $hours_options['we_to'] ?? ''; endif;
		if ( !empty($hours['we_closed']) ): $we = "Closed";
		else : $we = $we_from . " - " . $we_to; endif;

		if ( !empty($hours['th_from']) ) : $th_from = $hours['th_from'];
		else : $th_from = $hours_options['th_from'] ?? ''; endif;
		if ( !empty($hours['th_to']) ) : $th_to = $hours['th_to'];
		else : $th_to = $hours_options['th_to'] ?? ''; endif;
		if ( !empty($hours['th_closed']) ): $th = "Closed";
		else : $th = $th_from . " - " . $th_to; endif;

		if ( !empty($hours['fr_from']) ) : $fr_from = $hours['fr_from'];
		else : $fr_from = $hours_options['fr_from'] ?? ''; endif;
		if ( !empty($hours['fr_to']) ) : $fr_to = $hours['fr_to'];
		else : $fr_to = $hours_options['fr_to'] ?? ''; endif;
		if ( !empty($hours['fr_closed']) ): $fr = "Closed";
		else : $fr = $fr_from . " - " . $fr_to; endif;

		if ( !empty($hours['sa_from']) ) : $sa_from = $hours['sa_from'];
		else : $sa_from = $hours_options['sa_from'] ?? ''; endif;
		if ( !empty($hours['sa_to']) ) : $sa_to = $hours['sa_to'];
		else : $sa_to = $hours_options['sa_to'] ?? ''; endif;
		if ( !empty($hours['sa_closed']) ): $sa = "Closed";
		else : $sa = $sa_from . " - " . $sa_to; endif;

		if ( !empty($hours['su_from']) ) : $su_from = $hours['su_from'];
		else : $su_from = $hours_options['su_from'] ?? ''; endif;
		if ( !empty($hours['su_to']) ) : $su_to = $hours['su_to'];
		else : $su_to = $hours_options['su_to'] ?? ''; endif;
		if ( !empty($hours['su_closed']) ): $su = "Closed";
		else : $su = $su_from . " - " . $su_to; endif;
		
		// Ensure location is an array before accessing
		$location = is_array($location) ? $location : [];
		
		$street_name = !empty($location['street_name']) ? $location['street_name'] : '';
		$city = !empty($location['city']) ? $location['city'] : '';
		$state = !empty($location['state']) ? $location['state'] : '';
		$country = !empty($location['country']) ? $location['country'] : '';
		$post_code = !empty($location['post_code']) ? $location['post_code'] : '';ry' ])):	$country = $location['country'];
		else: $country = ''; endif;
		
		if (! empty($location[ 'post_code' ])):	$post_code = $location['post_code'];
		else: $post_code = ''; endif;

        $schema = [
            "@context" => "https://schema.org",
            "@type" => "Restaurant",
			"name" => "MR MIKES ".$name,
			"telephone" => $phone,
			"email" => $email,
            "address" => [
                "@type" => "PostalAddress",
				"streetAddress"=> $street_name,
				"addressLocality"=> $city,
				"addressRegion"=> $state,
				"addressCountry"=> $country,
				"postalCode"=> $post_code
            ],
			"menu"=> $menu,
			"servesCuisine"=> "steakhouse",
			"openingHoursSpecification"=> [
				[
					"@type"=> "OpeningHoursSpecification",
					"dayOfWeek"=> ["Monday"],
					"opens"=> $mo_from,
					"closes"=> $mo_to
				],
				[
					"@type"=> "OpeningHoursSpecification",
					"dayOfWeek"=> ["Tuesday"],
					"opens"=> $tu_from,
					"closes"=> $tu_to
				],
				[
					"@type"=> "OpeningHoursSpecification",
					"dayOfWeek"=> ["Wednesday"],
					"opens"=> $we_from,
					"closes"=> $we_to
				],
				[
					"@type"=> "OpeningHoursSpecification",
					"dayOfWeek"=> ["Thursday"],
					"opens"=> $th_from,
					"closes"=> $th_to
				],
				[
					"@type"=> "OpeningHoursSpecification",
					"dayOfWeek"=> ["Friday"],
					"opens"=> $fr_from,
					"closes"=> $fr_to
				],
				[
					"@type"=> "OpeningHoursSpecification",
					"dayOfWeek"=> ["Saturday"],
					"opens"=> $sa_from,
					"closes"=> $sa_to
				],
				[
					"@type"=> "OpeningHoursSpecification",
					"dayOfWeek"=> ["Sunday"],
					"opens"=> $su_from,
					"closes"=> $su_to
				]
			]
        ];

        echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
    } else {
		echo '<!--- schema not applied-->';
	}
}
add_action('wp_head', 'add_restaurant_schema');