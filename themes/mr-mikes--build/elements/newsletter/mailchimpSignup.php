<?php 

// this function is used for the deeds-well-done.php template and the Deeds well done advanced form to sign people up for mailchimp
function entry_created( $entry_id, $form ) {
    if (!get_field("newsletter_signup_checkbox", $entry_id)) return false;

    $email = get_field('email_address', $entry_id);
    $firstname = get_field('first_name', $entry_id);
    $lastname = get_field('last_name', $entry_id);

    if (
        $lastname 
        && $firstname 
        && $email
        ) {
            return mailchimpSignup(
                        $email, 
                        $firstname, 
                        $lastname,
                        'Deeds Well Done',
                        'no dont die',
                        '71e341a68d'
                        );
        }
}
add_action( 'af/form/entry_created/key=form_5bda2442594e3', 'entry_created', 10, 2 );






add_action('wp_ajax_mailchimpSignup', 'mailchimpSignup');
add_action('wp_ajax_nopriv_mailchimpSignup', 'mailchimpSignup');

function mailchimpSignup($email = '', $firstname = '', $lastname = '', $location = '', $dontdie = '', $mailchimplist = ''){

    if ($mailchimplist === '') {
        $listId = get_field('list_id', 'option');
    } else {
        $listId = $mailchimplist;
    }
    
    $apiKey = get_field('api_key', 'option');

    $email = isset($_POST['email']) ? $_POST['email'] : $email;
    $firstname = isset($_POST['first_name']) ? $_POST['first_name'] : $firstname;
    $lastname = isset($_POST['last_name']) ? $_POST['last_name'] : $lastname;
    $location = isset($_POST['location']) ? $_POST['location'] : $location;

    $data = [      
        'status'    => 'subscribed', // "subscribed","unsubscribed","cleaned","pending"
        'email'     => $email,
        'firstname' => $firstname,
        'lastname'  => $lastname,
        'location'  => $location
    ];


    $results = syncMailchimp($data, $apiKey, $listId);

    print_r(json_encode($results));
    if ($dontdie === '') die();
}


function syncMailchimp($data, $apiKey, $listId) {

    $memberId = md5(strtolower($data['email']));
    $dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
    $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/' . $memberId;

    $json = json_encode([
        'email_address' => $data['email'],
        'status'        => $data['status'],
        'merge_fields'  => [
            'FNAME'     => $data['firstname'],
            'LNAME'     => $data['lastname'],
            'LOCATION'  => $data['location']
        ]
    ]);

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    
    $result = curl_exec($ch); // store response
    $response = curl_getinfo($ch, CURLINFO_HTTP_CODE); // get HTTP CODE
    $errors = curl_error($ch); // store errors

    curl_close($ch);
    
    $results = array(
        'results' => $result,
        'response' => $response,
        'errors' => $errors
    );

    return $results;
}
?>