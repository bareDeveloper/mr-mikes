<?php
// Register theme menus
function registerMenus() {
	$locations = [
		'lp_header' => __( 'LP Header Menu' ),
		'utility'   => 'Utility Menu',
		'primary'   => 'Primary Menu',
		'footer'    => 'Footer Menu',
	];
	register_nav_menus( $locations );
}

add_action( 'after_setup_theme', 'registerMenus' );


// Add data attribute in case relationship field is given
function addDataAttr( $items, $args ) {

	if ( ! empty( $items ) ) {
		$dom = new DOMDocument();
		$dom->loadHTML( $items );
		$find = $dom->getElementsByTagName( 'a' );

		foreach ( $find as $item ) :
			if ( $item->getAttribute( 'rel' ) ) {
				$item->setAttribute( 'data-featherlight', '.' . $item->getAttribute( 'rel' ) );
				$item->setAttribute( 'data-featherlight-variant', $item->getAttribute( 'rel' ) . '__featherlight' );
			}
		endforeach;

		return $dom->saveHTML();
	}

}

add_filter( 'wp_nav_menu_items', 'addDataAttr', 10, 2 );
?>