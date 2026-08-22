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
				'show_in_rest'        => true,
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
	add_submenu_page( 'feast-cms', 'Design & Branding', 'Design & Branding', 'manage_options', 'feast-design', 'feast_design_page' );
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
	register_setting( 'feast_design_group', 'feast_design', 'feast_sanitize_design_settings' );
}

function feast_design_defaults() {
	return array(
		'ink'            => '#171b18',
		'forest'         => '#173f32',
		'forest_light'   => '#245c49',
		'cedar'          => '#2f765c',
		'red'            => '#b83d35',
		'cream'          => '#f7f1e6',
		'paper'          => '#fffdf8',
		'sand'           => '#dfc89f',
		'muted'          => '#676b66',
		'header_bg'      => '#0a0f0c',
		'footer_bg'      => '#101713',
		'hero_text'      => '#fffdf8',
		'hero_accent'    => '#edd7ad',
		'button_text'    => '#ffffff',
		'corner_radius'  => '10',
		'hero_height_vh' => '70',
		'site_width'             => '1240',
		'desktop_gutter'         => '24',
		'mobile_gutter'          => '10',
		'section_padding'        => '144',
		'mobile_section_padding' => '72',
		'section_heading_gap'    => '40',
		'wide_grid_gap'          => '112',
		'enquiry_grid_gap'       => '96',
		'content_page_width'     => '820',
		'content_page_top'       => '144',
		'content_page_bottom'    => '80',
		'base_font_size'         => '16',
		'body_line_height'       => '165',
		'h1_size'                => '128',
		'h2_size'                => '77',
		'h3_size'                => '30',
		'eyebrow_size'           => '41',
		'lead_size'              => '20',
		'announcement_height'    => '38',
		'header_height'          => '112',
		'mobile_header_height'   => '86',
		'logo_size'              => '66',
		'nav_gap'                => '32',
		'mobile_hero_min_height' => '720',
		'hero_content_top'       => '176',
		'hero_content_bottom'    => '84',
		'hero_text_width'        => '980',
		'hero_title_size'        => '92',
		'hero_body_size'         => '22',
		'mobile_hero_content_top'=> '156',
		'mobile_hero_content_bottom' => '152',
		'mobile_hero_title_size' => '58',
		'inner_hero_height'      => '620',
		'mobile_inner_hero_height' => '560',
		'inner_hero_bottom'      => '80',
		'grid_gap'               => '20',
		'card_padding'           => '36',
		'feature_padding'        => '64',
		'button_height'          => '52',
		'button_padding_x'       => '22',
		'button_radius'          => '2',
		'form_padding'           => '48',
		'mobile_form_padding'    => '20',
		'gallery_gap'            => '13',
		'footer_top'             => '64',
		'footer_bottom'          => '24',
		'footer_grid_gap'        => '32',
	);
}

/**
 * Numeric site-wide styling controls and their safe limits.
 */
function feast_design_control_groups() {
	return array(
		'Page layout & spacing' => array(
			'site_width'             => array( 'label' => 'Maximum content width', 'min' => 900, 'max' => 1600, 'unit' => 'px' ),
			'desktop_gutter'         => array( 'label' => 'Desktop side padding', 'min' => 12, 'max' => 100, 'unit' => 'px' ),
			'mobile_gutter'          => array( 'label' => 'Mobile side padding', 'min' => 8, 'max' => 40, 'unit' => 'px' ),
			'section_padding'        => array( 'label' => 'Desktop section top / bottom padding', 'min' => 40, 'max' => 240, 'unit' => 'px' ),
			'mobile_section_padding' => array( 'label' => 'Mobile section top / bottom padding', 'min' => 24, 'max' => 140, 'unit' => 'px' ),
			'section_heading_gap'    => array( 'label' => 'Space below section headings', 'min' => 12, 'max' => 100, 'unit' => 'px' ),
			'wide_grid_gap'          => array( 'label' => 'Story image / text column gap', 'min' => 20, 'max' => 180, 'unit' => 'px' ),
			'enquiry_grid_gap'       => array( 'label' => 'Enquiry text / form column gap', 'min' => 20, 'max' => 160, 'unit' => 'px' ),
			'content_page_width'     => array( 'label' => 'Standard text page width', 'min' => 560, 'max' => 1200, 'unit' => 'px' ),
			'content_page_top'       => array( 'label' => 'Standard page top padding', 'min' => 80, 'max' => 240, 'unit' => 'px' ),
			'content_page_bottom'    => array( 'label' => 'Standard page bottom padding', 'min' => 40, 'max' => 180, 'unit' => 'px' ),
		),
		'Typography' => array(
			'base_font_size'   => array( 'label' => 'Body text size', 'min' => 14, 'max' => 22, 'unit' => 'px' ),
			'body_line_height' => array( 'label' => 'Body line height', 'min' => 120, 'max' => 210, 'unit' => '%' ),
			'h1_size'          => array( 'label' => 'Maximum H1 size', 'min' => 48, 'max' => 160, 'unit' => 'px' ),
			'h2_size'          => array( 'label' => 'Maximum H2 size', 'min' => 36, 'max' => 110, 'unit' => 'px' ),
			'h3_size'          => array( 'label' => 'Maximum H3 size', 'min' => 20, 'max' => 52, 'unit' => 'px' ),
			'eyebrow_size'     => array( 'label' => 'Script heading size', 'min' => 22, 'max' => 64, 'unit' => 'px' ),
			'lead_size'        => array( 'label' => 'Intro / lead text size', 'min' => 16, 'max' => 34, 'unit' => 'px' ),
		),
		'Header & navigation' => array(
			'announcement_height'  => array( 'label' => 'Announcement bar height', 'min' => 28, 'max' => 70, 'unit' => 'px' ),
			'header_height'        => array( 'label' => 'Desktop navigation height', 'min' => 72, 'max' => 170, 'unit' => 'px' ),
			'mobile_header_height' => array( 'label' => 'Mobile navigation height', 'min' => 64, 'max' => 130, 'unit' => 'px' ),
			'logo_size'            => array( 'label' => 'Logo size', 'min' => 36, 'max' => 110, 'unit' => 'px' ),
			'nav_gap'              => array( 'label' => 'Navigation link spacing', 'min' => 8, 'max' => 70, 'unit' => 'px' ),
		),
		'Hero sections' => array(
			'hero_height_vh'         => array( 'label' => 'Homepage hero height', 'min' => 55, 'max' => 100, 'unit' => 'vh' ),
			'mobile_hero_min_height' => array( 'label' => 'Mobile homepage hero minimum height', 'min' => 560, 'max' => 1000, 'unit' => 'px' ),
			'hero_content_top'       => array( 'label' => 'Homepage hero content top padding', 'min' => 80, 'max' => 300, 'unit' => 'px' ),
			'hero_content_bottom'    => array( 'label' => 'Homepage hero content bottom padding', 'min' => 30, 'max' => 220, 'unit' => 'px' ),
			'hero_text_width'        => array( 'label' => 'Homepage hero text area width', 'min' => 520, 'max' => 1300, 'unit' => 'px' ),
			'hero_title_size'        => array( 'label' => 'Desktop homepage hero title size', 'min' => 48, 'max' => 130, 'unit' => 'px' ),
			'hero_body_size'         => array( 'label' => 'Desktop homepage hero paragraph size', 'min' => 16, 'max' => 36, 'unit' => 'px' ),
			'mobile_hero_content_top'=> array( 'label' => 'Mobile hero content top padding', 'min' => 70, 'max' => 260, 'unit' => 'px' ),
			'mobile_hero_content_bottom' => array( 'label' => 'Mobile hero content bottom padding', 'min' => 70, 'max' => 260, 'unit' => 'px' ),
			'mobile_hero_title_size' => array( 'label' => 'Mobile homepage hero title size', 'min' => 36, 'max' => 80, 'unit' => 'px' ),
			'inner_hero_height'      => array( 'label' => 'Menu / Catering / page hero height', 'min' => 380, 'max' => 900, 'unit' => 'px' ),
			'mobile_inner_hero_height' => array( 'label' => 'Mobile inner page hero height', 'min' => 360, 'max' => 760, 'unit' => 'px' ),
			'inner_hero_bottom'      => array( 'label' => 'Inner hero content bottom padding', 'min' => 30, 'max' => 160, 'unit' => 'px' ),
		),
		'Cards, grids, buttons & forms' => array(
			'corner_radius'   => array( 'label' => 'Card and image corner radius', 'min' => 0, 'max' => 60, 'unit' => 'px' ),
			'grid_gap'        => array( 'label' => 'Card and column gap', 'min' => 4, 'max' => 70, 'unit' => 'px' ),
			'card_padding'    => array( 'label' => 'Card inner padding', 'min' => 12, 'max' => 80, 'unit' => 'px' ),
			'feature_padding' => array( 'label' => 'Large feature / callout padding', 'min' => 24, 'max' => 110, 'unit' => 'px' ),
			'button_height'   => array( 'label' => 'Button minimum height', 'min' => 38, 'max' => 80, 'unit' => 'px' ),
			'button_padding_x'=> array( 'label' => 'Button left / right padding', 'min' => 10, 'max' => 60, 'unit' => 'px' ),
			'button_radius'   => array( 'label' => 'Button corner radius', 'min' => 0, 'max' => 40, 'unit' => 'px' ),
			'form_padding'    => array( 'label' => 'Enquiry form inner padding', 'min' => 16, 'max' => 90, 'unit' => 'px' ),
			'mobile_form_padding' => array( 'label' => 'Mobile form inner padding', 'min' => 12, 'max' => 50, 'unit' => 'px' ),
			'gallery_gap'     => array( 'label' => 'Gallery image gap', 'min' => 0, 'max' => 50, 'unit' => 'px' ),
		),
		'Footer' => array(
			'footer_top'      => array( 'label' => 'Footer top padding', 'min' => 24, 'max' => 140, 'unit' => 'px' ),
			'footer_bottom'   => array( 'label' => 'Footer bottom padding', 'min' => 12, 'max' => 80, 'unit' => 'px' ),
			'footer_grid_gap' => array( 'label' => 'Footer column gap', 'min' => 12, 'max' => 100, 'unit' => 'px' ),
		),
	);
}

function feast_get_design_settings() {
	return wp_parse_args( (array) get_option( 'feast_design', array() ), feast_design_defaults() );
}

function feast_design( $key ) {
	$settings = feast_get_design_settings();
	return isset( $settings[ $key ] ) ? $settings[ $key ] : '';
}

function feast_sanitize_design_settings( $input ) {
	$defaults = feast_design_defaults();
	$output   = array();
	$numeric  = array();
	foreach ( feast_design_control_groups() as $controls ) {
		$numeric = array_merge( $numeric, $controls );
	}
	foreach ( $defaults as $key => $default ) {
		if ( isset( $numeric[ $key ] ) ) {
			$value = isset( $input[ $key ] ) ? absint( $input[ $key ] ) : absint( $default );
			$output[ $key ] = (string) min( $numeric[ $key ]['max'], max( $numeric[ $key ]['min'], $value ) );
		} else {
			$output[ $key ] = isset( $input[ $key ] ) && sanitize_hex_color( $input[ $key ] ) ? sanitize_hex_color( $input[ $key ] ) : $default;
		}
	}
	return $output;
}

function feast_design_css() {
	$design = feast_get_design_settings();
	$css = ':root{';
	foreach ( array( 'ink', 'forest', 'forest_light', 'cedar', 'red', 'cream', 'paper', 'sand', 'muted' ) as $key ) {
		$css .= '--' . str_replace( '_', '-', $key ) . ':' . $design[ $key ] . ';';
	}
	$css .= '--header-bg:' . $design['header_bg'] . ';--footer-bg:' . $design['footer_bg'] . ';--hero-text:' . $design['hero_text'] . ';--hero-accent:' . $design['hero_accent'] . ';--button-text:' . $design['button_text'] . ';';
	foreach ( feast_design_control_groups() as $controls ) {
		foreach ( $controls as $key => $control ) {
			$variable = '--' . str_replace( '_', '-', $key );
			if ( 'corner_radius' === $key ) {
				$variable = '--radius';
			} elseif ( 'hero_height_vh' === $key ) {
				$variable = '--hero-height';
			}
			$css .= $variable . ':' . absint( $design[ $key ] ) . $control['unit'] . ';';
		}
	}
	$css .= '}';
	return $css;
}

function feast_dynamic_design_css() {
	wp_add_inline_style( 'feast-style', feast_design_css() );
}
add_action( 'wp_enqueue_scripts', 'feast_dynamic_design_css', 20 );

function feast_design_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$settings = feast_get_design_settings();
	$colours = array(
		'ink' => 'Main text / dark sections', 'forest' => 'Primary brand colour', 'forest_light' => 'Primary hover colour', 'cedar' => 'Accent green', 'red' => 'Feature accent', 'cream' => 'Cream sections', 'paper' => 'Page background', 'sand' => 'Warm neutral', 'muted' => 'Secondary text', 'header_bg' => 'Header background', 'footer_bg' => 'Footer background', 'hero_text' => 'Hero text', 'hero_accent' => 'Hero script accent', 'button_text' => 'Primary button text',
	);
	?>
	<div class="wrap feast-admin-wrap"><h1>Design &amp; Branding</h1><p class="feast-admin-lead">Style the entire website without editing code. These settings apply to the homepage, Menu, Catering, Story, Gallery, Contact, navigation and footer. Update the logo under Appearance → Customize → Site Identity, and navigation under Appearance → Menus.</p><p><a class="button button-secondary" href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" rel="noopener">Open live website preview ↗</a></p>
	<form method="post" action="options.php"><?php settings_fields( 'feast_design_group' ); ?>
		<div class="feast-admin-card"><h2>Brand colours</h2><?php foreach ( $colours as $key => $label ) : ?><div class="feast-admin-field"><label for="design-<?php echo esc_attr( $key ); ?>"><strong><?php echo esc_html( $label ); ?></strong></label><input id="design-<?php echo esc_attr( $key ); ?>" type="color" name="feast_design[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $settings[ $key ] ); ?>"></div><?php endforeach; ?></div>
		<?php foreach ( feast_design_control_groups() as $group_title => $controls ) : ?><div class="feast-admin-card"><h2><?php echo esc_html( $group_title ); ?></h2>
			<?php foreach ( $controls as $key => $control ) : ?><div class="feast-admin-field feast-admin-field--number"><label for="design-<?php echo esc_attr( $key ); ?>"><strong><?php echo esc_html( $control['label'] ); ?></strong></label><div><input id="design-<?php echo esc_attr( $key ); ?>" type="number" min="<?php echo esc_attr( $control['min'] ); ?>" max="<?php echo esc_attr( $control['max'] ); ?>" name="feast_design[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $settings[ $key ] ); ?>"><span><?php echo esc_html( $control['unit'] ); ?></span></div></div><?php endforeach; ?>
		</div><?php endforeach; ?>
		<?php submit_button( 'Save design settings' ); ?>
	</form></div>
	<?php
}

function feast_copy_defaults() {
	return array(
		'brand_name'            => 'Feast in the Middle East',
		'brand_tagline'         => 'Traditional food made with love',
		'announcement_text'    => 'Catering for family gatherings, celebrations and workplace events',
		'announcement_mobile'  => 'Middle Eastern catering across Sydney',
		'header_cta_label'      => 'Get a catering quote',
		'header_cta_link'       => '/contact/',
		'call_label'            => 'Call',
		'catering_eyebrow'    => 'Catering made simple',
		'catering_title'      => 'Choose your kind of feast.',
		'catering_intro'      => 'Start with one of our popular catering styles, then we’ll tailor the dishes and quantities to your guests.',
		'reviews_eyebrow'     => 'Loved by our customers',
		'reviews_title'       => 'What people say about the feast.',
		'reviews_intro'       => 'Real Google reviews from customers who have shared our food around their tables.',
		'menu_eyebrow'        => 'From our kitchen',
		'menu_title'          => 'The dishes people come back for.',
		'menu_intro'          => 'Traditional Middle Eastern flavours, generous portions and plenty made for sharing.',
		'menu_category_mains' => 'Hearty mains',
		'menu_category_salads'=> 'Salads & sides',
		'menu_category_bites' => 'Bites & extras',
		'menu_page_eyebrow'   => 'Our menu',
		'menu_cta_eyebrow'    => 'Planning an event?',
		'menu_cta_title'      => 'Build a menu for your guests.',
		'menu_cta_label'      => 'Request a catering quote',
		'menu_cta_link'       => '/contact/',
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
		'story_stamp'         => "Made with\nlove in\nGranville",
		'story_cta_label'     => 'Read our story',
		'story_cta_link'      => '/our-story/',
		'story_page_cta_label'=> 'Plan a feast with us',
		'story_page_cta_link' => '/contact/',
		'values_eyebrow'      => 'What matters to us',
		'values_title'        => 'Traditional food. Generous hospitality.',
		'values_intro'        => 'Food made carefully, served generously and designed to bring people together.',
		'gallery_eyebrow'     => 'Recent feasts',
		'gallery_title'       => 'Made to be shared.',
		'gallery_cta_label'   => 'View the full gallery',
		'gallery_cta_link'    => '/gallery/',
		'gallery_follow_label'=> 'Follow us on Instagram',
		'faq_eyebrow'         => 'Good to know',
		'faq_title'           => 'Catering questions, answered.',
		'enquiry_eyebrow'     => 'Start your catering order',
		'enquiry_title'       => 'Let’s put a feast on the table.',
		'enquiry_intro'       => 'Send us the basics and we’ll get in touch to discuss the menu, quantities and a custom quote.',
		'form_success'        => 'Thanks! Your catering request has been sent. We’ll be in touch soon.',
		'form_error'          => 'We couldn’t send that yet. Check the required fields or call us.',
		'form_name_label'     => 'Your name *',
		'form_phone_label'    => 'Phone number *',
		'form_email_label'    => 'Email *',
		'form_date_label'     => 'Event date',
		'form_guests_label'   => 'Approximate guests',
		'form_guests_placeholder' => 'e.g. 40',
		'form_type_label'     => 'What are you planning?',
		'form_type_placeholder' => 'Choose one',
		'form_type_options'   => "Family gathering\nWedding or celebration\nOffice lunch\nCommunity event\nOther",
		'form_message_label'  => 'Tell us about your feast',
		'form_message_placeholder' => 'Your preferred dishes, venue or anything we should know...',
		'form_submit_label'   => 'Request my catering quote',
		'form_note'           => 'No payment is taken here. We’ll contact you to confirm the details and quote.',
		'contact_instagram_label' => 'Instagram',
		'footer_description'  => 'Generous Middle Eastern food for family tables, workplace lunches and the celebrations that matter.',
		'footer_contact_title'=> 'Visit & contact',
		'footer_explore_title'=> 'Explore',
		'footer_location'     => 'Granville, Sydney',
		'footer_bottom_text'  => 'Traditional food. Generous hospitality.',
		'mobile_cta_label'    => 'Get a catering quote',
		'mobile_cta_link'     => '/contact/',
		'notfound_eyebrow'    => 'Page not found',
		'notfound_title'      => 'This plate is empty.',
		'notfound_text'       => 'The page you were after has moved or never existed. Head back to the homepage to explore our menu and catering.',
		'notfound_cta_label'  => 'Back to the homepage',
	);
}

function feast_get_site_copy() {
	return wp_parse_args( (array) get_option( 'feast_site_copy', array() ), feast_copy_defaults() );
}

function feast_copy( $key ) {
	global $feast_homepage_copy_overrides;
	if ( is_array( $feast_homepage_copy_overrides ) && array_key_exists( $key, $feast_homepage_copy_overrides ) ) {
		return (string) $feast_homepage_copy_overrides[ $key ];
	}
	$copy = feast_get_site_copy();
	return isset( $copy[ $key ] ) ? $copy[ $key ] : '';
}

function feast_resolve_url( $url ) {
	if ( ! $url ) {
		return home_url( '/' );
	}
	if ( 0 === strpos( $url, '#' ) ) {
		return $url;
	}
	if ( 0 === strpos( $url, '/' ) ) {
		return home_url( $url );
	}
	return $url;
}

function feast_sanitize_site_copy( $input ) {
	$output = array();
	foreach ( feast_copy_defaults() as $key => $default ) {
		if ( ! isset( $input[ $key ] ) ) {
			$output[ $key ] = $default;
		} elseif ( false !== strpos( $key, '_link' ) && 0 !== strpos( $input[ $key ], '#' ) && 0 !== strpos( $input[ $key ], '/' ) ) {
			$output[ $key ] = esc_url_raw( $input[ $key ] );
		} else {
			$output[ $key ] = sanitize_textarea_field( $input[ $key ] );
		}
	}
	return $output;
}

function feast_copy_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$copy = feast_get_site_copy();
	$groups = array(
		'Brand & header'       => array( 'brand_name', 'brand_tagline', 'header_cta_label', 'header_cta_link', 'call_label' ),
		'Header announcement' => array( 'announcement_text', 'announcement_mobile' ),
		'Catering section'   => array( 'catering_eyebrow', 'catering_title', 'catering_intro' ),
		'Google reviews'     => array( 'reviews_eyebrow', 'reviews_title', 'reviews_intro' ),
		'Menu section'       => array( 'menu_eyebrow', 'menu_title', 'menu_intro', 'menu_category_mains', 'menu_category_salads', 'menu_category_bites', 'menu_page_eyebrow', 'menu_cta_eyebrow', 'menu_cta_title', 'menu_cta_label', 'menu_cta_link' ),
		'How it works'       => array( 'process_eyebrow', 'process_title', 'process_intro', 'step_1_title', 'step_1_text', 'step_2_title', 'step_2_text', 'step_3_title', 'step_3_text' ),
		'Our story section'  => array( 'story_eyebrow', 'story_title', 'story_lead', 'story_text', 'story_stamp', 'story_cta_label', 'story_cta_link', 'story_page_cta_label', 'story_page_cta_link', 'values_eyebrow', 'values_title', 'values_intro' ),
		'Gallery section'    => array( 'gallery_eyebrow', 'gallery_title', 'gallery_cta_label', 'gallery_cta_link', 'gallery_follow_label' ),
		'FAQ section'        => array( 'faq_eyebrow', 'faq_title' ),
		'Enquiry section'    => array( 'enquiry_eyebrow', 'enquiry_title', 'enquiry_intro' ),
		'Enquiry form'       => array( 'form_success', 'form_error', 'form_name_label', 'form_phone_label', 'form_email_label', 'form_date_label', 'form_guests_label', 'form_guests_placeholder', 'form_type_label', 'form_type_placeholder', 'form_type_options', 'form_message_label', 'form_message_placeholder', 'form_submit_label', 'form_note' ),
		'Not found page'     => array( 'notfound_eyebrow', 'notfound_title', 'notfound_text', 'notfound_cta_label' ),
		'Footer & mobile CTA'=> array( 'footer_description', 'footer_contact_title', 'footer_explore_title', 'footer_location', 'footer_bottom_text', 'contact_instagram_label', 'mobile_cta_label', 'mobile_cta_link' ),
	);
	?>
	<div class="wrap feast-admin-wrap"><h1>Website Copy</h1><p class="feast-admin-lead">Edit the headings and supporting text used across the homepage. Offers, packages, dishes and images are managed from their own Feast CMS menus.</p>
	<form method="post" action="options.php"><?php settings_fields( 'feast_copy_group' ); ?>
		<?php foreach ( $groups as $group_title => $keys ) : ?><div class="feast-admin-card"><h2><?php echo esc_html( $group_title ); ?></h2>
			<?php foreach ( $keys as $key ) : $is_long = (bool) preg_match( '/(_intro|_text|_lead|_description|_note|_options|_success|_error|_stamp)$/', $key ); ?><div class="feast-admin-field"><label for="<?php echo esc_attr( $key ); ?>"><strong><?php echo esc_html( ucwords( str_replace( '_', ' ', $key ) ) ); ?></strong></label><?php if ( ! $is_long ) : ?><input type="text" id="<?php echo esc_attr( $key ); ?>" name="feast_site_copy[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $copy[ $key ] ); ?>"><?php else : ?><textarea rows="3" id="<?php echo esc_attr( $key ); ?>" name="feast_site_copy[<?php echo esc_attr( $key ); ?>]"><?php echo esc_textarea( $copy[ $key ] ); ?></textarea><?php endif; ?></div><?php endforeach; ?>
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
			'_feast_page_cta_label' => array( 'label' => 'Page call-to-action text (optional)', 'type' => 'text', 'placeholder' => 'Request a catering quote' ),
			'_feast_page_cta_link'  => array( 'label' => 'Page call-to-action link (optional)', 'type' => 'text', 'placeholder' => '/contact/' ),
			'_feast_seo_title'       => array( 'label' => 'SEO title (optional)', 'type' => 'text', 'placeholder' => 'Keep this clear and specific, around 50–60 characters' ),
			'_feast_meta_description'=> array( 'label' => 'Meta description (optional)', 'type' => 'textarea', 'placeholder' => 'A useful summary for search results, around 140–160 characters' ),
			'_feast_social_image'    => array( 'label' => 'Social sharing image URL (optional)', 'type' => 'text', 'placeholder' => 'Leave blank to use the featured image' ),
			'_feast_canonical_url'   => array( 'label' => 'Canonical URL override (advanced)', 'type' => 'text', 'placeholder' => 'Leave blank for the normal page URL' ),
			'_feast_noindex'         => array( 'label' => 'Hide this page from search engines', 'type' => 'checkbox' ),
		),
		'post' => array(
			'_feast_seo_title'       => array( 'label' => 'SEO title (optional)', 'type' => 'text', 'placeholder' => 'Keep this clear and specific, around 50–60 characters' ),
			'_feast_meta_description'=> array( 'label' => 'Meta description (optional)', 'type' => 'textarea', 'placeholder' => 'A useful summary for search results, around 140–160 characters' ),
			'_feast_social_image'    => array( 'label' => 'Social sharing image URL (optional)', 'type' => 'text', 'placeholder' => 'Leave blank to use the featured image' ),
			'_feast_canonical_url'   => array( 'label' => 'Canonical URL override (advanced)', 'type' => 'text', 'placeholder' => 'Leave blank for the normal post URL' ),
			'_feast_noindex'         => array( 'label' => 'Hide this post from search engines', 'type' => 'checkbox' ),
		),
		'feast_offer' => array(
			'_feast_eyebrow'        => array( 'label' => 'Small heading', 'type' => 'text', 'placeholder' => 'Middle Eastern catering across Sydney' ),
			'_feast_primary_label'  => array( 'label' => 'Main button text', 'type' => 'text', 'placeholder' => 'Request a catering quote' ),
			'_feast_primary_link'   => array( 'label' => 'Main button link', 'type' => 'text', 'placeholder' => '#catering-enquiry' ),
			'_feast_second_label'   => array( 'label' => 'Second button text', 'type' => 'text', 'placeholder' => 'Explore catering' ),
			'_feast_second_link'    => array( 'label' => 'Second button link', 'type' => 'text', 'placeholder' => '#catering' ),
			'_feast_note'           => array( 'label' => 'Small note', 'type' => 'text', 'placeholder' => 'Freshly prepared in Granville' ),
			'_feast_overlay_color'  => array( 'label' => 'Overlay colour', 'type' => 'color', 'default' => '#071309' ),
			'_feast_overlay_opacity'=> array( 'label' => 'Overlay strength (0–95)', 'type' => 'number', 'placeholder' => '75', 'min' => 0, 'max' => 95 ),
			'_feast_text_color'     => array( 'label' => 'Hero text colour', 'type' => 'color', 'default' => '#fffdf8' ),
			'_feast_accent_color'   => array( 'label' => 'Script accent colour', 'type' => 'color', 'default' => '#edd7ad' ),
			'_feast_primary_color'  => array( 'label' => 'Main button background', 'type' => 'color', 'default' => '#ffffff' ),
			'_feast_primary_text'   => array( 'label' => 'Main button text colour', 'type' => 'color', 'default' => '#173f32' ),
			'_feast_second_color'   => array( 'label' => 'Second button background', 'type' => 'color', 'default' => '#173f32' ),
			'_feast_second_text'    => array( 'label' => 'Second button text colour', 'type' => 'color', 'default' => '#ffffff' ),
		),
		'feast_bundle' => array(
			'_feast_tag'            => array( 'label' => 'Package badge', 'type' => 'text', 'placeholder' => 'Most popular' ),
			'_feast_audience'       => array( 'label' => 'Best for', 'type' => 'text', 'placeholder' => 'Ideal for 25–100+ guests' ),
			'_feast_features'       => array( 'label' => 'Package inclusions', 'type' => 'textarea', 'placeholder' => "One inclusion per line\nFresh salads and traditional sides" ),
			'_feast_cta_label'      => array( 'label' => 'Link text', 'type' => 'text', 'placeholder' => 'Plan my celebration' ),
			'_feast_cta_link'       => array( 'label' => 'Link destination', 'type' => 'text', 'placeholder' => '/contact/' ),
			'_feast_price'          => array( 'label' => 'Package price or price note', 'type' => 'text', 'placeholder' => 'Custom quote' ),
			'_feast_featured'       => array( 'label' => 'Highlight this package', 'type' => 'checkbox' ),
		),
		'feast_dish' => array(
			'_feast_category'       => array( 'label' => 'Menu category', 'type' => 'select', 'options' => array( 'mains' => feast_copy( 'menu_category_mains' ), 'salads' => feast_copy( 'menu_category_salads' ), 'bites' => feast_copy( 'menu_category_bites' ) ) ),
			'_feast_price'          => array( 'label' => 'Price', 'type' => 'text', 'placeholder' => '$18 or From $18' ),
			'_feast_dietary'        => array( 'label' => 'Dietary label (optional)', 'type' => 'text', 'placeholder' => 'Vegetarian · Gluten free' ),
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
		} elseif ( 'color' === $field['type'] ) {
			$colour = $value ? $value : $field['default'];
			printf( '<label for="%1$s"><strong>%2$s</strong></label><input type="color" id="%1$s" name="%1$s" value="%3$s">', esc_attr( $key ), esc_html( $field['label'] ), esc_attr( $colour ) );
		} elseif ( 'number' === $field['type'] ) {
			printf( '<label for="%1$s"><strong>%2$s</strong></label><input type="number" id="%1$s" name="%1$s" value="%3$s" placeholder="%4$s" min="%5$s" max="%6$s">', esc_attr( $key ), esc_html( $field['label'] ), esc_attr( $value ), esc_attr( $field['placeholder'] ), esc_attr( $field['min'] ), esc_attr( $field['max'] ) );
		} else {
			printf( '<label for="%1$s"><strong>%2$s</strong></label><input type="text" id="%1$s" name="%1$s" value="%3$s" placeholder="%4$s">', esc_attr( $key ), esc_html( $field['label'] ), esc_attr( $value ), esc_attr( $field['placeholder'] ) );
		}
		echo '</div>';
	}
}

function feast_render_help_box( $post ) {
	$guides = array(
		'page'          => 'Edit the page title, introductory excerpt, main content and featured image. Dedicated Feast page layouts update automatically.',
		'post'          => 'Edit the post normally. SEO fields override the search title, description, social image and indexing only when needed.',
		'feast_offer'   => 'Add the large headline as the title, supporting sentence as the excerpt, and the background photo as the featured image.',
		'feast_bundle'  => 'Add the package name as the title, choose a strong event or food photo as the Package image, and put each inclusion on its own line. Use Order to control its position.',
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
		} elseif ( 'color' === $field['type'] ) {
			$value = sanitize_hex_color( $raw );
		} elseif ( 'number' === $field['type'] ) {
			$value = (string) min( (int) $field['max'], max( (int) $field['min'], absint( $raw ) ) );
		} elseif ( ( false !== strpos( $key, '_link' ) || false !== strpos( $key, '_url' ) || false !== strpos( $key, '_image' ) ) && 0 !== strpos( $raw, '#' ) && 0 !== strpos( $raw, '/' ) ) {
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
	if ( ! $screen || ( ! in_array( $screen->id, array( 'page', 'post' ), true ) && 0 !== strpos( $screen->id, 'feast_' ) && false === strpos( $screen->id, 'feast-cms' ) && false === strpos( $screen->id, 'feast-settings' ) ) ) {
		return;
	}
	wp_enqueue_style( 'feast-admin', get_template_directory_uri() . '/assets/css/admin.css', array(), wp_get_theme()->get( 'Version' ) );
}
add_action( 'admin_enqueue_scripts', 'feast_admin_assets' );
