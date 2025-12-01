<?php
function renameUploadedFiles($filename) {

    if(is_admin()):
        return $filename;
    endif;

    $info = pathinfo($filename);
    $ext  = empty($info['extension']) ? '' : '.' . $info['extension'];
    $filename = bin2hex(openssl_random_pseudo_bytes(16));

    return $filename . $ext;
}
add_filter('sanitize_file_name', 'renameUploadedFiles', 10);

?>