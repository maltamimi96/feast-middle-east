<?php
/**
 * Private CMS inbox for catering enquiries.
 *
 * @package FeastMiddleEast
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Register the private enquiry record type. */
function feast_register_enquiry_type() {
	register_post_type(
		'feast_enquiry',
		array(
			'labels' => array(
				'name'          => 'Enquiries',
				'singular_name' => 'Enquiry',
				'menu_name'     => 'Enquiries',
				'edit_item'     => 'View Enquiry',
				'search_items'  => 'Search Enquiries',
				'not_found'     => 'No enquiries found.',
			),
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => 'feast-cms',
			'show_in_rest'        => false,
			'supports'            => array( 'title' ),
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
			'capabilities'        => array( 'create_posts' => 'do_not_allow' ),
			'has_archive'         => false,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
		)
	);
}
add_action( 'init', 'feast_register_enquiry_type' );

/** Store one validated form submission in the private CMS inbox. */
function feast_store_enquiry( $enquiry ) {
	$post_id = wp_insert_post(
		array(
			'post_type'   => 'feast_enquiry',
			'post_status' => 'private',
			'post_title'  => $enquiry['name'],
		)
	);

	if ( ! $post_id || is_wp_error( $post_id ) ) {
		return 0;
	}

	$fields = array(
		'_feast_email'        => $enquiry['email'],
		'_feast_phone'        => $enquiry['phone'],
		'_feast_event_date'   => $enquiry['event_date'],
		'_feast_guests'       => $enquiry['guests'],
		'_feast_event_type'   => $enquiry['event_type'],
		'_feast_message'      => $enquiry['message'],
		'_feast_source'       => $enquiry['source'],
		'_feast_email_status' => 'pending',
	);

	foreach ( $fields as $key => $value ) {
		update_post_meta( $post_id, $key, $value );
	}

	return $post_id;
}

/** Add a read-only submission detail panel. */
function feast_enquiry_meta_boxes() {
	add_meta_box( 'feast-enquiry-details', 'Enquiry Details', 'feast_render_enquiry_details', 'feast_enquiry', 'normal', 'high' );
}
add_action( 'add_meta_boxes_feast_enquiry', 'feast_enquiry_meta_boxes' );

/** Render safely escaped enquiry details in WordPress admin. */
function feast_render_enquiry_details( $post ) {
	$details = array(
		'Email'                => get_post_meta( $post->ID, '_feast_email', true ),
		'Phone'                => get_post_meta( $post->ID, '_feast_phone', true ),
		'Event date'           => get_post_meta( $post->ID, '_feast_event_date', true ),
		'Guest count'          => get_post_meta( $post->ID, '_feast_guests', true ),
		'Event type'           => get_post_meta( $post->ID, '_feast_event_type', true ),
		'Notification email'   => get_post_meta( $post->ID, '_feast_email_status', true ),
		'Submitted from'       => get_post_meta( $post->ID, '_feast_source', true ),
	);

	echo '<table class="widefat striped"><tbody>';
	foreach ( $details as $label => $value ) {
		echo '<tr><th style="width:180px">' . esc_html( $label ) . '</th><td>' . esc_html( $value ? $value : '—' ) . '</td></tr>';
	}
	echo '</tbody></table>';

	$message = get_post_meta( $post->ID, '_feast_message', true );
	echo '<h3>Message</h3><div style="white-space:pre-wrap">' . esc_html( $message ? $message : 'No message supplied.' ) . '</div>';
}

/** Make the enquiry list useful at a glance. */
function feast_enquiry_columns( $columns ) {
	return array(
		'cb'           => $columns['cb'],
		'title'        => 'Name',
		'feast_contact' => 'Contact',
		'feast_event'  => 'Event',
		'feast_status' => 'Email status',
		'date'         => 'Received',
	);
}
add_filter( 'manage_feast_enquiry_posts_columns', 'feast_enquiry_columns' );

/** Populate custom columns in the enquiry inbox. */
function feast_enquiry_column_content( $column, $post_id ) {
	if ( 'feast_contact' === $column ) {
		echo esc_html( get_post_meta( $post_id, '_feast_email', true ) );
		echo '<br>' . esc_html( get_post_meta( $post_id, '_feast_phone', true ) );
	} elseif ( 'feast_event' === $column ) {
		$type   = get_post_meta( $post_id, '_feast_event_type', true );
		$date   = get_post_meta( $post_id, '_feast_event_date', true );
		$guests = get_post_meta( $post_id, '_feast_guests', true );
		echo esc_html( $type ? $type : 'Event' );
		if ( $date ) {
			echo '<br>' . esc_html( $date );
		}
		if ( $guests ) {
			echo '<br>' . esc_html( $guests . ' guests' );
		}
	} elseif ( 'feast_status' === $column ) {
		echo esc_html( ucfirst( get_post_meta( $post_id, '_feast_email_status', true ) ?: 'pending' ) );
	}
}
add_action( 'manage_feast_enquiry_posts_custom_column', 'feast_enquiry_column_content', 10, 2 );
