<?php
/**
 * Native Gutenberg patterns and one-time content migration.
 *
 * @package FeastMiddleEast
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function feast_register_pattern_category() {
	register_block_pattern_category( 'feast', array( 'label' => __( 'Feast sections', 'feast-middle-east' ) ) );
}
add_action( 'init', 'feast_register_pattern_category', 9 );

/** Read a theme pattern as ordinary Gutenberg block markup. */
function feast_pattern_markup( $filename ) {
	$path = get_template_directory() . '/patterns/' . sanitize_file_name( $filename ) . '.php';
	if ( ! is_readable( $path ) ) {
		return '';
	}
	ob_start();
	include $path;
	return trim( (string) ob_get_clean() );
}

/**
 * Replace the old image-like block with editable nested core blocks.
 * A recoverable copy of the previous page content is kept in post meta.
 */
function feast_migrate_homepage_to_native_blocks() {
	if ( get_option( 'feast_native_homepage_v2' ) ) {
		return;
	}
	if ( ! add_option( 'feast_native_homepage_v2_lock', time(), '', false ) ) {
		return;
	}

	$home_id = absint( get_option( 'page_on_front' ) );
	if ( ! $home_id ) {
		$home    = get_page_by_path( 'home', OBJECT, 'page' );
		$home_id = $home ? (int) $home->ID : 0;
	}
	$markup = feast_pattern_markup( 'homepage' );
	if ( ! $home_id || ! $markup ) {
		delete_option( 'feast_native_homepage_v2_lock' );
		return;
	}

	$current = (string) get_post_field( 'post_content', $home_id );
	$legacy  = has_block( 'feast/homepage', $current );
	$starter = ! $current || false !== stripos( wp_strip_all_tags( $current ), 'Welcome to WordPress' );

	if ( $legacy || $starter ) {
		if ( $current ) {
			update_post_meta( $home_id, '_feast_before_native_blocks', $current );
		}
		$result = wp_update_post( array( 'ID' => $home_id, 'post_content' => $markup ), true );
		if ( ! is_wp_error( $result ) ) {
			update_option( 'feast_native_homepage_v2', '1', false );
		}
	} else {
		// Never overwrite content already rebuilt manually by the site owner.
		update_option( 'feast_native_homepage_v2', '1', false );
	}

	delete_option( 'feast_native_homepage_v2_lock' );
}
add_action( 'init', 'feast_migrate_homepage_to_native_blocks', 70 );

/** Give each dedicated page a native, element-by-element editable starting layout. */
function feast_migrate_inner_pages_to_native_blocks() {
	if ( get_option( 'feast_native_inner_pages_v1' ) ) {
		return;
	}
	if ( ! add_option( 'feast_native_inner_pages_v1_lock', time(), '', false ) ) {
		return;
	}

	$pages = array(
		'menu'      => 'page-menu',
		'catering'  => 'page-catering',
		'our-story' => 'page-our-story',
		'gallery'   => 'page-gallery',
		'contact'   => 'page-contact',
	);
	foreach ( $pages as $slug => $pattern ) {
		$page   = get_page_by_path( $slug, OBJECT, 'page' );
		$markup = feast_pattern_markup( $pattern );
		if ( ! $page || ! $markup ) {
			continue;
		}
		$current = (string) $page->post_content;
		if ( false !== strpos( $current, 'feast-native-page' ) ) {
			continue;
		}
		if ( $current ) {
			update_post_meta( $page->ID, '_feast_before_native_blocks', $current );
		}
		wp_update_post( array( 'ID' => $page->ID, 'post_content' => $markup ) );
	}
	update_option( 'feast_native_inner_pages_v1', '1', false );
	delete_option( 'feast_native_inner_pages_v1_lock' );
}
add_action( 'init', 'feast_migrate_inner_pages_to_native_blocks', 75 );

/** Keep the existing coded enquiry form available inside a Shortcode block. */
function feast_enquiry_form_shortcode() {
	ob_start();
	get_template_part( 'template-parts/enquiry-form' );
	return (string) ob_get_clean();
}
add_shortcode( 'feast_enquiry_form', 'feast_enquiry_form_shortcode' );
