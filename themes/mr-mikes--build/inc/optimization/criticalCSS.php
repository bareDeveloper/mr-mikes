<?php

function criticalCSS(){

    $criticalFileDir = get_stylesheet_directory() . '/critical-css/';

    $elements = [
         'normalize',
         'typographyjs',
         'accessibility',  
         'typography',
         'global',
         'header'
    ];

    echo '<style id="above-the-fold">';

        foreach($elements as $element):

            $file = $criticalFileDir  . '/' .$element . '.css';
        
            if (file_exists($file)) {
                echo file_get_contents($file);
            }

        endforeach;


        if (is_page()) {

            $flexible_content = get_field('modules');

            $module1 = $criticalFileDir . $flexible_content[0]["acf_fc_layout"] . '.css';
            if (file_exists($module1)) {
                echo file_get_contents($module1);
            }

            $module2 = $criticalFileDir . $flexible_content[1]["acf_fc_layout"] . '.css';
            if (file_exists($module2)) {
                echo file_get_contents($module2);
            }

        } elseif (is_single()) {

            $post_type = get_post_type();

            if($post_type == 'post'){
                $template = '';
            }else{
                $template = '-' . $post_type;
            }

            $file = $criticalFileDir . 'single'. $template .'.css';
            if (file_exists($file)) {
                echo file_get_contents($file);
            } 
            
        } elseif (is_archive()) {


        } elseif (is_404()) {

            $file = $criticalFileDir . '404.css';
            if (file_exists($file)) {
                echo file_get_contents($file);
            }
            
        } elseif (is_search() && is_search_has_results()) {

           
        } elseif (is_search()) {
            
        } else {

            $template_slug = get_page_template_slug( $post->ID );

            if($template_slug):

                $template_slug = str_replace('php', "css", $template_slug);
                $file = $criticalFileDir . $template_slug;
                if (file_exists($file)) {
                    echo file_get_contents($file);
                }
            endif;

        }

    echo '</style>';

}

?>