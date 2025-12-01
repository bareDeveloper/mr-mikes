<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$section_id = get_sub_field( 'section_id' );
?>
<div class="jobs"
	<?php if ( ! empty( $section_id ) ) : ?>
        id="<?php echo esc_attr( $section_id ); ?>"
	<?php endif; ?>
>

    <div class="jobs__container">

		<?php

		$category = get_sub_field( 'category' );

		element( 'headline', [
			'text'  => get_sub_field( 'headline' ),
			'style' => 'h2'
		] );

		$args = [
			'element' => 'job',

			'query' => [
				'post_type'      => 'jobs',
				'posts_per_page' => - 1,
				'tax_query'      => array(
					array(
						'taxonomy' => 'job_category',
						'field'    => 'term_id',
						'terms'    => $category
					)
				)
			],

			'props' => [

				'post_id' => 'get_the_ID()',

				'title' => 'get_the_title()',

				'link' => 'get_permalink()',

			]

		];

		$jobs = queryPosts( $args );

		if ( $jobs == false ):

			echo '<h3>No available positions</h3>';

		endif;

		?>

    </div>

</div>