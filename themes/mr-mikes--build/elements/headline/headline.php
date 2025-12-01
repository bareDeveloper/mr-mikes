<?php

if(props($props, 'text')):

    // Set default properties
    if(props($props, 'style')):
        $style = $props['style'];
    else :
        $style = 'h2';
    endif; 
    
    if(props($props, 'id')):
        $id = 'id="' . $props['id'] . '"';
    else:
        $id = '';
    endif;

    if(props($props, 'class')):
        $class = $props['class'];
    else:
        $class = '';
    endif;


    // Build element
    echo 
        '<div ' . $id . 'class="headline__container '. $class .'">
            <'. $style . ' class="headline">'
                . $props['text'] .
            '</'. $style . '>
        </div>';

endif; ?>