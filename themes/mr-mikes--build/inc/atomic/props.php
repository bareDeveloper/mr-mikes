<?php
function props($props, $key){
    if(is_array($props) && array_key_exists($key, $props)) :
        if($props[$key] > "") :
            return true;
        else :
            return false;
        endif;
    else :
        return false;
    endif;
}
?>