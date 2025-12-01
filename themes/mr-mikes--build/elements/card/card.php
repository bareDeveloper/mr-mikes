<!-- <div class="card">

    <div class="card__image lazyload" <?php echo lazy_background($thumbnail); ?>>
    </div>

    <div class="card__content"> 

        <?php element('headline', $headline); ?>

        <div class="card__text">
            <?php 
            if(get_field('details')) :
                echo wp_trim_words( $content, 60, '...' );
            else : 
                echo $content;
            endif;
            ?>
            
        </div>

        <?php
        if(get_field('details')) :
            element('button', $button); 
        endif;
        ?>
        
    </div>

</div> -->