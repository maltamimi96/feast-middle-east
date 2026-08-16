<?php
/**
 * Native WordPress CMS controls for homepage content.
 *
 * @package FeastMiddleEast
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function feast_register_content_types() {
	$types = array(
		'feast_offer' => array(
			'singular'      => 'Hero Offer',
			'plural'        => 'Hero Offers',
			'menu_name'     => 'Hero Offers',
			'featured_name' => 'Offer background image',
			'supports'      => array( 'title', 'excerpt', 'thumbnail', 'page-attributes' ),
		),
		'feast_bundle' => array(
			'singular'      => 'Catering Package',
			'plural'        => 'Catering Packages',
			'menu_name'     => 'Catering Packages',
			'featured_name' => 'Package image',
			'supports'      => array( 'title', 'excerpt', 'thumbnail', 'page-attributes' ),
		),
		'feast_dish' => array(
			'singular'      => 'Menu Dish',
			'plural'        => 'Menu Dishes',
			'menu_name'     => 'Menu Dishes',
			'featured_name' => 'Dish image',
			'supports'      => array( 'title', 'excerpt', 'thumbnail', 'page-attributes' ),
		),
		'feast_gallery' => array(
			'singular'      => 'Gallery Image',
			'plural'        => 'Gallery Images',
			'menu_name'     => 'Gallery Images',
			'featured_name' => 'Gallery image',
			'supports'      => array( 'title', 'thumbnail', 'page-attributes' ),
		),
	);

	foreach ( $types as $slug => $type ) {
		register_post_type(
			$slug,
			array(
				'labels' => array(
					'name'                  => $type['plural'],
					'singular_name'         => $type['singular'],
					'menu_name'             => $type['menu_name'],
					'add_new'               => 'Add New',
					'add_new_item'          => 'Add New ' . $type['singular'],
					'edit_item'             => 'Edit ' . $type['singular'],
					'new_item'              => 'New ' . $type['singular'],
					'view_item'             => 'View ' . $type['singular'],
					'search_items'          => 'Search ' . $type['plural'],
					'not_found'             => 'No ' . strtolower( $type['plural'] ) . ' found.',
					'featured_image'        => $type['featured_name'],
					'set_featured_image'    => 'Choose ' . strtolower( $type['featured_name'] ),
					'remove_featured_image' => 'Remove image',
				),
				'public'              => false,
				'show_ui'             => true,
				'show_in_menu'        => 'feast-cms',
				'show_in_rest'        => false,
				'supports'            => $type['supports'],
				'menu_position'       => 25,
				'capability_type'     => 'post',
				'map_meta_cap'        => true,
				'has_archive'         => false,
				'publicly_queryable'  => false,
				'exclude_from_search' => true,
			)
		);
	}
}
add_action( 'init', 'feast_register_content_types' );

function feast_cms_menu() {
	add_menu_page(
		'Feast CMS',
		'Feast CMS',
		'edit_pages',
		'feast-cms',
		'feast_settings_page',
		'dashicons-food',
		24
	);
	add_submenu_page( 'feast-cms', 'Business Settings', 'Business Settings', 'manage_options', 'feast-settings', 'feast_settings_page' );
}
add_action( 'admin_menu', 'feast_cms_menu' );

function feast_business_defaults() {
	return array(
		'phone_display' => '0407 495 908',
		'phone_link'    => '+61407495908',
		'address'       => "Inside HAWA Food Spot\n43 South St, Granville NSW",
		'instagram'     => 'https://www.instagram.com/feast_in_the_middle_east/',
		'enquiry_email' => get_option( 'admin_email' ),
	);
}

function feast_get_business_settings() {
	return wp_parse_args( (array) get_option( 'feast_business', array() ), feast_business_defaults() );
}

function feast_setting( $key ) {
	$settings = feast_get_business_settings();
	return isset( $settings[ $key ] ) ? $settings[ $key ] : '';
}

function feast_register_settings() {
	register_setting( 'feast_settings_group', 'feast_business', 'feast_sanitize_business_settings' );
}
add_action( 'admin_init', 'feast_register_settings' );

function feast_sanitize_business_settings( $input ) {
	$defaults = feast_business_defaults();
	$output   = array();
	$output['phone_display'] = isset( $input['phone_display'] ) ? sanitize_text_field( $input['phone_display'] ) : $defaults['phone_display'];
	$output['phone_link']    = isset( $input['phone_link'] ) ? preg_replace( '/[^0-9+]/', '', $input['phone_link'] ) : $defaults['phone_link'];
	$output['address']       = isset( $input['address'] ) ? sanitize_textarea_field( $input['address'] ) : $defaults['address'];
	$output['instagram']     = isset( $input['instagram'] ) ? esc_url_raw( $input['instagram'] ) : $defaults['instagram'];
	$output['enquiry_email'] = isset( $input['enquiry_email'] ) && is_email( $input['enquiry_email'] ) ? sanitize_email( $input['enquiry_email'] ) : $defaults['enquiry_email'];
	return $output;
}

function feast_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$settings = feast_get_business_settings();
	?>
	<div class="wrap feast-admin-wrap">
		<h1>Feast CMS</h1>
		<p class="feast-admin-lead">Manage the information used across the website. Use the menu on the left to add hero offers, catering packages, dishes and gallery images.</p>
		<div class="feast-admin-card">
			<h2>Business settings</h2>
			<form method="post" action="options.php">
				<?php settings_fields( 'feast_settings_group' ); ?>
				<table class="form-table" role="presentation">
					<tr><th scope="row"><label for="feast-phone-display">Phone shown on site</label></th><td><input class="regular-text" id="feast-phone-display" name="feast_business[phone_display]" value="<?php echo esc_attr( $settings['phone_display'] ); ?>"></td></tr>
					<tr><th scope="row"><label for="feast-phone-link">Phone link</label></th><td><input class="regular-text" id="feast-phone-link" name="feast_business[phone_link]" value="<?php echo esc_attr( $settings['phone_link'] ); ?>"><p class="description">Use international format, for example +61407495908.</p></td></tr>
					<tr><th scope="row"><label for="feast-address">Address</label></th><td><textarea class="large-text" rows="3" id="feast-address" name="feast_business[address]"><?php echo esc_textarea( $settings['address'] ); ?></textarea></td></tr>
					<tr><th scope="row"><label for="feast-instagram">Instagram URL</label></th><td><input class="large-text" type="url" id="feast-instagram" name="feast_business[instagram]" value="<?php echo esc_attr( $settings['instagram'] ); ?>"></td></tr>
					<tr><th scope="row"><label for="feast-email">Catering enquiry email</label></th><td><input class="regular-text" type="email" id="feast-email" name="feast_business[enquiry_email]" value="<?php echo esc_attr( $settings['enquiry_email'] ); ?>"><p class="description">Quote requests from the website are sent here.</p></td></tr>
				</table>
				<?php submit_button( 'Save business settings' ); ?>
			</form>
		</div>
	</div>
	<?php
}

function feast_meta_fields() {
	return array(
		'feast_offer' => array(
			'_feast_eyebrow'        => array( 'label' => 'Small heading', 'type' => 'text', 'placeholder' => 'Middle Eastern catering across Sydney' ),
			'_feast_primary_label'  => array( 'label' => 'Main button text', 'type' => 'text', 'placeholder' => 'Request a catering quote' ),
			'_feast_primary_link'   => array( 'label' => 'Main button link', 'type' => 'text', 'placeholder' => '#catering-enquiry' ),
			'_feast_second_label'   => array( 'label' => 'Second button text', 'type' => 'text', 'placeholder' => 'Explore catering' ),
			'_feast_second_link'    => array( 'label' => 'Second button link', 'type' => 'text', 'placeholder' => '#catering' ),
			'_feast_note'           => array( 'label' => 'Small note', 'type' => 'text', 'placeholder' => 'Freshly prepared in Granville' ),
		),
		'feast_bundle' => array(
			'_feast_tag'            => array( 'label' => 'Package badge', 'type' => 'text', 'placeholder' => 'Most popular' ),
			'_feast_audience'       => array( 'label' => 'Best for', 'type' => 'text', 'placeholder' => 'Ideal for 25–100+ guests' ),
			'_feast_features'       => array( 'label' => 'Package inclusions', 'type' => 'textarea', 'placeholder' => "One inclusion per line\nFresh salads and traditional sides" ),
			'_feast_cta_label'      => array( 'label' => 'Link text', 'type' => 'text', 'placeholder' => 'Plan my celebration' ),
			'_feast_featured'       => array( 'label' => 'Highlight this package', 'type' => 'checkbox' ),
		),
		'feast_dish' => array(
			'_feast_category'       => array( 'label' => 'Menu category', 'type' => 'select', 'options' => array( 'mains' => 'Hearty mains', 'salads' => 'Salads & sides', 'bites' => 'Bites & extras' ) ),
			'_feast_showcase'       => array( 'label' => 'Show as a large image card', 'type' => 'checkbox' ),
		),
		'feast_gallery' => array(),
	);
}

function feast_add_meta_boxes() {
	foreach ( feast_meta_fields() as $post_type => $fields ) {
		add_meta_box( 'feast-content-details', 'Website display settings', 'feast_render_meta_box', $post_type, 'normal', 'high' );
		add_meta_box( 'feast-publishing-help', 'How this appears', 'feast_render_help_box', $post_type, 'side', 'default' );
	}
}
add_action( 'add_meta_boxes', 'feast_add_meta_boxes' );

function feast_render_meta_box( $post ) {
	$all_fields = feast_meta_fields();
	$fields     = isset( $all_fields[ $post->post_type ] ) ? $all_fields[ $post->post_type ] : array();
	wp_nonce_field( 'feast_save_content', 'feast_content_nonce' );
	if ( empty( $fields ) ) {
		echo '<p>Use the title for the image description, choose a featured image, set the order, then publish.</p>';
		return;
	}
	foreach ( $fields as $key => $field ) {
		$value = get_post_meta( $post->ID, $key, true );
		echo '<div class="feast-admin-field">';
		if ( 'checkbox' === $field['type'] ) {
			printf( '<label><input type="checkbox" name="%1$s" value="1" %2$s> <strong>%3$s</strong></label>', esc_attr( $key ), checked( $value, '1', false ), esc_html( $field['label'] ) );
		} elseif ( 'textarea' === $field['type'] ) {
			printf( '<label for="%1$s"><strong>%2$s</strong></label><textarea id="%1$s" name="%1$s" rows="5" placeholder="%3$s">%4$s</textarea>', esc_attr( $key ), esc_html( $field['label'] ), esc_attr( $field['placeholder'] ), esc_textarea( $value ) );
		} elseif ( 'select' === $field['type'] ) {
			printf( '<label for="%1$s"><strong>%2$s</strong></label><select id="%1$s" name="%1$s">', esc_attr( $key ), esc_html( $field['label'] ) );
			foreach ( $field['options'] as $option_value => $option_label ) {
				printf( '<option value="%1$s" %2$s>%3$s</option>', esc_attr( $option_value ), selected( $value, $option_value, false ), esc_html( $option_label ) );
			}
			echo '</select>';
		} else {
			printf( '<label for="%1$s"><strong>%2$s</strong></label><input type="text" id="%1$s" name="%1$s" value="%3$s" placeholder="%4$s">', esc_attr( $key ), esc_html( $field['label'] ), esc_attr( $value ), esc_attr( $field['placeholder'] ) );
		}
		echo '</div>';
	}
}

function feast_render_help_box( $post ) {
	$guides = array(
		'feast_offer'   => 'Add the large headline as the title, supporting sentence as the excerpt, and the background photo as the featured image.',
		'feast_bundle'  => 'Add the package name as the title. Put each inclusion on its own line. Drag is not required: use the Order field under Page Attributes.',
		'feast_dish'    => 'Add the dish name, a short description and a featured image. Use Order to control its position.',
		'feast_gallery' => 'Add a descriptive title and choose a featured image. The title is used as accessible image text.',
	);
	echo '<p>' . esc_html( $guides[ $post->post_type ] ) . '</p><p><strong>Publish</strong> to put this item on the website. Save as <strong>Draft</strong> to hide it.</p>';
}

function feast_save_content_meta( $post_id ) {
	if ( ! isset( $_POST['feast_content_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['feast_content_nonce'] ) ), 'feast_save_content' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$post_type  = get_post_type( $post_id );
	$all_fields = feast_meta_fields();
	if ( ! isset( $all_fields[ $post_type ] ) ) {
		return;
	}
	foreach ( $all_fields[ $post_type ] as $key => $field ) {
		if ( 'checkbox' === $field['type'] ) {
			update_post_meta( $post_id, $key, isset( $_POST[ $key ] ) ? '1' : '0' );
			continue;
		}
		if ( ! isset( $_POST[ $key ] ) ) {
			delete_post_meta( $post_id, $key );
			continue;
		}
		$raw = wp_unslash( $_POST[ $key ] );
		if ( 'textarea' === $field['type'] ) {
			$value = sanitize_textarea_field( $raw );
		} elseif ( false !== strpos( $key, '_link' ) && 0 !== strpos( $raw, '#' ) ) {
			$value = esc_url_raw( $raw );
		} else {
			$value = sanitize_text_field( $raw );
		}
		update_post_meta( $post_id, $key, $value );
	}
}
add_action( 'save_post', 'feast_save_content_meta' );

function feast_content_items( $post_type, $limit = -1, $meta_query = array() ) {
	$args = array(
		'post_type'      => $post_type,
		'post_status'    => 'publish',
		'posts_per_page' => $limit,
		'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
		'order'          => 'ASC',
		'no_found_rows'  => true,
	);
	if ( ! empty( $meta_query ) ) {
		$args['meta_query'] = $meta_query;
	}
	return get_posts( $args );
}

function feast_admin_assets( $hook ) {
	$screen = get_current_screen();
	if ( ! $screen || ( 0 !== strpos( $screen->id, 'feast_' ) && false === strpos( $screen->id, 'feast-cms' ) && false === strpos( $screen->id, 'feast-settings' ) ) ) {
		return;
	}
	wp_enqueue_style( 'feast-admin', get_template_directory_uri() . '/assets/css/admin.css', array(), wp_get_theme()->get( 'Version' ) );
}
add_action( 'admin_enqueue_scripts', 'feast_admin_assets' );

