<?php 
add_action( 'after_setup_theme', 'add_my_custom_meta_field' );
function add_my_custom_meta_field() {
	add_filter( 'wp_schema_pro_schema_meta_fields', 'my_extra_schema_field' );
	add_filter( 'wp_schema_pro_schema_local_business', 'my_extra_schema_field_mapping', 10, 3 );
}

/**
 * Add fields for mapping.
 *
 * @param  array $fields Mapping fields array.
 * @return array
 */
function my_extra_schema_field( $fields ) {
	$fields['bsf-aiosrs-local-business']['subkeys']['cusine-serve'] = array( // `bsf-aiosrs-book` used for Book, `bsf-aiosrs-event` will for Event like that.
		'label'    => esc_html__( 'Cuisine Serves', 'aiosrs-pro' ), // Label to display in Mapping fields
		'type'     => 'text', // text/date/image
		'default'  => 'none',
	);
	
	return $fields;
}

/**
 * Mapping extra field for schema markup.
 *
 * @param  array $schema Schema array.
 * @param  array $data   Mapping fields array.
 * @return array
 */
function my_extra_schema_field_mapping( $schema, $data, $post ) {

	if ( isset( $data['cusine-serve'] ) && isset( $data['schema-type'] ) && 'FoodEstablishment' == $data['schema-type'] ) {
		$schema['servesCuisine'] = wp_strip_all_tags( $data['cusine-serve'] );
	return $schema;
	}
}
?>