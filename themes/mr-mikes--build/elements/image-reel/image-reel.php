<?php
$bgColour = get_sub_field('background_colour');
$bgColour = "bg-" . $bgColour;
?>



<div class="image-reel <?php echo $bgColour;?>">
<?php
  if( have_rows('images') ):
    while ( have_rows('images') ) : the_row();
    $image = get_sub_field('image');

    if(!empty($image)):
      $url = $image['url'];
      $alt = $image['alt']; ?>

      <div class="image-reel__img-cont">
        <img
          alt="<?php echo $alt ?>"
          class="image-reel__img"
          src="<?php echo $url; ?>"
        />
      </div>

    <?php endif; ?>

    <?php endwhile;
  endif;
?>
</div>