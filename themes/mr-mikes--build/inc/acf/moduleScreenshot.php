<?php
// add_action( 'current_screen', 'this_screen' );

// function this_screen() {

//     $current_screen = get_current_screen();

//     if( $current_screen ->post_type === "page" && is_super_admin()) {

//         add_action( 'acf/render_field/type=flexible_content', 'action_function_name', 10, 1 );

//         add_action( 'admin_enqueue_scripts', 'wpdocs_selectively_enqueue_admin_script' );
    
//         add_action('wp_ajax_modulePreviewImage', 'saveModuleImage'); 
//         add_action('wp_ajax_nopriv_modulePreviewImage', 'saveModuleImage');

//         add_thickbox();

//     }
    
// }


// function action_function_name( $field ) {

//     echo '<div id="my-content-id" style="display:none;">
//         <img class="preview-image" />
//     </div>';



//     //Ajax url
//     echo '<script>var ajaxurl = "' . admin_url('admin-ajax.php') . '";</script>';
//     echo '<script>var themeurl = "' . get_stylesheet_directory_uri() . '";</script>';

//     //Create container for screenshot and iframe
//     echo '<div id="screenshotContainer"><iframe name="previewIframe"></iframe></div>';

//     //Load all element screenshots as background images
//     $path = get_stylesheet_directory() . '/elements/';
//     if ($handle = opendir($path)) {
//         while (false !== ($file = readdir($handle))) {
//             if ('.' === $file) continue;
//             if ('..' === $file) continue;

//             $style .= '.acf-fc-popup > ul > li > a[data-layout="'.$file.'"]:before{background-image: url('.get_stylesheet_directory_uri() . '/elements/' .$file. '/' .$file. '.png);} ';
            
//         }
//         closedir($handle);
//     }

//     //Set styles
//     echo '<style>

//         .acf-fc-popup:before{
//             position: fixed;
//             left: 0 !important;
//             top: 0 !important;
//             width: 100%;
//             height: 100%;
//             background-color: rgba(0,0,0, 0.8);
//         }

//         .acf-fc-popup > ul{
//             position: fixed;
//             right: 5vw;
//             top: 5vh;
//             background-color: #fff;
//             width: 200px;
//             padding-top: 20px;
//             overflow: auto;
//         }

//         .acf-fc-popup > ul:before{
//             content: " ";
//             position: fixed;
//             top: 5vh;
//             left: 5vw;
//             width: 90vw;
//             height: 90vh;
//             background: #fff;
//         }

//         .acf-fc-popup > ul > li > a{
//             cursor: pointer;
//             color: #000;
//             font-weight: bold;
//         }

//         .acf-fc-popup > ul > li > a:before{
//             content: " ";
//             position: fixed;
//             z-index: 999;
//             top: 5vh;
//             left: 5vw;
//             width: calc(90vw - 200px);
//             height: 90vh;
//             background-size: contain;
//             background-repeat: no-repeat;
//             background-position: center;
//             opacity: 0;
//         }

//         .acf-fc-popup > ul > li > a:hover:before{
//             opacity: 1;
//         }

//         ' . $style . '

//         .acf-flexible-content .layout .acf-fc-layout-controlls .acf-icon{
//             visibility: hidden;
//         }
        
//         .acf-flexible-content .layout:hover .acf-fc-layout-controlls .acf-icon{
//             visibility: visible;
//         }

//         .screenshotButton .dashicons, 
//         .previewButton .dashicons{ 
//             padding-top: 1px;
//             line-height: 18px;
//             font-size: 14px;
//         }

//         #screenshotContainer{
//             position: fixed;
//             left: 0;
//             top: 0;
//             width: 100%;
//             height: 100%;
//             z-index: 9999;
//             pointer-events: none;
//             opacity: 0;
//             background-color: #ffffff;
//         }

//         #screenshotContainer iframe{
//             width: 100%;
//             height: 100%;
//         }

//         .preview-image{
//             max-width: 100%;
//             max-height: 100%;
//         }

//     </style>';
// }


// function wpdocs_selectively_enqueue_admin_script( $hook ) {

//     wp_enqueue_script('html2canvas', get_template_directory_uri() . '/static/js/html2canvas.js');
//     wp_enqueue_script('adminACF', get_template_directory_uri() . '/static/js/moduleScreenshot.js', array('jquery'), '', false);

// }

// function saveModuleImage(){

//     define('UPLOAD_DIR', '../wp-content/themes/mr-mikes--build/elements/' . $_POST['element'] . '/');
//     $img = $_POST['file'];
//     $img = str_replace('data:image/png;base64,', '', $img);
// 	$img = str_replace(' ', '+', $img);
// 	$data = base64_decode($img);
// 	$file = UPLOAD_DIR . $_POST['element'] . '.png';
// 	$success = file_put_contents($file, $data);

//     print $success ? $file : 'Unable to save the file.';
//     die;
// }

?>