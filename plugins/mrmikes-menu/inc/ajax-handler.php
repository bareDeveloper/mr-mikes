<?php
/**
 * MrMikes Menu AJAX Handler
 * Handles AJAX requests for dish images and style images
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AJAX handler for getting dish images
 */
function mrmikes_get_dish_image_ajax() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'mrmikes_ajax_nonce')) {
        wp_die('Security check failed');
    }

    // Get dish ID from request
    $dish_id = intval($_POST['dish_id']);

    if (!$dish_id) {
        wp_send_json_error('Invalid dish ID');
        return;
    }

    // Verify post exists and is the right type
    $post = get_post($dish_id);
    if (!$post || (!in_array($post->post_type, ['dish', 'drinks']))) {
        wp_send_json_error('Dish not found');
        return;
    }

    // Get image field from ACF
    $image_id = get_field('image', $dish_id);

    if (!$image_id) {
        wp_send_json_error('No image found for this dish');
        return;
    }

    // Get image data
    $image_data = wp_get_attachment_image_src($image_id, 'large');
    $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);

    if (!$image_data) {
        wp_send_json_error('Image data not found');
        return;
    }

    // Prepare response
    $response = array(
        'url' => $image_data[0],
        'width' => $image_data[1],
        'height' => $image_data[2],
        'alt' => $image_alt ? $image_alt : get_the_title($dish_id) . ' image'
    );

    wp_send_json_success($response);
}

/**
 * AJAX handler for getting attachment images (for personalized style images)
 */
function mrmikes_get_attachment_image_ajax() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'mrmikes_ajax_nonce')) {
        wp_die('Security check failed');
    }

    // Get image ID from request
    $image_id = intval($_POST['image_id']);

    if (!$image_id) {
        wp_send_json_error('Invalid image ID');
        return;
    }

    // Verify attachment exists
    $attachment = get_post($image_id);
    if (!$attachment || $attachment->post_type !== 'attachment') {
        wp_send_json_error('Image not found');
        return;
    }

    // Get image data
    $image_data = wp_get_attachment_image_src($image_id, 'large');
    $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);

    if (!$image_data) {
        wp_send_json_error('Image data not found');
        return;
    }

    // Prepare response
    $response = array(
        'url' => $image_data[0],
        'width' => $image_data[1],
        'height' => $image_data[2],
        'alt' => $image_alt ? $image_alt : get_the_title($image_id) . ' image'
    );

    wp_send_json_success($response);
}

// Hook AJAX actions for dish images
add_action('wp_ajax_mrmikes_get_dish_image', 'mrmikes_get_dish_image_ajax');
add_action('wp_ajax_nopriv_mrmikes_get_dish_image', 'mrmikes_get_dish_image_ajax');

// Hook AJAX actions for attachment images (style images)
add_action('wp_ajax_mrmikes_get_attachment_image', 'mrmikes_get_attachment_image_ajax');
add_action('wp_ajax_nopriv_mrmikes_get_attachment_image', 'mrmikes_get_attachment_image_ajax');