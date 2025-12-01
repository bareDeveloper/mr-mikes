<div class="job">

    <?php
        $location_id = get_field('location', $post_id);
        if($location_id){
            $location = get_the_title($location_id) . ' - ';
            $address = get_field('address', $location_id);
        }else{
            $location = '';
            $address = '';
        }
        
    ?>

    <a href="<?php echo $link; ?>">
        <h3 class="headline">
            <?php echo $location . $title; ?>
        </h3>
    </a>

    <?php if (is_array($address) && array_key_exists("address", $address)): ?>
        <?php echo $address['address']; ?>
    <?php endif; ?>

</div>