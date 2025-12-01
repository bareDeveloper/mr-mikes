<?php

/**
 * Filters the next, previous and submit buttons.
 * Replaces the forms <input> buttons with <button> while maintaining attributes from original <input>.
 *
 * @param string $button Contains the <input> tag to be filtered.
 * @param object $form Contains all the properties of the current form.
 *
 * @return string The filtered button.
 */
add_filter( 'gform_previous_button', 'replace_prev_button', 10, 2 );
add_filter( 'gform_next_button', 'replace_submit_button', 10, 2 );
add_filter( 'gform_submit_button', 'replace_submit_button', 10, 2 );

function replace_prev_button( $button, $form ) {
	$dom = new DOMDocument();
	$dom->loadHTML( $button );
	$input      = $dom->getElementsByTagName( 'input' )->item( 0 );
	$new_button = $dom->createElement( 'a' );
	$new_button->appendChild( $dom->createTextNode( $input->getAttribute( 'value' ) ) );
	$input->removeAttribute( 'value' );

	$classes = $input->getAttribute( 'class' );
	$classes = "button";
	$input->setAttribute( 'class', $classes );

	foreach ( $input->attributes as $attribute ) {
		$new_button->setAttribute( $attribute->name, $attribute->value );
	}
	$input->parentNode->replaceChild( $new_button, $input );


	$new_button = '<div class="button_container btn-black">' . $dom->saveHtml( $new_button ) . '</div>';

	return $new_button;
}

function replace_submit_button( string $button ): string {

	$dom           = new DOMDocument();
	$button_dom_el = $dom->loadHTML( $button );

	if ( empty( $button_dom_el ) ) {
		return $button;
	}

	// Get the first input element from the parsed HTML
	$button_input_node = $dom->getElementsByTagName( 'input' )->item( 0 );

	// If there is no input element, return the default button
	if ( empty( $button_input_node ) ) {
		return $button;
	}

	try {

		// Trying to create a new button element
		$new_button_element = $dom->createElement( 'button' );
		if ( $new_button_element === false ) {
			return $button;
		}

		$new_button_text = $dom->createTextNode( $button_input_node->getAttribute( 'value' ) );
		if ( $new_button_text === false ) {
			return $button;
		}

		// Append the value of the input element to the new button element (Text inside the button)
		$new_button_element->appendChild( $new_button_text );

		// Remove the value attribute from the input element
		$button_input_node->removeAttribute( 'value' );

		// Getting all the classes from the input element
		$button_input_node_classes = $button_input_node->getAttribute( 'class' );

		// Adding the new class to the button element (input element)
		$button_input_node_classes .= " " . "gform-next-btn button";
		$is_new_classes_added      = $button_input_node->setAttribute( 'class', $button_input_node_classes );

		if ( $is_new_classes_added === false ) {
			return $button;
		}

		foreach ( $button_input_node->attributes as $attribute ) {
			$is_attrs_added_to_new_btn_el = $new_button_element->setAttribute( $attribute->name, $attribute->value );
			if ( $is_attrs_added_to_new_btn_el === false ) {
				return $button;
			}
		}

		$is_node_was_replaced = $button_input_node->parentNode->replaceChild( $new_button_element, $button_input_node );
		if ( $is_node_was_replaced === false ) {
			return $button;
		}

		// Return the new button element with the new classes, wrapped in a div with the new classes
		return '<div class="button_container btn-red">' . $dom->saveHtml( $new_button_element ) . '</div>';

	} catch ( DOMException $e ) {

		// If there was an error, return the default button
		return $button;

	}
}