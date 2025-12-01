<div class="location-block">
    <div class="top-gallery">
        <?php $decorative_gallery_images = get_field( 'decorative_-_gallery' ); ?>
    	<?php if ( $decorative_gallery_images ) :  ?>
    		<?php foreach ( $decorative_gallery_images as $decorative_gallery_image ): ?>
    			<a href="<?php echo esc_url( $decorative_gallery_image['url'] ); ?>">
    				<img src="<?php echo esc_url( $decorative_gallery_image['sizes']['large'] ); ?>" alt="<?php echo esc_attr( $decorative_gallery_image['alt'] ); ?>" />
    			</a>
    			<p><?php echo esc_html( $decorative_gallery_image['caption'] ); ?></p>
    		<?php endforeach; ?>
    	<?php endif; ?>
    </div>
    <div class="location-lists">
        <div class="current col">
            <h3 class="title"><?php the_field( 'current_locations_-_title' ); ?></h3>
            
            <?php if ( have_rows( 'current_locations_-_manual_list' ) ) : ?>
                <ul>
        		<?php while ( have_rows( 'current_locations_-_manual_list' ) ) : the_row(); ?>
        			<li>
        			    <span><?php the_sub_field( 'name_-_link' ); ?></span>
        			</li>
        		<?php endwhile; ?>
        		</ul>
        	<?php else : ?>
        		<?php // No rows found ?>
        	<?php endif; ?>
        	
        	<?php if ( have_rows( 'soon_locations_-_manual_list' ) ) : ?>
            	<div class="soon-box">
            	    <h3 class="title"><?php the_field( 'soon_-_title' ); ?></h3>
                    <ul>
            		<?php while ( have_rows( 'soon_locations_-_manual_list' ) ) : the_row(); ?>
            			<li>
            			    <span><?php the_sub_field( 'name_-_link' ); ?></span>
            			</li>
            		<?php endwhile; ?>
            		</ul>
            	</div>
        	<?php else : ?>
        		<?php // No rows found ?>
        	<?php endif; ?>
        	
        </div>
        <div class="opportunities col">
            <h3 class="title"><?php the_field( 'opportunities_-_title' ); ?></h3>
            <?php if ( have_rows( 'opportunities_locations_-_manual_list' ) ) : ?>
                <ul>
        		<?php while ( have_rows( 'opportunities_locations_-_manual_list' ) ) : the_row(); ?>
        			<li>
        			    <span><?php the_sub_field( 'name_-_link' ); ?></span>
        			</li>
        		<?php endwhile; ?>
        		</ul>
        	<?php else : ?>
        		<?php // No rows found ?>
        	<?php endif; ?>
        </div>
    </div>
</div>