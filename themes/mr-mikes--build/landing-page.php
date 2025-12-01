<?php 
/**
 * Template Name: Generic Landing Page 
 * Description: Extends frebrewary template
 */
?>

<?php element('header'); ?>
<div class="footer-bottom-space-fix">

    <div class="febrewary" style="min-height: unset;">
        <div class="febrewary__mobile">
            <?php 
            if (have_rows('mobile_images')): 
                while(have_rows('mobile_images')): 
                    the_row(); 
                    Img(get_sub_field('image')); 
                endwhile; 
            endif; 
            ?>
        </div>

        <div class="febrewary__desk">
            <?php Img(get_field('header_image')); ?>

            <div class="febrewary__col-wrap"
                <?php lazy_background(get_field('desktop_background_image'), 'medium', get_field('background_image_style')); ?>>
                <section class="febrewary__cols">
                    <?php 
                    if (have_rows('desktop_images')) {
                        while(have_rows('desktop_images')) {
                            the_row();
                            ?>
                    <div class="col">
                        <?php Img(get_sub_field('image')); ?>
                    </div>
                    <?php
                        }
                    }
                    ?>
            </div>
            </section>
        </div>
    </div>
</div>

<?php element('footer'); ?>
</div>