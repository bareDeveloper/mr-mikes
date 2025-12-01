<?php

add_filter( 'gform_pre_render_2', 'populate_provinces' );
add_filter( 'gform_pre_validation_2', 'populate_provinces' );
add_filter( 'gform_pre_submission_filter_2', 'populate_provinces' );
add_filter( 'gform_admin_pre_render_2', 'populate_provinces' );
function populate_provinces( $form ) {
    // Get all provinces terms
    $terms = get_terms([
        'taxonomy' => 'provinces',
        'hide_empty' => false,
    ]);

    // Create provinces array with all provinces terms
    $provinces = array();
    foreach ( $terms as $term ) {
        $provinces[] = array('text' => $term->name, 'value' => $term->name);
    }

    // Add provinces array as choices to 
    // the field with paramater name 'province_select'
    foreach ( $form['fields'] as &$field ) {
        if ( $field->inputName == 'province_select' ) {
            $field->placeholder = ' '; // Adds an empty placeholder choice
            $field->choices = $provinces;
        }
    }
 
    return $form;
}

add_filter( 'gform_pre_validation_2', 'populate_restaurants' );
add_filter( 'gform_pre_render_2', 'populate_restaurants' );
function populate_restaurants( $form ) {
    // Read province selected in first page of the form
    $province = rgpost('input_3');

    // Reading posts for 'restaurant' post type
    // matching the selected province term
    $posts = get_posts([
        'post_type' => 'restaurant',
        'numberposts' => -1,
        'tax_query' => [
            [
                'taxonomy' => 'provinces',
                'field' => 'name',
                'terms' => $province
            ]
        ]
    ]);

    // Create restaurants array with all restaurants matching the selected province term
    $restaurants = array();
    foreach ( $posts as $post ) {
        $restaurants[] = array( 'value' => $post->post_title, 'text' => $post->post_title );
    }
 
    // Adding restaurants array as choices to the field with paramater name 'fav_location'
    foreach ( $form['fields'] as &$field ) {
        if ( $field->inputName == 'fav_location' ) {
            $field->placeholder = ' '; // Adds an empty placeholder choice
            $field->choices = $restaurants;
        }
    }
 
    return $form;
}