<?php

function element($element_name, $props = []){
    if (empty($element_name)) {
        return false;
    }

    $stylesheetdir = get_stylesheet_directory();
    $element = $stylesheetdir . '/elements/' . $element_name . '/' . $element_name . '.php';

    $element_original_path = "$stylesheetdir/src/components/elements/$element_name/$element_name.php";

    if (file_exists($element)) {

        // Function to use array properties as variables i.e. $headline instead of $props['headine']
        extract($props, EXTR_PREFIX_SAME, "");
        
        // if (RP_DISPLAY_ELEMENT_PATH) {
        //     echo "<!-- $element_name.php | $element_original_path -->";
        //     include $element;
        //     echo "<!-- end $element_name.php | $element_original_path -->";
        // } else {
            include $element;
        // }

    } else {
        // Display error message if element doesn't exist
        echo $element_name . ' does not exist.</br>';

    }
}
?>