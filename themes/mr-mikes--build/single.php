<?php element('header'); ?>

  <?php

    element('banner', [
      'background_image' => get_post_thumbnail_id(),
      'headline' => get_the_title()
    ])

  ?>

  <div class="single lazyload" <?php echo lazy_background(get_field('body_background_image', 'options')); ?>>

    <?php while ( have_posts() ) : the_post(); ?>

      <div class="single__container">

        <div class="single__sidebar">

            <?php
              element('button', [
                'button' => 'All promotions',
                'link' => get_permalink(get_option('page_for_posts'))
              ])
            ?>

        </div>

        <div class="single__content">

          <?php the_content(); ?>

        </div>

      </div>

    <?php endwhile; ?>

    <div class="single__pagination">

        <?php element('next-prev-post'); ?>

    </div>

</div>

<?php element('footer'); ?>
