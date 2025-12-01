<?php element('header'); ?>

    <div class="search">

      <div class="search__container">

          <?php if (have_posts()) : ?>

              <div class="search__headline">

                  <?php
                      atom('headline', [
                        'props' => array(
                          'headline' => 'Search Results for: ' . get_search_query(),
                          'style' => 'h1'
                        )
                      ]);
                  ?>

              </div>      
              
              <?php while (have_posts()) : the_post(); ?>
                  <div class="search__result">
                    
                  </div>
              <?php endwhile; ?>

          <?php else : ?>

          <?php endif; ?>

      </div>

    </div>

<?php element('footer');