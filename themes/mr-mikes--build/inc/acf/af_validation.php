<?php
function validate_email() {
    $email = af_get_field( 'email_address' );

    $entries = get_posts(array(
        'post_type' => 'af_entry',
        'meta_query' => array(
            'relation'    => 'AND',
            array(
                'key' => 'entry_form',
                'value' => 'form_5db081e82ddd8',
                'compare'      => '='
            ),
            array(
              'key' => 'email_address',
              'value' => $email,
              'compare'      => '='
            )
        )
    ));

    if (count($entries) > 0) {
        af_add_error( 'email_address', 'You\'ve already submitted this form.' );
    }
}
add_action( 'af/form/validate/key=form_5db081e82ddd8', 'validate_email' );