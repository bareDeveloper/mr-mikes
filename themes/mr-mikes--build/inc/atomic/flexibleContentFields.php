<?php

// Display row of flexible content field
function modules( $modules_field = 'modules', $page_id = '' ) {

	if ( have_rows( $modules_field, $page_id ) ) :

		while ( have_rows( $modules_field, $page_id ) ) : the_row();

			echo '<div class="module" data-layout="' . get_row_layout() . '">';

			element( get_row_layout() );

			echo '</div>';

		endwhile;

	endif;
}

?>