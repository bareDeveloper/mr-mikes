<div class="page-banner">
    <div class="page-banner_bg">
        <img src="<?php the_field('page-banner_background'); ?>" />
    </div>
    <div class="banner__container">
        <div class="headline__container">
            <?php the_title( '<h1>', '</h1>' ); ?>
        </div>
    </div>
    <?php if( get_field('optional_description') ): ?> 
        <div class="headline__container_optional">
            <div class="container_optional_inside">
                <?php the_field('optional_description'); ?>
            </div>
        </div>
    <?php endif; ?>
</div>