<?php /* Template Name: Page Style 2 */ ?>

<?php element('header'); ?>


<section class="page-style-2 <?php the_field( 'background_-_position' ); ?> <?php the_field( 'background_-__size' ); ?>" style="background: url(<?php the_field( 'background' ); ?>);" >
    <div class="grid-edges">
        <div class="content-page">
            <?php the_content(); ?>
        </div>
    </div>
</section>

<?php element('footer'); ?>
</div>