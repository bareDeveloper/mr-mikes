<?php


if( function_exists('acf_add_local_field_group') ):

    $layouts = [];

    // Get all modules
    $modules = acf_get_field_groups();

    foreach($modules as $module):

        $title = $module['title'];
        
        if (strpos($title, 'Module: ') !== false) :

            $title = str_replace('Module: ', '', $title);

            $key = $module['key'];

            // Generate slug from title
            $slug = strtolower(preg_replace('/[^\w-]+/','-', $title));

            $layouts[$key] = [
                'key' => $key,
                'label' => $title,
                'name' => $slug,
                'display' => 'block',
                'sub_fields' => array(
                    array(
                        'key' => $key,
                        'label' => $title,
                        'name' => $slug,
                        'type' => 'clone',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'clone' => array(
                            0 => $key,
                        ),
                        'display' => 'seamless',
                        'layout' => 'block',
                        'prefix_label' => 0,
                        'prefix_name' => 0,
                    ),
                ),
                'min' => '',
                'max' => '',
            ];
        endif;
                
    endforeach;

    // Create flexible content area 'modules'

    acf_add_local_field_group(array(
        'key' => 'group_5a78f47055572',
        'title' => 'Modules',
        'fields' => array(
            array(
                'key' => 'field_5a78f4f7e90a0',
                'label' => 'Modules',
                'name' => 'modules',
                'type' => 'flexible_content',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'layouts' => $layouts,
                'button_label' => 'Add Content',
                'min' => '',
                'max' => '',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'page',
                ),
                array(
                    'param' => 'post_type',
                    'operator' => '!=',
                    'value' => 'page',
                ),
            ),
        ),
        'menu_order' => -999,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => 1,
        'description' => '',
    ));


    // Display flexible content area 'modules' on default template

    acf_add_local_field_group(array(
        'key' => 'group_5a8486cf51136',
        'title' => 'Template: Default',
        'fields' => array(
            array(
                'key' => 'field_5a84871978f88',
                'label' => 'Content',
                'name' => 'content',
                'type' => 'clone',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'clone' => array(
                    0 => 'field_5a78f4f7e90a0',
                ),
                'display' => 'seamless',
                'layout' => 'block',
                'prefix_label' => 0,
                'prefix_name' => 0,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'page',
                ),
            ),
        ),
        'menu_order' => -1,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => array(
            0 => 'the_content',
            1 => 'excerpt',
            2 => 'custom_fields',
            3 => 'discussion',
            4 => 'comments',
            5 => 'revisions',
            6 => 'slug',
            7 => 'author',
            8 => 'format',
            9 => 'featured_image',
            10 => 'categories',
            11 => 'tags',
            12 => 'send-trackbacks',
        ),
        'active' => 1,
        'description' => '',
    ));
        
endif;

?>