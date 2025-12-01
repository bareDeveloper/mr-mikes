<?php element('header'); ?>

    <?php modules('modules', get_option('page_for_posts')); ?>

    <div class="home lazyload" <?php echo lazy_background(get_field('body_background_image', 'options')); ?>>
        
        <div class="home__container">
           
            <?php 

                $args = [
                    'element'       => 'card',

                    'query'         => [
                        'post_type' => 'post'
                    ],

                    'props'         => [

                        'thumbnail' => 'get_post_thumbnail_id()',
                        
                        'headline'  => [
                            'id'    => '',
                            'class' => '',
                            'text'  => 'get_the_title()',
                            'style' => 'h3'
                        ],

                        'content'   => 'get_the_content()',

                        'button'    => [
                            'id'    => '',
                            'class' => '',
                            'button'  => 'Feed me more…',
                            'link'  => 'get_permalink()'
                        ]
                        
                    ]
                    
                ];

                queryPosts($args);
        
                $count = wp_count_posts();

                if($count->publish > 5){
                    element('pagination');
                }
             
             ?>

        </div>

    </div>

<?php element('footer');