<?php

if(props($props, 'button') && props($props, 'link')):

    // Set default properties
    if(props($props, 'target')):
        $target = $props['target'];
    else :
        $target = ''; 
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
        '<div ' . $id . 'class="button__container '. $class .'">
            <a class="button" href="' . $props['link'] . '" target="'. $target .'">'
                . $props['button'] .
            '</a>
        </div>';

endif; ?>