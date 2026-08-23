<?php
/**
 * Plugin Name: Feast Core
 * Description: Feast CMS integrations, Elementor widgets, and editable global layout locations.
 * Version: 0.1.0
 * Author: Feast in the Middle East
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Text Domain: feast-core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'FEAST_CORE_VERSION', '0.1.0' );
define( 'FEAST_CORE_FILE', __FILE__ );
define( 'FEAST_CORE_DIR', plugin_dir_path( __FILE__ ) );
define( 'FEAST_CORE_URL', plugin_dir_url( __FILE__ ) );

/** Register the Feast widget group in Elementor. */
function feast_core_elementor_category( $elements_manager ) {
	$elements_manager->add_category(
		'feast',
		array(
			'title' => __( 'Feast in the Middle East', 'feast-core' ),
			'icon'  => 'fa fa-cutlery',
		)
	);
}
add_action( 'elementor/elements/categories_registered', 'feast_core_elementor_category' );

/** Load all code-owned widgets. Elementor remains optional. */
function feast_core_register_widgets( $widgets_manager ) {
	require_once FEAST_CORE_DIR . 'includes/class-feast-hero-widget.php';
	require_once FEAST_CORE_DIR . 'includes/class-feast-collection-widget.php';
	require_once FEAST_CORE_DIR . 'includes/class-feast-enquiry-widget.php';

	$widgets_manager->register( new \Feast_Core_Hero_Widget() );
	$widgets_manager->register( new \Feast_Core_Collection_Widget() );
	$widgets_manager->register( new \Feast_Core_Enquiry_Widget() );
}
add_action( 'elementor/widgets/register', 'feast_core_register_widgets' );

function feast_core_assets() {
	wp_enqueue_style( 'feast-core-elementor', FEAST_CORE_URL . 'assets/feast-core.css', array(), FEAST_CORE_VERSION );
}
add_action( 'wp_enqueue_scripts', 'feast_core_assets', 30 );

/** Let admins know when the visual editing dependency is missing. */
function feast_core_elementor_notice() {
	if ( did_action( 'elementor/loaded' ) || ! current_user_can( 'activate_plugins' ) ) {
		return;
	}
	echo '<div class="notice notice-warning"><p><strong>Feast Core:</strong> Install and activate Elementor Website Builder (free) to use the Feast visual widgets. The existing website will continue to work without it.</p></div>';
}
add_action( 'admin_notices', 'feast_core_elementor_notice' );

function feast_core_location_defaults() {
	return array( 'header' => 0, 'footer' => 0 );
}

function feast_core_locations() {
	return wp_parse_args( (array) get_option( 'feast_core_locations', array() ), feast_core_location_defaults() );
}

function feast_core_sanitize_locations( $input ) {
	return array(
		'header' => isset( $input['header'] ) ? absint( $input['header'] ) : 0,
		'footer' => isset( $input['footer'] ) ? absint( $input['footer'] ) : 0,
	);
}

function feast_core_register_settings() {
	register_setting( 'feast_core_layouts', 'feast_core_locations', 'feast_core_sanitize_locations' );
}
add_action( 'admin_init', 'feast_core_register_settings' );

function feast_core_layout_menu() {
	$parent = function_exists( 'feast_cms_menu' ) ? 'feast-cms' : 'options-general.php';
	add_submenu_page( $parent, 'Visual Layouts', 'Visual Layouts', 'edit_pages', 'feast-visual-layouts', 'feast_core_layout_page' );
}
add_action( 'admin_menu', 'feast_core_layout_menu', 30 );

function feast_core_layout_page() {
	if ( ! current_user_can( 'edit_pages' ) ) {
		return;
	}
	$locations = feast_core_locations();
	$pages     = get_pages( array( 'sort_column' => 'post_title' ) );
	?>
	<div class="wrap">
		<h1>Feast visual layouts</h1>
		<p>Create ordinary pages named <strong>Global Header</strong> and <strong>Global Footer</strong>, edit them with Elementor, then assign them here. Choosing “Use theme fallback” keeps the current coded design.</p>
		<form method="post" action="options.php">
			<?php settings_fields( 'feast_core_layouts' ); ?>
			<table class="form-table" role="presentation">
			<?php foreach ( array( 'header' => 'Global header', 'footer' => 'Global footer' ) as $key => $label ) : ?>
				<tr><th scope="row"><label for="feast-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></label></th><td>
					<select id="feast-<?php echo esc_attr( $key ); ?>" name="feast_core_locations[<?php echo esc_attr( $key ); ?>]">
						<option value="0">Use theme fallback</option>
						<?php foreach ( $pages as $page ) : ?><option value="<?php echo esc_attr( $page->ID ); ?>" <?php selected( $locations[ $key ], $page->ID ); ?>><?php echo esc_html( $page->post_title ); ?></option><?php endforeach; ?>
					</select>
					<?php if ( $locations[ $key ] && did_action( 'elementor/loaded' ) ) : ?><a class="button" href="<?php echo esc_url( admin_url( 'post.php?post=' . absint( $locations[ $key ] ) . '&action=elementor' ) ); ?>">Edit with Elementor</a><?php endif; ?>
				</td></tr>
			<?php endforeach; ?>
			</table>
			<?php submit_button( 'Save visual layouts' ); ?>
		</form>
	</div>
	<?php
}

/**
 * Render an assigned Elementor page as a theme location.
 *
 * @return bool True only when a valid Elementor layout was rendered.
 */
function feast_core_render_location( $location ) {
	if ( ! did_action( 'elementor/loaded' ) || ! class_exists( '\\Elementor\\Plugin' ) ) {
		return false;
	}
	$locations = feast_core_locations();
	$page_id   = isset( $locations[ $location ] ) ? absint( $locations[ $location ] ) : 0;
	if ( ! $page_id || 'publish' !== get_post_status( $page_id ) ) {
		return false;
	}
	$content = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $page_id, true );
	if ( ! trim( $content ) ) {
		return false;
	}
	printf( '<div class="feast-elementor-location feast-elementor-location--%1$s">%2$s</div>', esc_attr( $location ), $content ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	return true;
}

/** Reviews can be placed through Elementor's Shortcode widget or this alias. */
function feast_core_reviews_shortcode() {
	return do_shortcode( '[trustindex no-registration=google]' );
}
add_shortcode( 'feast_google_reviews', 'feast_core_reviews_shortcode' );

