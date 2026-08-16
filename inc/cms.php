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
		'feast_faq' => array(
			'singular'      => 'Catering FAQ',
			'plural'        => 'Catering FAQs',
			'menu_name'     => 'Catering FAQs',
			'featured_name' => 'FAQ image',
			'supports'      => array( 'title', 'editor', 'page-attributes' ),
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
	add_submenu_page( 'feast-cms', 'Website Copy', 'Website Copy', 'manage_options', 'feast-copy', 'feast_copy_page' );
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
	register_setting( 'feast_copy_group', 'feast_site_copy', 'feast_sanitize_site_copy' );
}

function feast_copy_defaults() {
	return array(
		'announcement_text'    => 'Catering for family gatherings, celebrations and workplace events',
		'trust_1_title'       => 'Made fresh',
		'trust_1_text'        => 'From our Granville kitchen',
		'trust_2_title'       => 'Custom menus',
		'trust_2_text'        => 'Built around your event',
		'trust_3_title'       => '10–100+ guests',
		'trust_3_text'        => 'Small gatherings to big days',
		'trust_4_title'       => 'Pickup or delivery',
		'trust_4_text'        => 'Ask about your location',
		'catering_eyebrow'    => 'Catering made simple',
		'catering_title'      => 'Choose your kind of feast.',
		'catering_intro'      => 'Start with one of our popular catering styles, then we’ll tailor the dishes and quantities to your guests.',
		'menu_eyebrow'        => 'From our kitchen',
		'menu_title'          => 'The dishes people come back for.',
		'menu_intro'          => 'Traditional Middle Eastern flavours, generous portions and plenty made for sharing.',
		'process_eyebrow'     => 'How it works',
		'process_title'       => 'From your idea to their plates.',
		'process_intro'       => 'No complicated ordering. Just tell us what you’re planning and we’ll help take care of the food.',
		'step_1_title'        => 'Tell us about the event',
		'step_1_text'         => 'Share your date, guest count, event style and any dishes you already have in mind.',
		'step_2_title'        => 'We build your menu',
		'step_2_text'         => 'We’ll recommend the right mix and quantities, then send you a custom quote.',
		'step_3_title'        => 'We prepare the feast',
		'step_3_text'         => 'Your food is freshly prepared and organised for pickup or an agreed delivery.',
		'story_eyebrow'       => 'Our table is your table',
		'story_title'         => 'Food that feels like home.',
		'story_lead'          => 'Feast in the Middle East is built around the food we love to cook and share: generous, traditional dishes that bring people together.',
		'story_text'          => 'Whether you’re feeding the family or celebrating with a room full of people, every order gets the same care from our Granville kitchen.',
		'gallery_eyebrow'     => 'Recent feasts',
		'gallery_title'       => 'Made to be shared.',
		'enquiry_eyebrow'     => 'Start your catering order',
		'enquiry_title'       => 'Let’s put a feast on the table.',
		'enquiry_intro'       => 'Send us the basics and we’ll get in touch to discuss the menu, quantities and a custom quote.',
	);
}

function feast_get_site_copy() {
	return wp_parse_args( (array) get_option( 'feast_site_copy', array() ), feast_copy_defaults() );
}

function feast_copy( $key ) {
	$copy = feast_get_site_copy();
	return isset( $copy[ $key ] ) ? $copy[ $key ] : '';
}

function feast_sanitize_site_copy( $input ) {
	$output = array();
	foreach ( feast_copy_defaults() as $key => $default ) {
		$output[ $key ] = isset( $input[ $key ] ) ? sanitize_textarea_field( $input[ $key ] ) : $default;
	}
	return $output;
}

function feast_copy_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$copy = feast_get_site_copy();
	$groups = array(
		'Header announcement' => array( 'announcement_text' ),
		'Homepage trust bar' => array( 'trust_1_title', 'trust_1_text', 'trust_2_title', 'trust_2_text', 'trust_3_title', 'trust_3_text', 'trust_4_title', 'trust_4_text' ),
		'Catering section'   => array( 'catering_eyebrow', 'catering_title', 'catering_intro' ),
		'Menu section'       => array( 'menu_eyebrow', 'menu_title', 'menu_intro' ),
		'How it works'       => array( 'process_eyebrow', 'process_title', 'process_intro', 'step_1_title', 'step_1_text', 'step_2_title', 'step_2_text', 'step_3_title', 'step_3_text' ),
		'Our story section'  => array( 'story_eyebrow', 'story_title', 'story_lead', 'story_text' ),
		'Gallery section'    => array( 'gallery_eyebrow', 'gallery_title' ),
		'Enquiry section'    => array( 'enquiry_eyebrow', 'enquiry_title', 'enquiry_intro' ),
	);
	?>
	<div class="wrap feast-admin-wrap"><h1>Website Copy</h1><p class="feast-admin-lead">Edit the headings and supporting text used across the homepage. Offers, packages, dishes and images are managed from their own Feast CMS menus.</p>
	<form method="post" action="options.php"><?php settings_fields( 'feast_copy_group' ); ?>
		<?php foreach ( $groups as $group_title => $keys ) : ?><div class="feast-admin-card"><h2><?php echo esc_html( $group_title ); ?></h2>
			<?php foreach ( $keys as $key ) : $is_text = false !== strpos( $key, '_title' ) || false !== strpos( $key, '_eyebrow' ); ?><div class="feast-admin-field"><label for="<?php echo esc_attr( $key ); ?>"><strong><?php echo esc_html( ucwords( str_replace( '_', ' ', $key ) ) ); ?></strong></label><?php if ( $is_text ) : ?><input type="text" id="<?php echo esc_attr( $key ); ?>" name="feast_site_copy[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $copy[ $key ] ); ?>"><?php else : ?><textarea rows="3" id="<?php echo esc_attr( $key ); ?>" name="feast_site_copy[<?php echo esc_attr( $key ); ?>]"><?php echo esc_textarea( $copy[ $key ] ); ?></textarea><?php endif; ?></div><?php endforeach; ?>
		</div><?php endforeach; ?>
		<?php submit_button( 'Save website copy' ); ?>
	</form></div>
	<?php
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
		'page' => array(
			'_feast_page_eyebrow' => array( 'label' => 'Small heading above the page title', 'type' => 'text', 'placeholder' => 'Catering in Sydney' ),
		),
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
		'feast_faq' => array(),
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
		'page'          => 'Edit the page title, introductory excerpt, main content and featured image. Dedicated Feast page layouts update automatically.',
		'feast_offer'   => 'Add the large headline as the title, supporting sentence as the excerpt, and the background photo as the featured image.',
		'feast_bundle'  => 'Add the package name as the title. Put each inclusion on its own line. Drag is not required: use the Order field under Page Attributes.',
		'feast_dish'    => 'Add the dish name, a short description and a featured image. Use Order to control its position.',
		'feast_gallery' => 'Add a descriptive title and choose a featured image. The title is used as accessible image text.',
		'feast_faq'     => 'Add the customer question as the title and the answer in the main content editor. Use Order to control its position.',
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
