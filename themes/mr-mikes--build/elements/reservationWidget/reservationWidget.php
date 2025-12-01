<?php // Note: Widget for single restaurant is located on the single-restaurant.php page ?>

<?php 

$args = [
    'post_type' =>  'restaurant',
    'posts_per_page' => -1,
    'meta_query'	=> array(
		array(
			'key'	  	=> 'open_table_id',
			'value'	  	=> '',
			'compare' 	=> '>',
		),
	)
];

$postslist = get_posts( $args );

$open_table_ids = [];

foreach ($postslist as $post) :  
    setup_postdata($post);
    $post_id = $post->ID;
    $open_table_id = 'rid=' . get_field('open_table_id', $post_id);
    $open = get_field("open", $post_id);

    if($open && $open != 'Closed' && $open != 'Open for Takeout/Delivery'){
        array_push($open_table_ids, $open_table_id);
    }
endforeach;

$open_table_ids_to_string = implode('&', $open_table_ids);

?>



<div class="reservation">
    <div class="reservation__container">
        <script type='text/javascript' src='//www.opentable.ca/widget/reservation/loader?<?php echo $open_table_ids_to_string; ?>&type=multi&theme=tall&iframe=true&overlay=false&domain=ca&lang=en-CA'></script>
    </div>
</div>