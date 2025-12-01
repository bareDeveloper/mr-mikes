<?php

// Custom routing for contact form
function contactForm_recipient( $recipient, $email, $form, $fields ) {

    $subject = af_get_field('subject');

    if($subject == 'experience'){
        $recipient .= ', info@mrmikes.ca';

    }elseif($subject == 'general'){
        $recipient .= ', info@mrmikes.ca';

    }elseif($subject == 'rewards'){
        $recipient .= ', rewards@mrmikes.ca';

    }elseif($subject == 'donation'){
        $recipient .= ', info@mrmikes.ca';
    }

    $location = af_get_field('location');
    if($location){
        $location_id = $location->ID;
        $email = get_field('email', $location_id);
        $recipient .= ', ' . $email;
    }
    
    return $recipient;
}
add_filter( 'af/form/email/recipient/key=form_5ae8f43b7cfcb', 'contactForm_recipient', 10, 4 );


// Necessary because Post object selector return false and not ''
function validateLocation() {
    $location = af_get_field( 'location' );

    if ( $location == false) {
        af_add_error( 'location', 'Please select a location' );
    }
}
add_action( 'af/form/validate/key=form_5ae8f43b7cfcb', 'validateLocation' );


// Add hidden field with restaurant id to form 'Job'
function hidden_field( $form, $args ) {
    global $post;
    $post_id = $post->ID;

    $location_id = get_field('location', $post_id);

    if($location_id){
        echo '<input type="hidden" name="location" value="'.$location_id.'">';
    }

    echo '<input type="hidden" name="job_title_hidden" value="'.get_the_title().'">';
}
add_filter( 'af/form/hidden_fields/key=form_5ae8cf4c75bad', 'hidden_field', 10, 4 );


//Route emails to restaurant
function jobForm_recipient( $recipient, $email, $form, $fields ) {

    $location = af_get_field( 'location' );
    $email = get_field('email', $location);

    if($location && $email){
        $recipient .= ', ' . $email;
    }else{
        $recipient .= ', info@mrmikes.ca';
    }
    
    return $recipient;
}
add_filter( 'af/form/email/recipient/key=form_5ae8cf4c75bad', 'jobForm_recipient', 10, 4 );
add_filter( 'af/form/email/recipient/key=form_5ae8d0063b5b4', 'jobForm_recipient', 10, 4 );


// Add file as attachment
function filter_email_attachments( $attachments, $email, $form, $fields ) {

    $file = af_get_field( 'resume' );
    $file_url = $file['url'];
    $file_path = str_replace(site_url() . '/wp-content' , WP_CONTENT_DIR , $file_url);

	// Add a file as an attachment
    $attachments[] = $file_path;
    
    return $attachments;
}
add_filter( 'af/form/email/attachments/key=form_5ae8cf4c75bad', 'filter_email_attachments', 10, 4 );
add_filter( 'af/form/email/attachments/key=form_5ae8d0063b5b4', 'filter_email_attachments', 10, 4 );


// Delete attachment after email has been sent
function after_email_send( $email, $form ) {
    $file = af_get_field('resume');
    wp_delete_attachment($file['ID']);
}
add_action( 'af/email/after_send/key=form_5ae8cf4c75bad', 'after_email_send', 10, 2 );
add_action( 'af/email/after_send/key=form_5ae8d0063b5b4', 'after_email_send', 10, 2 );

?>