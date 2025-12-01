<?php /* Template Name: Page Style 1 */ ?>

<?php element('header'); ?>

<section class="page-style-1">
    <div class="page-title">
        <div class="headline__container grid-edges">
           <?php the_title('<h1 class="headline">','</h1>'); ?>
        </div>
    </div>
    <div class="grid-edges">
        <?php the_content(); ?>
    </div>
</section>

<?php element('footer'); ?>
</div>