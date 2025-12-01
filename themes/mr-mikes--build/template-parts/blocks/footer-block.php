<section class="footer-block <?php the_field( 'bg-color' ); ?>">
    <?php if( get_field('background_image') ): ?>
	    I<img src="<?php the_field( 'background_image' ); ?>" alt="footer">
    <?php endif; ?>
    <div class="footer-block_inside">
        <div class="footer-block_content">
            <div class="left-footer">
                <h3 class="title"><?php the_field( 'title','options' ); ?></h3>
                <a href="<?php the_field( 'one_link','options' ); ?>"><?php the_field( 'one_link_-_title','options' ); ?></a>
            </div>
            <div class="right-footer">
                <?php
                wp_nav_menu( array( 
                    'theme_location' => 'footer-block-menu', 
                    'container_class' => 'custom-menu-class' ) );
                ?>
            </div>
        </div>
    </div>
</section>