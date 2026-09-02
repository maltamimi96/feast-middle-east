<?php
/**
 * One-time starter content for the editable Feast CMS.
 *
 * @package FeastMiddleEast
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function feast_import_theme_image( $filename, $title ) {
	$existing = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'posts_per_page' => 1,
			'meta_key'       => '_feast_theme_asset',
			'meta_value'     => $filename,
			'fields'         => 'ids',
		)
	);
	if ( ! empty( $existing ) ) {
		return (int) $existing[0];
	}

	$source = get_template_directory() . '/assets/images/' . $filename;
	if ( ! file_exists( $source ) || ! is_readable( $source ) ) {
		return 0;
	}
	$contents = file_get_contents( $source );
	if ( false === $contents ) {
		return 0;
	}
	// Asset keys may include a theme subdirectory, but Media Library filenames may not.
	$upload = wp_upload_bits( basename( $filename ), null, $contents );
	if ( ! empty( $upload['error'] ) ) {
		return 0;
	}
	$filetype = wp_check_filetype( $upload['file'] );
	$attachment_id = wp_insert_attachment(
		array(
			'post_mime_type' => $filetype['type'],
			'post_title'     => $title,
			'post_status'    => 'inherit',
		),
		$upload['file']
	);
	if ( is_wp_error( $attachment_id ) ) {
		return 0;
	}
	require_once ABSPATH . 'wp-admin/includes/image.php';
	$metadata = wp_generate_attachment_metadata( $attachment_id, $upload['file'] );
	wp_update_attachment_metadata( $attachment_id, $metadata );
	update_post_meta( $attachment_id, '_feast_theme_asset', $filename );
	return (int) $attachment_id;
}

/**
 * Return every bundled gallery image in a predictable order.
 */
function feast_bundled_gallery_images() {
	$directory = get_template_directory() . '/assets/images/gallery';
	$images    = glob( $directory . '/*.{jpg,jpeg,png,webp,avif}', GLOB_BRACE );

	if ( false === $images ) {
		return array();
	}

	natcasesort( $images );
	return array_values( $images );
}

/**
 * Gradually import bundled gallery photos into the editable Gallery CMS.
 *
 * Keeping the batch deliberately small prevents shared hosting from timing out
 * while WordPress creates its responsive image sizes. The sync is idempotent and
 * continues on subsequent requests until every bundled image is editable.
 */
function feast_sync_bundled_gallery() {
	if ( get_option( 'feast_gallery_assets_v1' ) ) {
		return;
	}
	if ( ! add_option( 'feast_gallery_sync_lock', time(), '', false ) ) {
		$lock_time = (int) get_option( 'feast_gallery_sync_lock' );
		if ( $lock_time && ( time() - $lock_time ) < 300 ) {
			return;
		}
		delete_option( 'feast_gallery_sync_lock' );
		if ( ! add_option( 'feast_gallery_sync_lock', time(), '', false ) ) {
			return;
		}
	}

	$images = feast_bundled_gallery_images();
	if ( empty( $images ) ) {
		delete_option( 'feast_gallery_sync_lock' );
		return;
	}

	$gallery_posts = get_posts(
		array(
			'post_type'      => 'feast_gallery',
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'fields'         => 'ids',
		)
	);
	$imported       = array();
	foreach ( $gallery_posts as $gallery_post_id ) {
		$asset_key = get_post_meta( $gallery_post_id, '_feast_gallery_asset', true );
		if ( $asset_key ) {
			$imported[ $asset_key ] = true;
		}
	}

	$missing = array();
	foreach ( $images as $index => $image_path ) {
		$asset_key = 'gallery/' . basename( $image_path );
		if ( empty( $imported[ $asset_key ] ) ) {
			$missing[ $index ] = $asset_key;
		}
	}

	if ( empty( $missing ) ) {
		update_option( 'feast_gallery_assets_v1', '1', false );
		delete_option( 'feast_gallery_sync_lock' );
		return;
	}

	$created = 0;
	foreach ( $missing as $index => $asset_key ) {
		if ( $created >= 4 ) {
			break;
		}

		$title         = sprintf( 'Middle Eastern catering gallery photo %02d', $index + 1 );
		$attachment_id = feast_import_theme_image( $asset_key, $title );
		if ( ! $attachment_id ) {
			continue;
		}

		$post_id = wp_insert_post(
			array(
				'post_type'   => 'feast_gallery',
				'post_status' => 'publish',
				'post_title'  => $title,
				'menu_order'  => 100 + $index,
			)
		);
		if ( ! $post_id || is_wp_error( $post_id ) ) {
			continue;
		}

		update_post_meta( $post_id, '_feast_gallery_asset', $asset_key );
		update_post_meta( $attachment_id, '_wp_attachment_image_alt', $title );
		set_post_thumbnail( $post_id, $attachment_id );
		$created++;
	}

	if ( $created === count( $missing ) ) {
		update_option( 'feast_gallery_assets_v1', '1', false );
	}
	delete_option( 'feast_gallery_sync_lock' );
}
add_action( 'init', 'feast_sync_bundled_gallery', 40 );

/**
 * Remove duplicate CMS rows left by overlapping requests during the first sync.
 * Attachments are retained in the Media Library and posts are moved to Trash.
 */
function feast_cleanup_duplicate_gallery_assets() {
	if ( get_option( 'feast_gallery_deduped_v1' ) ) {
		return;
	}

	$gallery_posts = get_posts(
		array(
			'post_type'      => 'feast_gallery',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'ID',
			'order'          => 'ASC',
			'fields'         => 'ids',
		)
	);
	$seen = array();
	foreach ( $gallery_posts as $gallery_post_id ) {
		$asset_key = get_post_meta( $gallery_post_id, '_feast_gallery_asset', true );
		if ( ! $asset_key ) {
			continue;
		}
		if ( isset( $seen[ $asset_key ] ) ) {
			wp_trash_post( $gallery_post_id );
			continue;
		}
		$seen[ $asset_key ] = true;
	}

	update_option( 'feast_gallery_deduped_v1', '1', false );
}
add_action( 'init', 'feast_cleanup_duplicate_gallery_assets', 45 );

/**
 * Add the original hero, menu and story photographs to the Gallery CMS too.
 * These files already exist in the Media Library, so no duplicate upload is made.
 */
function feast_sync_original_photos_to_gallery() {
	// add_option() is atomic, so only one overlapping request can perform this sync.
	if ( ! add_option( 'feast_original_gallery_assets_v1', 'running', '', false ) ) {
		return;
	}

	$originals = array(
		'catering-selection.jpg'  => 'A generous Middle Eastern catering selection',
		'event-salads.jpg'        => 'Fresh salads prepared for an event',
		'hero-catering-spread.jpg' => 'A generous catering spread',
		'hero-chicken-mansaf.jpg' => 'Traditional chicken mansaf',
		'hero-event-table.jpg'    => 'A catered celebration table',
		'menu-fattoush.jpg'       => 'Freshly prepared fattoush',
		'menu-malfouf.jpg'        => 'Traditional stuffed malfouf',
		'menu-warak-enab.jpg'     => 'Hand-rolled warak enab',
		'menu-wrap.jpg'           => 'Fresh Middle Eastern wraps',
		'owner-kitchen.jpg'       => 'Preparing food in the Feast kitchen',
	);

	$gallery_posts = get_posts(
		array(
			'post_type'      => 'feast_gallery',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'fields'         => 'ids',
		)
	);
	$imported = array();
	foreach ( $gallery_posts as $gallery_post_id ) {
		$asset_key = get_post_meta( $gallery_post_id, '_feast_gallery_asset', true );
		if ( ! $asset_key ) {
			$thumbnail_id = get_post_thumbnail_id( $gallery_post_id );
			$asset_key    = $thumbnail_id ? get_post_meta( $thumbnail_id, '_feast_theme_asset', true ) : '';
			if ( $asset_key ) {
				update_post_meta( $gallery_post_id, '_feast_gallery_asset', $asset_key );
			}
		}
		if ( $asset_key ) {
			$imported[ $asset_key ] = true;
		}
	}

	$order = 10;
	foreach ( $originals as $asset_key => $title ) {
		if ( ! empty( $imported[ $asset_key ] ) ) {
			continue;
		}
		$attachment_id = feast_import_theme_image( $asset_key, $title );
		if ( ! $attachment_id ) {
			continue;
		}
		$post_id = wp_insert_post(
			array(
				'post_type'   => 'feast_gallery',
				'post_status' => 'publish',
				'post_title'  => $title,
				'menu_order'  => $order++,
			)
		);
		if ( $post_id && ! is_wp_error( $post_id ) ) {
			update_post_meta( $post_id, '_feast_gallery_asset', $asset_key );
			update_post_meta( $attachment_id, '_wp_attachment_image_alt', $title );
			set_post_thumbnail( $post_id, $attachment_id );
		}
	}

	update_option( 'feast_original_gallery_assets_v1', '1', false );
}
add_action( 'init', 'feast_sync_original_photos_to_gallery', 47 );

/**
 * Complete the starter menu when an early partial import created only one dish.
 * Existing dishes are preserved and only missing titles are added.
 */
function feast_complete_starter_menu() {
	if ( ! add_option( 'feast_completed_starter_menu_v1', 'running', '', false ) ) {
		return;
	}

	$dishes = array(
		array( 'title' => 'Chicken Mansaf', 'excerpt' => 'Tender spiced chicken served over fragrant rice with a creamy jameed yoghurt sauce and toasted nuts.', 'image' => 'hero-chicken-mansaf.jpg', 'meta' => array( '_feast_category' => 'mains', '_feast_showcase' => '0' ) ),
		array( 'title' => 'Malfouf', 'excerpt' => 'Cabbage leaves filled with seasoned rice and meat, rolled by hand and slow-cooked with garlic and lemon.', 'image' => 'menu-malfouf.jpg', 'meta' => array( '_feast_category' => 'mains', '_feast_showcase' => '1' ) ),
		array( 'title' => 'Dawood Basha', 'excerpt' => 'Tender Middle Eastern meatballs simmered in a rich tomato and onion sauce, served as a comforting homestyle main.', 'meta' => array( '_feast_category' => 'mains', '_feast_showcase' => '0' ) ),
		array( 'title' => 'Fattoush', 'excerpt' => 'A crisp salad of lettuce, tomato, cucumber, radish and herbs, tossed with toasted pita and a tangy sumac dressing.', 'image' => 'menu-fattoush.jpg', 'meta' => array( '_feast_category' => 'salads', '_feast_showcase' => '1' ) ),
		array( 'title' => 'Tabouli', 'excerpt' => 'Finely chopped parsley, tomato, mint and bulgur dressed with fresh lemon juice and olive oil.', 'meta' => array( '_feast_category' => 'salads', '_feast_showcase' => '0' ) ),
		array( 'title' => 'Hummus', 'excerpt' => 'A silky blend of chickpeas, tahini, lemon and garlic, finished with a drizzle of olive oil.', 'meta' => array( '_feast_category' => 'salads', '_feast_showcase' => '0' ) ),
		array( 'title' => 'Batata Harra', 'excerpt' => 'Crispy potato cubes tossed with garlic, coriander, chilli and lemon for a bright, spicy finish.', 'meta' => array( '_feast_category' => 'salads', '_feast_showcase' => '0' ) ),
		array( 'title' => 'Warak Enab', 'excerpt' => 'Tender vine leaves rolled by hand around a fragrant rice and herb filling, then gently cooked with lemon.', 'image' => 'menu-warak-enab.jpg', 'meta' => array( '_feast_category' => 'bites', '_feast_showcase' => '1' ) ),
		array( 'title' => 'Kibbeh', 'excerpt' => 'Golden bulgur shells filled with seasoned minced meat, onion and aromatic Middle Eastern spices.', 'meta' => array( '_feast_category' => 'bites', '_feast_showcase' => '0' ) ),
		array( 'title' => 'Sambousek', 'excerpt' => 'Crisp, golden pastry parcels filled with savoury seasoned meat and fragrant spices.', 'meta' => array( '_feast_category' => 'bites', '_feast_showcase' => '0' ) ),
		array( 'title' => 'Fresh Wraps', 'excerpt' => 'Soft flatbread packed with freshly prepared fillings, crisp salad and flavourful house sauces.', 'image' => 'menu-wrap.jpg', 'meta' => array( '_feast_category' => 'bites', '_feast_showcase' => '0' ) ),
	);

	$existing_posts = get_posts(
		array(
			'post_type'      => 'feast_dish',
			'post_status'    => 'any',
			'posts_per_page' => -1,
		)
	);
	$existing_titles = array();
	foreach ( $existing_posts as $existing_post ) {
		$existing_titles[ strtolower( trim( $existing_post->post_title ) ) ] = true;
	}

	foreach ( $dishes as $order => $dish ) {
		if ( isset( $existing_titles[ strtolower( $dish['title'] ) ] ) ) {
			continue;
		}
		$post_id = wp_insert_post(
			array(
				'post_type'    => 'feast_dish',
				'post_status'  => 'publish',
				'post_title'   => $dish['title'],
				'post_excerpt' => $dish['excerpt'],
				'menu_order'   => $order,
			)
		);
		if ( ! $post_id || is_wp_error( $post_id ) ) {
			continue;
		}
		foreach ( $dish['meta'] as $key => $value ) {
			update_post_meta( $post_id, $key, $value );
		}
		if ( ! empty( $dish['image'] ) ) {
			$image_id = feast_import_theme_image( $dish['image'], $dish['title'] );
			if ( $image_id ) {
				set_post_thumbnail( $post_id, $image_id );
			}
		}
	}

	update_option( 'feast_completed_starter_menu_v1', '1', false );
}
add_action( 'init', 'feast_complete_starter_menu', 50 );

/**
 * Replace missing or generic menu copy with a description specific to each dish.
 *
 * This one-time migration also updates sites where the starter menu has already
 * been imported into WordPress.
 */
function feast_update_starter_menu_descriptions() {
	if ( ! add_option( 'feast_updated_menu_descriptions_v3', 'running', '', false ) ) {
		return;
	}

	$descriptions = array(
		'lamb kabsah'                => 'Slow-cooked lamb served over fragrant spiced rice with toasted nuts and a rich blend of warming Middle Eastern flavours.',
		'kousa bi banadoora'         => 'Tender zucchini filled with seasoned rice and meat, then gently simmered in a bright, savoury tomato sauce.',
		'mloukhiyeh'                 => 'A comforting jute mallow stew cooked with garlic, coriander and lemon, traditionally served with fragrant rice.',
		'chicken mansaf'             => 'Tender spiced chicken served over fragrant rice with a creamy jameed yoghurt sauce and toasted nuts.',
		'shish barak'                => 'Delicate meat-filled dumplings simmered in a warm, garlicky yoghurt sauce and finished with fresh coriander.',
		'fasolia'                    => 'A slow-cooked homestyle bean stew in a rich tomato sauce, seasoned with garlic and aromatic spices.',
		'lubiyah and lahme'          => 'Tender green beans and slow-cooked meat simmered together in a savoury tomato, garlic and spice sauce.',
		'wara anib zaat'             => 'Vine leaves rolled around a fragrant rice, tomato and herb filling, gently cooked with lemon and olive oil.',
		'eggplant with diced tomato' => 'Tender eggplant cooked with diced tomato, garlic and herbs for a rich, savoury vegetable dish.',
		'sheikh al mahshi'           => 'Tender eggplant filled with seasoned meat and aromatic spices, then simmered in a rich tomato sauce.',
		'malfouf'                    => 'Cabbage leaves filled with seasoned rice and meat, rolled by hand and slow-cooked with garlic and lemon.',
		'kousa b laban'              => 'Tender stuffed zucchini simmered in a creamy, garlicky yoghurt sauce and served as a comforting homestyle favourite.',
		'kafta and batata'           => 'Seasoned kafta and sliced potatoes baked in a rich tomato sauce with onion and warming Middle Eastern spices.',
		'dawood basha'               => 'Tender Middle Eastern meatballs simmered in a rich tomato and onion sauce, served as a comforting homestyle main.',
		'fattoush'                   => 'A crisp salad of lettuce, tomato, cucumber, radish and herbs, tossed with toasted pita and a tangy sumac dressing.',
		'tabouli'                    => 'Finely chopped parsley, tomato, mint and bulgur dressed with fresh lemon juice and olive oil.',
		'hummus'                     => 'A silky blend of chickpeas, tahini, lemon and garlic, finished with a drizzle of olive oil.',
		'batata harra'               => 'Crispy potato cubes tossed with garlic, coriander, chilli and lemon for a bright, spicy finish.',
		'warak enab'                 => 'Tender vine leaves rolled by hand around a fragrant rice and herb filling, then gently cooked with lemon.',
		'kibbeh'                     => 'Golden bulgur shells filled with seasoned minced meat, onion and aromatic Middle Eastern spices.',
		'sambousek'                  => 'Crisp, golden pastry parcels filled with savoury seasoned meat and fragrant spices.',
		'fresh wraps'                => 'Soft flatbread packed with freshly prepared fillings, crisp salad and flavourful house sauces.',
	);

	$dishes = get_posts(
		array(
			'post_type'      => 'feast_dish',
			'post_status'    => 'any',
			'posts_per_page' => -1,
		)
	);

	foreach ( $dishes as $dish ) {
		$title = strtolower( trim( $dish->post_title ) );
		if ( ! isset( $descriptions[ $title ] ) ) {
			continue;
		}

		wp_update_post(
			array(
				'ID'           => $dish->ID,
				'post_excerpt' => $descriptions[ $title ],
			)
		);
	}

	update_option( 'feast_updated_menu_descriptions_v3', '1', false );
}
add_action( 'init', 'feast_update_starter_menu_descriptions', 51 );

/** Update existing catering packages with clearer guest ranges. */
function feast_update_package_guest_ranges() {
	if ( ! add_option( 'feast_updated_package_guest_ranges_v1', 'running', '', false ) ) {
		return;
	}

	$ranges = array(
		'family table'      => 'Ideal for 10–20 guests',
		'celebration feast' => 'Ideal for 20–50 guests',
		'office lunch'      => 'Ideal for 10–50 guests',
	);
	$packages = get_posts(
		array(
			'post_type'      => 'feast_bundle',
			'post_status'    => 'any',
			'posts_per_page' => -1,
		)
	);

	foreach ( $packages as $package ) {
		$title = strtolower( trim( $package->post_title ) );
		if ( isset( $ranges[ $title ] ) ) {
			update_post_meta( $package->ID, '_feast_audience', $ranges[ $title ] );
		}
	}

	update_option( 'feast_updated_package_guest_ranges_v1', '1', false );
}
add_action( 'init', 'feast_update_package_guest_ranges', 52 );

/**
 * Give the starter catering packages relevant photography without replacing
 * images a site editor has already selected.
 */
function feast_add_starter_package_photography() {
	if ( ! add_option( 'feast_package_photography_v1', 'running', '', false ) ) {
		return;
	}

	$package_images = array(
		'family table'      => 'hero-chicken-mansaf.jpg',
		'celebration feast' => 'hero-event-table.jpg',
		'office lunch'      => 'menu-wrap.jpg',
	);
	$packages = get_posts(
		array(
			'post_type'      => 'feast_bundle',
			'post_status'    => 'any',
			'posts_per_page' => -1,
		)
	);
	foreach ( $packages as $package ) {
		$title_key = strtolower( trim( $package->post_title ) );
		if ( has_post_thumbnail( $package->ID ) || empty( $package_images[ $title_key ] ) ) {
			continue;
		}
		$image_id = feast_import_theme_image( $package_images[ $title_key ], $package->post_title . ' catering' );
		if ( $image_id ) {
			set_post_thumbnail( $package->ID, $image_id );
		}
	}

	update_option( 'feast_package_photography_v1', '1', false );
}
add_action( 'init', 'feast_add_starter_package_photography', 52 );

function feast_seed_post_type( $post_type, $items ) {
	$existing = get_posts( array( 'post_type' => $post_type, 'post_status' => 'any', 'posts_per_page' => 1, 'fields' => 'ids' ) );
	if ( ! empty( $existing ) ) {
		return;
	}
	foreach ( $items as $order => $item ) {
		$post_id = wp_insert_post(
			array(
				'post_type'    => $post_type,
				'post_status'  => 'publish',
				'post_title'   => $item['title'],
				'post_excerpt' => isset( $item['excerpt'] ) ? $item['excerpt'] : '',
				'post_content' => isset( $item['content'] ) ? $item['content'] : '',
				'menu_order'   => $order,
			)
		);
		if ( ! $post_id || is_wp_error( $post_id ) ) {
			continue;
		}
		if ( ! empty( $item['meta'] ) ) {
			foreach ( $item['meta'] as $key => $value ) {
				update_post_meta( $post_id, $key, $value );
			}
		}
		if ( ! empty( $item['image'] ) ) {
			$image_id = feast_import_theme_image( $item['image'], $item['title'] );
			if ( $image_id ) {
				set_post_thumbnail( $post_id, $image_id );
			}
		}
	}
}

function feast_seed_page( $slug, $title, $excerpt, $content, $image = '' ) {
	$page = get_page_by_path( $slug, OBJECT, 'page' );
	if ( $page ) {
		return $page->ID;
	}
	$page_id = wp_insert_post(
		array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_name'    => $slug,
			'post_title'   => $title,
			'post_excerpt' => $excerpt,
			'post_content' => $content,
		)
	);
	if ( $page_id && ! is_wp_error( $page_id ) && $image ) {
		$image_id = feast_import_theme_image( $image, $title );
		if ( $image_id ) {
			set_post_thumbnail( $page_id, $image_id );
		}
	}
	return $page_id;
}

function feast_seed_initial_content() {
	if ( ! is_admin() || ! current_user_can( 'manage_options' ) || get_option( 'feast_seeded_content_v2' ) ) {
		return;
	}

	feast_seed_post_type(
		'feast_offer',
		array(
			array( 'title' => 'A feast worth gathering for.', 'excerpt' => 'Generous, traditional food for weddings, work lunches, family celebrations and everything in between.', 'image' => 'hero-catering-spread.jpg', 'meta' => array( '_feast_eyebrow' => 'Middle Eastern catering across Sydney', '_feast_primary_label' => 'Request a catering quote', '_feast_primary_link' => '/contact/', '_feast_second_label' => 'Explore catering', '_feast_second_link' => '/catering/', '_feast_note' => 'Freshly prepared in Granville' ) ),
			array( 'title' => 'Big tables. Full plates. Happy people.', 'excerpt' => 'Build a share-style feast with hot mains, fresh salads, sides and sweets, made to suit your gathering.', 'image' => 'hero-chicken-mansaf.jpg', 'meta' => array( '_feast_eyebrow' => 'The family feast', '_feast_primary_label' => 'Build your feast', '_feast_primary_link' => '/contact/', '_feast_second_label' => 'See menu favourites', '_feast_second_link' => '/menu/', '_feast_note' => 'Custom menus for 10–100+ guests' ) ),
			array( 'title' => 'Your next event, beautifully fed.', 'excerpt' => 'From team lunches to milestone celebrations, tell us your guest count and we’ll help plan the food.', 'image' => 'hero-event-table.jpg', 'meta' => array( '_feast_eyebrow' => 'Office & event catering', '_feast_primary_label' => 'Cater my event', '_feast_primary_link' => '/contact/', '_feast_second_label' => 'Explore packages', '_feast_second_link' => '/catering/', '_feast_note' => 'Pickup and delivery options available' ) ),
		)
	);

	feast_seed_post_type(
		'feast_bundle',
		array(
			array( 'title' => 'Family Table', 'meta' => array( '_feast_tag' => 'Warm & generous', '_feast_audience' => 'Ideal for 10–20 guests', '_feast_features' => "Your choice of hearty main dishes\nFresh salads and traditional sides\nShare-style trays, ready for the table\nCustom quote based on your menu", '_feast_cta_label' => 'Ask about this feast', '_feast_featured' => '0' ) ),
			array( 'title' => 'Celebration Feast', 'meta' => array( '_feast_tag' => 'Most popular', '_feast_audience' => 'Ideal for 20–50 guests', '_feast_features' => "A generous mix of mains and favourites\nSalads, dips, sides and finger food\nDesigned for weddings and big occasions\nCustom quote built for your guest count", '_feast_cta_label' => 'Plan my celebration', '_feast_featured' => '1' ) ),
			array( 'title' => 'Office Lunch', 'meta' => array( '_feast_tag' => 'Easy crowd-pleaser', '_feast_audience' => 'Ideal for 10–50 guests', '_feast_features' => "Easy-to-serve hot mains or wraps\nFresh salads, dips and sides\nFlexible options for team preferences\nCustom quote for pickup or delivery", '_feast_cta_label' => 'Feed the team', '_feast_featured' => '0' ) ),
		)
	);

	feast_seed_post_type(
		'feast_dish',
		array(
			array( 'title' => 'Chicken Mansaf', 'excerpt' => 'Tender spiced chicken served over fragrant rice with a creamy jameed yoghurt sauce and toasted nuts.', 'image' => 'hero-chicken-mansaf.jpg', 'meta' => array( '_feast_category' => 'mains', '_feast_showcase' => '0' ) ),
			array( 'title' => 'Malfouf', 'excerpt' => 'Cabbage leaves filled with seasoned rice and meat, rolled by hand and slow-cooked with garlic and lemon.', 'image' => 'menu-malfouf.jpg', 'meta' => array( '_feast_category' => 'mains', '_feast_showcase' => '1' ) ),
			array( 'title' => 'Dawood Basha', 'excerpt' => 'Tender Middle Eastern meatballs simmered in a rich tomato and onion sauce, served as a comforting homestyle main.', 'meta' => array( '_feast_category' => 'mains', '_feast_showcase' => '0' ) ),
			array( 'title' => 'Fattoush', 'excerpt' => 'A crisp salad of lettuce, tomato, cucumber, radish and herbs, tossed with toasted pita and a tangy sumac dressing.', 'image' => 'menu-fattoush.jpg', 'meta' => array( '_feast_category' => 'salads', '_feast_showcase' => '1' ) ),
			array( 'title' => 'Tabouli', 'excerpt' => 'Finely chopped parsley, tomato, mint and bulgur dressed with fresh lemon juice and olive oil.', 'meta' => array( '_feast_category' => 'salads', '_feast_showcase' => '0' ) ),
			array( 'title' => 'Hummus', 'excerpt' => 'A silky blend of chickpeas, tahini, lemon and garlic, finished with a drizzle of olive oil.', 'meta' => array( '_feast_category' => 'salads', '_feast_showcase' => '0' ) ),
			array( 'title' => 'Batata Harra', 'excerpt' => 'Crispy potato cubes tossed with garlic, coriander, chilli and lemon for a bright, spicy finish.', 'meta' => array( '_feast_category' => 'salads', '_feast_showcase' => '0' ) ),
			array( 'title' => 'Warak Enab', 'excerpt' => 'Tender vine leaves rolled by hand around a fragrant rice and herb filling, then gently cooked with lemon.', 'image' => 'menu-warak-enab.jpg', 'meta' => array( '_feast_category' => 'bites', '_feast_showcase' => '1' ) ),
			array( 'title' => 'Kibbeh', 'excerpt' => 'Golden bulgur shells filled with seasoned minced meat, onion and aromatic Middle Eastern spices.', 'meta' => array( '_feast_category' => 'bites', '_feast_showcase' => '0' ) ),
			array( 'title' => 'Sambousek', 'excerpt' => 'Crisp, golden pastry parcels filled with savoury seasoned meat and fragrant spices.', 'meta' => array( '_feast_category' => 'bites', '_feast_showcase' => '0' ) ),
			array( 'title' => 'Fresh Wraps', 'excerpt' => 'Soft flatbread packed with freshly prepared fillings, crisp salad and flavourful house sauces.', 'image' => 'menu-wrap.jpg', 'meta' => array( '_feast_category' => 'bites', '_feast_showcase' => '0' ) ),
		)
	);

	feast_seed_post_type(
		'feast_gallery',
		array(
			array( 'title' => 'A generous catering selection', 'image' => 'catering-selection.jpg' ),
			array( 'title' => 'Fresh Middle Eastern wraps', 'image' => 'menu-wrap.jpg' ),
			array( 'title' => 'Colourful event salads', 'image' => 'event-salads.jpg' ),
			array( 'title' => 'A catered celebration table', 'image' => 'hero-event-table.jpg' ),
		)
	);

	feast_seed_post_type(
		'feast_faq',
		array(
			array( 'title' => 'How far ahead should I order?', 'content' => 'Contact us as early as you can, especially for large celebrations. We’ll confirm availability for your date when preparing the quote.' ),
			array( 'title' => 'Can you help choose quantities?', 'content' => 'Yes. Tell us your guest count and event style and we’ll recommend a practical mix of dishes and quantities.' ),
			array( 'title' => 'Do you offer pickup and delivery?', 'content' => 'Pickup is available from Granville. Ask us about delivery availability and cost for your event location.' ),
			array( 'title' => 'Can the menu be customised?', 'content' => 'Yes. Catering packages are a starting point and can be adjusted around your event and preferred dishes.' ),
		)
	);

	feast_seed_page( 'catering', 'Catering for Every Occasion', 'Generous Middle Eastern catering, planned around your guests and your event.', '<p>From family tables and workplace lunches to weddings and milestone celebrations, we prepare generous traditional food designed to be shared.</p>' );
	feast_seed_page( 'menu', 'Our Menu', 'Explore the dishes, salads, sides and bites that make a Feast table.', '<p>Our menu celebrates traditional Middle Eastern flavours, freshly prepared in Granville and served with generosity.</p>' );
	feast_seed_page( 'our-story', 'Our Story', 'Traditional food made with love, from our Granville kitchen to your table.', '<p>Feast in the Middle East is built around the food we love to cook and share—generous, traditional dishes that bring people together.</p><p>Whether you’re feeding the family or celebrating with a room full of people, every order receives the same care.</p>', 'owner-kitchen.jpg' );
	feast_seed_page( 'gallery', 'Gallery', 'A look at recent dishes, catering tables and celebrations.', '<p>Every feast is prepared to look as generous as it tastes.</p>' );
	feast_seed_page( 'contact', 'Plan Your Feast', 'Tell us about your event and we’ll help build the right menu.', '<p>Share your date, guest count and the kind of event you’re planning. We’ll contact you to discuss dishes, quantities and a custom quote.</p>' );

	update_option( 'feast_seeded_content_v2', '1', false );
	set_transient( 'feast_seed_notice', '1', 120 );
}
add_action( 'admin_init', 'feast_seed_initial_content', 30 );

function feast_seed_admin_notice() {
	if ( ! current_user_can( 'manage_options' ) || ! get_transient( 'feast_seed_notice' ) ) {
		return;
	}
	delete_transient( 'feast_seed_notice' );
	echo '<div class="notice notice-success is-dismissible"><p><strong>Feast CMS is ready.</strong> The current offers, packages, dishes, gallery and website pages are now editable from WordPress.</p></div>';
}
add_action( 'admin_notices', 'feast_seed_admin_notice' );
