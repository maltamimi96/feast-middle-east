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
 * Claim a short-lived lock so a migration cannot run twice concurrently.
 *
 * add_option() on its own leaves the lock behind for good if the request dies
 * part way through, which silently blocks the migration on every later request.
 * A lock older than the timeout is treated as abandoned and reclaimed.
 */
function feast_claim_migration_lock( $key, $timeout = 300 ) {
	$existing = get_option( $key );

	if ( false === $existing ) {
		return (bool) add_option( $key, time(), '', false );
	}

	if ( ( time() - (int) $existing ) < $timeout ) {
		return false;
	}

	update_option( $key, time(), false );
	return true;
}

/**
 * Replace the old image-like block with editable nested core blocks.
 * A recoverable copy of the previous page content is kept in post meta.
 */
function feast_migrate_homepage_to_native_blocks() {
	if ( get_option( 'feast_native_homepage_v2' ) ) {
		return;
	}
	if ( ! feast_claim_migration_lock( 'feast_native_homepage_v2_lock' ) ) {
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

/**
 * Undo the inner-page block migration.
 *
 * Catering, Menu, Our Story, Gallery and Contact are rendered by their own PHP
 * templates, which build their sections from the Feast CMS and use the page
 * content only as the short intro. Giving those pages a full static block
 * layout meant the same packages, dishes and heroes were output twice. The
 * block layout is stashed in post meta rather than discarded.
 */
function feast_restore_inner_pages_from_native_blocks() {
	if ( get_option( 'feast_inner_pages_classic_v1' ) ) {
		return;
	}
	if ( ! feast_claim_migration_lock( 'feast_inner_pages_classic_v1_lock' ) ) {
		return;
	}

	foreach ( array( 'menu', 'catering', 'our-story', 'gallery', 'contact' ) as $slug ) {
		$page = get_page_by_path( $slug, OBJECT, 'page' );
		if ( ! $page || false === strpos( (string) $page->post_content, 'feast-native-page' ) ) {
			continue;
		}

		update_post_meta( $page->ID, '_feast_reverted_native_blocks', $page->post_content );
		wp_update_post(
			array(
				'ID'           => $page->ID,
				'post_content' => (string) get_post_meta( $page->ID, '_feast_before_native_blocks', true ),
			)
		);
	}

	update_option( 'feast_inner_pages_classic_v1', '1', false );
	delete_option( 'feast_inner_pages_classic_v1_lock' );
}
add_action( 'init', 'feast_restore_inner_pages_from_native_blocks', 75 );

/** Keep the existing coded enquiry form available inside a Shortcode block. */
function feast_enquiry_form_shortcode() {
	ob_start();
	get_template_part( 'template-parts/enquiry-form' );
	return (string) ob_get_clean();
}
add_shortcode( 'feast_enquiry_form', 'feast_enquiry_form_shortcode' );
