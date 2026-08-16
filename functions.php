<?php
/**
 * Theme setup and catering enquiry handling.
 *
 * @package FeastMiddleEast
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_template_directory() . '/inc/cms.php';
require_once get_template_directory() . '/inc/seed.php';

function feast_theme_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo', array( 'height' => 120, 'width' => 120, 'flex-height' => true, 'flex-width' => true ) );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	register_nav_menus( array( 'primary' => __( 'Primary Menu', 'feast-middle-east' ) ) );
	add_post_type_support( 'page', 'excerpt' );
}
add_action( 'after_setup_theme', 'feast_theme_setup' );

function feast_theme_assets() {
	$theme = wp_get_theme();
	wp_enqueue_style( 'feast-style', get_stylesheet_uri(), array(), $theme->get( 'Version' ) );
	wp_enqueue_script( 'feast-main', get_template_directory_uri() . '/assets/js/main.js', array(), $theme->get( 'Version' ), true );
}
add_action( 'wp_enqueue_scripts', 'feast_theme_assets' );

function feast_asset( $file ) {
	return esc_url( get_template_directory_uri() . '/assets/images/' . ltrim( $file, '/' ) );
}

function feast_handle_enquiry() {
	$redirect = wp_get_referer() ? wp_get_referer() : home_url( '/' );

	if ( ! isset( $_POST['feast_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['feast_nonce'] ) ), 'feast_catering_enquiry' ) ) {
		wp_safe_redirect( add_query_arg( 'enquiry', 'error', $redirect ) . '#catering-enquiry' );
		exit;
	}

	if ( ! empty( $_POST['website'] ) ) {
		wp_safe_redirect( add_query_arg( 'enquiry', 'sent', $redirect ) . '#catering-enquiry' );
		exit;
	}

	$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$phone   = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$date    = isset( $_POST['event_date'] ) ? sanitize_text_field( wp_unslash( $_POST['event_date'] ) ) : '';
	$guests  = isset( $_POST['guests'] ) ? absint( $_POST['guests'] ) : 0;
	$type    = isset( $_POST['event_type'] ) ? sanitize_text_field( wp_unslash( $_POST['event_type'] ) ) : '';
	$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

	if ( empty( $name ) || ! is_email( $email ) || empty( $phone ) ) {
		wp_safe_redirect( add_query_arg( 'enquiry', 'missing', $redirect ) . '#catering-enquiry' );
		exit;
	}

	$subject = sprintf( 'New catering enquiry from %s', $name );
	$body    = "Name: {$name}\nEmail: {$email}\nPhone: {$phone}\nEvent date: {$date}\nGuests: {$guests}\nEvent type: {$type}\n\nDetails:\n{$message}";
	$headers = array( 'Reply-To: ' . $name . ' <' . $email . '>' );
	$sent    = wp_mail( feast_setting( 'enquiry_email' ), $subject, $body, $headers );

	wp_safe_redirect( add_query_arg( 'enquiry', $sent ? 'sent' : 'error', $redirect ) . '#catering-enquiry' );
	exit;
}
add_action( 'admin_post_nopriv_feast_catering_enquiry', 'feast_handle_enquiry' );
add_action( 'admin_post_feast_catering_enquiry', 'feast_handle_enquiry' );
