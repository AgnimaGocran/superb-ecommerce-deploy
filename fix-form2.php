<?php
/**
 * Rebuild the demo Contact Forminator form (id 372) with the demo's row
 * structure: one field per row (col-12), auto submit row labelled "Send".
 * Run: wp eval-file fix2.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$desired_id = 372;

// Remove any previous version of the form (id forced below).
$existing = get_post( $desired_id );
if ( $existing && 'forminator_forms' === $existing->post_type ) {
	wp_delete_post( $desired_id, true );
}

$wrappers = array(
	array(
		'wrapper_id' => 'wrapper-1551776807277-1631',
		'form_id'    => 'forminator-form-contact',
		'fields'     => array(
			array(
				'element_id'  => 'name-1',
				'type'        => 'text',
				'columns'     => 12,
				'field_label' => 'First Name',
				'placeholder' => 'E.g. John',
				'required'    => 'true',
			),
		),
	),
	array(
		'wrapper_id' => 'wrapper-1551776807459-5264',
		'form_id'    => 'forminator-form-contact',
		'fields'     => array(
			array(
				'element_id'  => 'email-1',
				'type'        => 'email',
				'columns'     => 12,
				'field_label' => 'Email Address',
				'placeholder' => 'E.g. john@doe.com',
				'required'    => 'true',
			),
		),
	),
	array(
		'wrapper_id' => 'wrapper-1551776807561-7008',
		'form_id'    => 'forminator-form-contact',
		'fields'     => array(
			array(
				'element_id'  => 'phone-1',
				'type'        => 'phone',
				'columns'     => 12,
				'field_label' => 'Phone Number',
				'placeholder' => 'E.g. +1 3004005000',
				'required'    => 'false',
			),
		),
	),
	array(
		'wrapper_id' => 'wrapper-1551776807674-8430',
		'form_id'    => 'forminator-form-contact',
		'fields'     => array(
			array(
				'element_id'  => 'textarea-1',
				'type'        => 'textarea',
				'columns'     => 12,
				'field_label' => 'Message',
				'placeholder' => 'Enter your message...',
				'required'    => 'false',
			),
		),
	),
	array(
		'wrapper_id' => 'wrapper-1551776807787-1111',
		'form_id'    => 'forminator-form-contact',
		'fields'     => array(
			array(
				'element_id'  => 'submit-1',
				'type'        => 'submit',
				'columns'     => 12,
				'submit-text' => 'Send',
			),
		),
	),
);

$settings = array(
	'formName'          => 'Contact',
	'form-design'       => 'flat',
	'validation-text'   => 'Please fill in all required fields.',
);

$id = Forminator_API::add_form( 'Contact', $wrappers, $settings );

if ( is_wp_error( $id ) ) {
	echo 'FORM_ERROR ' . $id->get_error_message() . "\n";
	exit( 1 );
}

// Force the form post id to 372 so the demo custom CSS (css_style-372.css,
// selectors #forminator-module-372) applies unchanged.
if ( (int) $id !== $desired_id ) {
	global $wpdb;
	$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->posts} SET ID = %d WHERE ID = %d", $desired_id, $id ) );
	$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->postmeta} SET post_id = %d WHERE post_id = %d", $desired_id, $id ) );
	clean_post_cache( $desired_id );
}

echo 'FORM_REBUILT ' . $desired_id . "\n";
