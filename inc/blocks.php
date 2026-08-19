<?php
/**
 * Native Gutenberg editing experience for the coded Feast homepage.
 *
 * @package FeastMiddleEast
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Homepage fields exposed in the visual editor sidebar.
 */
function feast_homepage_block_field_groups() {
	return array(
		'Catering introduction' => array( 'catering_eyebrow', 'catering_title', 'catering_intro' ),
		'Google reviews'        => array( 'reviews_eyebrow', 'reviews_title', 'reviews_intro' ),
		'Menu introduction'     => array( 'menu_eyebrow', 'menu_title', 'menu_intro', 'menu_category_mains', 'menu_category_salads', 'menu_category_bites' ),
		'How it works'          => array( 'process_eyebrow', 'process_title', 'process_intro', 'step_1_title', 'step_1_text', 'step_2_title', 'step_2_text', 'step_3_title', 'step_3_text' ),
		'Our story'             => array( 'story_eyebrow', 'story_title', 'story_lead', 'story_text', 'story_stamp', 'story_cta_label', 'story_cta_link' ),
		'Gallery introduction'  => array( 'gallery_eyebrow', 'gallery_title', 'gallery_cta_label', 'gallery_cta_link' ),
		'Enquiry introduction'  => array( 'enquiry_eyebrow', 'enquiry_title', 'enquiry_intro' ),
		'Enquiry form'          => array( 'form_success', 'form_error', 'form_name_label', 'form_phone_label', 'form_email_label', 'form_date_label', 'form_guests_label', 'form_guests_placeholder', 'form_type_label', 'form_type_placeholder', 'form_type_options', 'form_message_label', 'form_message_placeholder', 'form_submit_label', 'form_note' ),
	);
}

function feast_homepage_block_field_keys() {
	$keys = array();
	foreach ( feast_homepage_block_field_groups() as $group_keys ) {
		$keys = array_merge( $keys, $group_keys );
	}
	return array_values( array_unique( $keys ) );
}

function feast_homepage_block_attributes() {
	$defaults   = feast_copy_defaults();
	$attributes = array();
	foreach ( feast_homepage_block_field_keys() as $key ) {
		$attributes[ $key ] = array(
			'type'    => 'string',
			'default' => isset( $defaults[ $key ] ) ? $defaults[ $key ] : '',
		);
	}
	return $attributes;
}

/**
 * Render the real coded homepage inside Gutenberg and on the public site.
 */
function feast_render_homepage_block( $attributes ) {
	global $feast_homepage_copy_overrides;

	$previous_overrides             = isset( $feast_homepage_copy_overrides ) ? $feast_homepage_copy_overrides : null;
	$feast_homepage_copy_overrides = array_intersect_key( $attributes, array_flip( feast_homepage_block_field_keys() ) );

	ob_start();
	get_template_part( 'template-parts/homepage-sections' );
	$output = ob_get_clean();

	$feast_homepage_copy_overrides = $previous_overrides;
	return '<div class="feast-homepage-block">' . $output . '</div>';
}

function feast_register_homepage_block() {
	$theme = wp_get_theme();
	wp_register_script(
		'feast-homepage-editor',
		get_template_directory_uri() . '/assets/js/editor-homepage.js',
		array( 'wp-blocks', 'wp-block-editor', 'wp-components', 'wp-element', 'wp-i18n', 'wp-server-side-render' ),
		$theme->get( 'Version' ),
		true
	);
	wp_localize_script(
		'feast-homepage-editor',
		'feastHomepageEditor',
		array(
			'groups'     => feast_homepage_block_field_groups(),
			'attributes' => feast_homepage_block_attributes(),
			'links'      => array(
				'hero'     => admin_url( 'edit.php?post_type=feast_offer' ),
				'packages' => admin_url( 'edit.php?post_type=feast_bundle' ),
				'menu'     => admin_url( 'edit.php?post_type=feast_dish' ),
				'gallery'  => admin_url( 'edit.php?post_type=feast_gallery' ),
				'copy'     => admin_url( 'admin.php?page=feast-copy' ),
				'design'   => admin_url( 'admin.php?page=feast-design' ),
			),
		)
	);

	register_block_type(
		'feast/homepage',
		array(
			'api_version'     => 3,
			'attributes'      => feast_homepage_block_attributes(),
			'editor_script'   => 'feast-homepage-editor',
			'render_callback' => 'feast_render_homepage_block',
			'supports'        => array(
				'html'     => false,
				'multiple' => false,
				'reusable' => false,
			),
		)
	);
}
add_action( 'init', 'feast_register_homepage_block', 20 );

/**
 * Keep the visual Home editor focused on the protected coded layout.
 */
function feast_homepage_allowed_blocks( $allowed_blocks, $editor_context ) {
	$home_id = absint( get_option( 'page_on_front' ) );
	if ( $home_id && ! empty( $editor_context->post ) && $home_id === (int) $editor_context->post->ID ) {
		return array( 'feast/homepage' );
	}
	return $allowed_blocks;
}
add_filter( 'allowed_block_types_all', 'feast_homepage_allowed_blocks', 10, 2 );

/**
 * Replace the old placeholder paragraph with the locked visual homepage block.
 * A recoverable copy of the previous page content is kept in post meta.
 */
function feast_migrate_homepage_to_visual_block() {
	if ( get_option( 'feast_visual_homepage_v1' ) ) {
		return;
	}
	$existing_lock = absint( get_option( 'feast_visual_homepage_lock' ) );
	if ( $existing_lock && ( time() - $existing_lock ) > 300 ) {
		delete_option( 'feast_visual_homepage_lock' );
	}
	if ( ! add_option( 'feast_visual_homepage_lock', time(), '', false ) ) {
		return;
	}

	$home_id = absint( get_option( 'page_on_front' ) );
	if ( ! $home_id ) {
		$home_page = get_page_by_path( 'home', OBJECT, 'page' );
		if ( $home_page ) {
			$home_id = (int) $home_page->ID;
		} else {
			$home_id = wp_insert_post(
				array(
					'post_title'     => 'Home',
					'post_name'      => 'home',
					'post_type'      => 'page',
					'post_status'    => 'publish',
					'comment_status' => 'closed',
				),
				true
			);
			if ( is_wp_error( $home_id ) ) {
				delete_option( 'feast_visual_homepage_lock' );
				return;
			}
		}
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $home_id );
	}

	$current_content = (string) get_post_field( 'post_content', $home_id );
	if ( has_block( 'feast/homepage', $current_content ) ) {
		update_option( 'feast_visual_homepage_v1', '1', false );
		delete_option( 'feast_visual_homepage_lock' );
		return;
	}

	if ( $current_content ) {
		update_post_meta( $home_id, '_feast_pre_visual_homepage_content', $current_content );
	}

	$copy       = feast_get_site_copy();
	$attributes = array_intersect_key( $copy, array_flip( feast_homepage_block_field_keys() ) );
	$attributes['lock'] = array( 'move' => true, 'remove' => true );
	$block      = serialize_block(
		array(
			'blockName'    => 'feast/homepage',
			'attrs'        => $attributes,
			'innerBlocks'  => array(),
			'innerHTML'    => '',
			'innerContent' => array(),
		)
	);
	$result     = wp_update_post(
		array(
			'ID'           => $home_id,
			'post_content' => $block,
		),
		true
	);

	if ( ! is_wp_error( $result ) ) {
		update_option( 'feast_visual_homepage_v1', '1', false );
	}
	delete_option( 'feast_visual_homepage_lock' );
}
add_action( 'init', 'feast_migrate_homepage_to_visual_block', 60 );
