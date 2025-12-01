<div class="cards card-l">
    <div class="card-image">
        <?php 
        $image = get_field('image');
        if( !empty( $image ) ): ?>
            <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
        <?php endif; ?>
    </div>
    <div class="card-content">
        <?php the_field( 'content' ); ?>
        <?php if( get_field('more_link') ): ?>
            <a href="<?php the_field( 'more_link' ); ?>" target="<?php the_field( 'more_link_-_target' ); ?>" class="card-more"><?php the_field( 'more_link_-_name' ); ?></a>
        <?php endif; ?>
    </div>
</div>