<?php
/**
 * Native metadata and structured data for Feast in the Middle East.
 *
 * @package FeastMiddleEast
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function feast_seo_defaults() {
	return array(
		'home_title'          => 'Middle Eastern Catering Sydney | Feast in the Middle East',
		'home_description'    => 'Traditional Middle Eastern catering in Granville for family gatherings, weddings, workplace lunches and Sydney events. Request a custom quote.',
		'default_social_image'=> '',
		'business_description'=> 'Traditional Middle Eastern food and catering prepared in Granville for gatherings, celebrations and workplace events across Sydney.',
		'public_email'        => '',
		'street_address'      => '43 South St',
		'address_locality'    => 'Granville',
		'address_region'      => 'NSW',
		'postal_code'         => '2142',
		'address_country'     => 'AU',
		'latitude'            => '',
		'longitude'           => '',
		'service_area'        => 'Sydney, New South Wales',
		'cuisines'            => 'Middle Eastern, Lebanese',
		'price_range'         => '$$',
		'currency'            => 'AUD',
		'opening_hours'       => '',
		'google_business_url' => '',
		'facebook_url'        => '',
		'tiktok_url'          => '',
		'google_verification' => '',
		'bing_verification'   => '',
	);
}

function feast_get_seo_settings() {
	return wp_parse_args( (array) get_option( 'feast_seo', array() ), feast_seo_defaults() );
}

function feast_seo_setting( $key ) {
	$settings = feast_get_seo_settings();
	return isset( $settings[ $key ] ) ? $settings[ $key ] : '';
}

function feast_register_seo_settings() {
	register_setting( 'feast_seo_group', 'feast_seo', 'feast_sanitize_seo_settings' );
}
add_action( 'admin_init', 'feast_register_seo_settings' );

function feast_sanitize_seo_settings( $input ) {
	$defaults = feast_seo_defaults();
	$output   = array();
	$url_keys = array( 'default_social_image', 'google_business_url', 'facebook_url', 'tiktok_url' );
	foreach ( $defaults as $key => $default ) {
		$value = isset( $input[ $key ] ) ? wp_unslash( $input[ $key ] ) : $default;
		if ( in_array( $key, $url_keys, true ) ) {
			$output[ $key ] = esc_url_raw( $value );
		} elseif ( 'public_email' === $key ) {
			$output[ $key ] = is_email( $value ) ? sanitize_email( $value ) : '';
		} elseif ( in_array( $key, array( 'home_description', 'business_description', 'opening_hours' ), true ) ) {
			$output[ $key ] = sanitize_textarea_field( $value );
		} else {
			$output[ $key ] = sanitize_text_field( $value );
		}
	}
	return $output;
}

function feast_seo_admin_menu() {
	add_submenu_page( 'feast-cms', 'SEO & Local Business', 'SEO & Local Business', 'manage_options', 'feast-seo', 'feast_seo_page' );
}
add_action( 'admin_menu', 'feast_seo_admin_menu' );

function feast_seo_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$settings = feast_get_seo_settings();
	$groups = array(
		'Homepage search preview' => array( 'home_title', 'home_description', 'default_social_image' ),
		'Local business details'  => array( 'business_description', 'public_email', 'street_address', 'address_locality', 'address_region', 'postal_code', 'address_country', 'latitude', 'longitude', 'service_area', 'cuisines', 'price_range', 'currency' ),
		'Opening hours'           => array( 'opening_hours' ),
		'Profiles & verification' => array( 'google_business_url', 'facebook_url', 'tiktok_url', 'google_verification', 'bing_verification' ),
	);
	$labels = array(
		'home_title' => 'Homepage SEO title', 'home_description' => 'Homepage meta description', 'default_social_image' => 'Default social sharing image URL', 'business_description' => 'Business description', 'public_email' => 'Public business email', 'street_address' => 'Street address', 'address_locality' => 'Suburb / locality', 'address_region' => 'State / region', 'postal_code' => 'Postcode', 'address_country' => 'Country code', 'latitude' => 'Latitude (optional)', 'longitude' => 'Longitude (optional)', 'service_area' => 'Catering service area', 'cuisines' => 'Cuisines (comma separated)', 'price_range' => 'Price range', 'currency' => 'Currency code', 'opening_hours' => 'Opening hours', 'google_business_url' => 'Google Business Profile URL', 'facebook_url' => 'Facebook URL', 'tiktok_url' => 'TikTok URL', 'google_verification' => 'Google Search Console verification code', 'bing_verification' => 'Bing Webmaster verification code',
	);
	?>
	<div class="wrap feast-admin-wrap"><h1>SEO &amp; Local Business</h1><p class="feast-admin-lead">Manage how the business is described to search engines and social platforms. Individual pages have their own SEO fields in the page editor.</p>
	<?php if ( ! get_option( 'blog_public' ) ) : ?><div class="notice notice-error inline"><p><strong>Search engine indexing is currently disabled.</strong> Open <a href="<?php echo esc_url( admin_url( 'options-reading.php' ) ); ?>">Settings → Reading</a> and untick “Discourage search engines from indexing this site” before launch.</p></div><?php else : ?><div class="notice notice-success inline"><p><strong>Search engine indexing is enabled.</strong> XML sitemap: <a href="<?php echo esc_url( home_url( '/wp-sitemap.xml' ) ); ?>" target="_blank" rel="noopener"><?php echo esc_html( home_url( '/wp-sitemap.xml' ) ); ?></a></p></div><?php endif; ?>
	<form method="post" action="options.php"><?php settings_fields( 'feast_seo_group' ); ?>
		<?php foreach ( $groups as $group_title => $keys ) : ?><div class="feast-admin-card"><h2><?php echo esc_html( $group_title ); ?></h2>
			<?php if ( 'Opening hours' === $group_title ) : ?><p>One period per line using <code>Monday,Tuesday|09:00|17:00</code>. Leave blank until the client confirms the hours.</p><?php endif; ?>
			<?php foreach ( $keys as $key ) : $textarea = in_array( $key, array( 'home_description', 'business_description', 'opening_hours' ), true ); ?><div class="feast-admin-field"><label for="seo-<?php echo esc_attr( $key ); ?>"><strong><?php echo esc_html( $labels[ $key ] ); ?></strong></label><?php if ( $textarea ) : ?><textarea id="seo-<?php echo esc_attr( $key ); ?>" rows="4" name="feast_seo[<?php echo esc_attr( $key ); ?>]"><?php echo esc_textarea( $settings[ $key ] ); ?></textarea><?php else : ?><input id="seo-<?php echo esc_attr( $key ); ?>" type="text" name="feast_seo[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $settings[ $key ] ); ?>"><?php endif; ?></div><?php endforeach; ?>
		</div><?php endforeach; ?>
		<?php submit_button( 'Save SEO settings' ); ?>
	</form></div>
	<?php
}

function feast_has_seo_plugin() {
	return defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'AIOSEO_VERSION' ) || class_exists( 'WPSEO_Options' ) || class_exists( 'RankMath' );
}

function feast_page_seo_value( $key ) {
	if ( ! is_singular() ) {
		return '';
	}
	return get_post_meta( get_queried_object_id(), $key, true );
}

function feast_meta_description() {
	if ( is_front_page() ) {
		return feast_seo_setting( 'home_description' );
	}
	$custom = feast_page_seo_value( '_feast_meta_description' );
	if ( $custom ) {
		return $custom;
	}
	if ( is_singular() ) {
		$post = get_queried_object();
		if ( ! empty( $post->post_excerpt ) ) {
			return wp_strip_all_tags( $post->post_excerpt );
		}
		return wp_trim_words( wp_strip_all_tags( strip_shortcodes( $post->post_content ) ), 28, '' );
	}
	return get_bloginfo( 'description' );
}

function feast_filter_document_title( $title ) {
	if ( feast_has_seo_plugin() ) {
		return $title;
	}
	if ( is_front_page() && feast_seo_setting( 'home_title' ) ) {
		return feast_seo_setting( 'home_title' );
	}
	$custom = feast_page_seo_value( '_feast_seo_title' );
	if ( $custom ) {
		return $custom;
	}
	if ( is_singular() ) {
		return get_the_title( get_queried_object_id() ) . ' | ' . feast_copy( 'brand_name' );
	}
	return $title;
}
add_filter( 'pre_get_document_title', 'feast_filter_document_title', 20 );

function feast_canonical_url() {
	$custom = feast_page_seo_value( '_feast_canonical_url' );
	if ( $custom ) {
		return $custom;
	}
	if ( is_front_page() ) {
		return home_url( '/' );
	}
	if ( is_singular() ) {
		return wp_get_canonical_url( get_queried_object_id() );
	}
	return '';
}

function feast_social_image() {
	$custom = feast_page_seo_value( '_feast_social_image' );
	if ( $custom ) {
		return $custom;
	}
	if ( is_singular() && has_post_thumbnail( get_queried_object_id() ) ) {
		return get_the_post_thumbnail_url( get_queried_object_id(), 'full' );
	}
	if ( feast_seo_setting( 'default_social_image' ) ) {
		return feast_seo_setting( 'default_social_image' );
	}
	if ( is_front_page() ) {
		$offers = feast_content_items( 'feast_offer', 1 );
		if ( $offers && has_post_thumbnail( $offers[0]->ID ) ) {
			return get_the_post_thumbnail_url( $offers[0]->ID, 'full' );
		}
	}
	$logo_id = get_theme_mod( 'custom_logo' );
	return $logo_id ? wp_get_attachment_image_url( $logo_id, 'full' ) : feast_asset( 'logo.jpg' );
}

function feast_output_seo_meta() {
	if ( feast_has_seo_plugin() ) {
		return;
	}
	$description = feast_meta_description();
	$canonical   = feast_canonical_url();
	$title       = wp_get_document_title();
	$image       = feast_social_image();
	if ( $description ) {
		echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
	}
	if ( $canonical ) {
		echo '<link rel="canonical" href="' . esc_url( $canonical ) . '">' . "\n";
	}
	if ( feast_seo_setting( 'google_verification' ) ) {
		echo '<meta name="google-site-verification" content="' . esc_attr( feast_seo_setting( 'google_verification' ) ) . '">' . "\n";
	}
	if ( feast_seo_setting( 'bing_verification' ) ) {
		echo '<meta name="msvalidate.01" content="' . esc_attr( feast_seo_setting( 'bing_verification' ) ) . '">' . "\n";
	}
	echo '<meta property="og:locale" content="en_AU">' . "\n";
	echo '<meta property="og:type" content="' . ( is_singular( 'post' ) ? 'article' : 'website' ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
	if ( $description ) {
		echo '<meta property="og:description" content="' . esc_attr( $description ) . '">' . "\n";
	}
	if ( $canonical ) {
		echo '<meta property="og:url" content="' . esc_url( $canonical ) . '">' . "\n";
	}
	echo '<meta property="og:site_name" content="' . esc_attr( feast_copy( 'brand_name' ) ) . '">' . "\n";
	if ( $image ) {
		echo '<meta property="og:image" content="' . esc_url( $image ) . '">' . "\n";
	}
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '">' . "\n";
	if ( $description ) {
		echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '">' . "\n";
	}
	if ( $image ) {
		echo '<meta name="twitter:image" content="' . esc_url( $image ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'feast_output_seo_meta', 2 );

function feast_disable_core_canonical() {
	if ( ! feast_has_seo_plugin() ) {
		remove_action( 'wp_head', 'rel_canonical' );
	}
}
add_action( 'wp', 'feast_disable_core_canonical' );

function feast_filter_robots( $robots ) {
	if ( feast_has_seo_plugin() ) {
		return $robots;
	}
	$robots['max-image-preview'] = 'large';
	if ( ( is_singular() && '1' === feast_page_seo_value( '_feast_noindex' ) ) || is_search() || is_404() ) {
		$robots['noindex'] = true;
	}
	return $robots;
}
add_filter( 'wp_robots', 'feast_filter_robots' );

function feast_opening_hours_schema() {
	$lines = preg_split( '/\r\n|\r|\n/', feast_seo_setting( 'opening_hours' ) );
	$valid_days = array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday' );
	$output = array();
	foreach ( array_filter( array_map( 'trim', $lines ) ) as $line ) {
		$parts = array_map( 'trim', explode( '|', $line ) );
		if ( 3 !== count( $parts ) || ! preg_match( '/^\d{2}:\d{2}$/', $parts[1] ) || ! preg_match( '/^\d{2}:\d{2}$/', $parts[2] ) ) {
			continue;
		}
		$days = array_values( array_intersect( $valid_days, array_map( 'trim', explode( ',', $parts[0] ) ) ) );
		if ( ! $days ) {
			continue;
		}
		$output[] = array( '@type' => 'OpeningHoursSpecification', 'dayOfWeek' => $days, 'opens' => $parts[1], 'closes' => $parts[2] );
	}
	return $output;
}

function feast_menu_schema() {
	$dishes = feast_content_items( 'feast_dish' );
	$labels = array( 'mains' => feast_copy( 'menu_category_mains' ), 'salads' => feast_copy( 'menu_category_salads' ), 'bites' => feast_copy( 'menu_category_bites' ) );
	$sections = array();
	foreach ( $labels as $key => $label ) {
		$items = array();
		foreach ( $dishes as $dish ) {
			if ( $key !== get_post_meta( $dish->ID, '_feast_category', true ) ) {
				continue;
			}
			$item = array( '@type' => 'MenuItem', 'name' => get_the_title( $dish ) );
			if ( $dish->post_excerpt ) {
				$item['description'] = wp_strip_all_tags( $dish->post_excerpt );
			}
			if ( has_post_thumbnail( $dish->ID ) ) {
				$item['image'] = get_the_post_thumbnail_url( $dish->ID, 'large' );
			}
			$price = get_post_meta( $dish->ID, '_feast_price', true );
			if ( $price && preg_match( '/(\d+(?:\.\d{1,2})?)/', str_replace( ',', '', $price ), $match ) ) {
				$item['offers'] = array( '@type' => 'Offer', 'price' => $match[1], 'priceCurrency' => feast_seo_setting( 'currency' ) );
			}
			$items[] = $item;
		}
		if ( $items ) {
			$sections[] = array( '@type' => 'MenuSection', 'name' => $label, 'hasMenuItem' => $items );
		}
	}
	return array( '@type' => 'Menu', '@id' => home_url( '/menu/#menu' ), 'name' => 'Feast in the Middle East Menu', 'url' => home_url( '/menu/' ), 'hasMenuSection' => $sections );
}

function feast_structured_data_graph() {
	$home       = home_url( '/' );
	$business_id= $home . '#restaurant';
	$website_id = $home . '#website';
	$image      = feast_social_image();
	$same_as    = array_filter( array( feast_setting( 'instagram' ), feast_seo_setting( 'facebook_url' ), feast_seo_setting( 'tiktok_url' ), feast_seo_setting( 'google_business_url' ) ) );
	$restaurant = array(
		'@type' => 'Restaurant', '@id' => $business_id, 'name' => feast_copy( 'brand_name' ), 'url' => $home, 'description' => feast_seo_setting( 'business_description' ), 'telephone' => feast_setting( 'phone_link' ), 'priceRange' => feast_seo_setting( 'price_range' ), 'servesCuisine' => array_values( array_filter( array_map( 'trim', explode( ',', feast_seo_setting( 'cuisines' ) ) ) ) ), 'areaServed' => feast_seo_setting( 'service_area' ), 'hasMenu' => home_url( '/menu/' ), 'address' => array( '@type' => 'PostalAddress', 'streetAddress' => feast_seo_setting( 'street_address' ), 'addressLocality' => feast_seo_setting( 'address_locality' ), 'addressRegion' => feast_seo_setting( 'address_region' ), 'postalCode' => feast_seo_setting( 'postal_code' ), 'addressCountry' => feast_seo_setting( 'address_country' ) ),
	);
	$logo_id = get_theme_mod( 'custom_logo' );
	$restaurant['logo'] = $logo_id ? wp_get_attachment_image_url( $logo_id, 'full' ) : feast_asset( 'logo.jpg' );
	if ( $image ) { $restaurant['image'] = $image; }
	if ( $same_as ) { $restaurant['sameAs'] = array_values( $same_as ); }
	if ( feast_seo_setting( 'public_email' ) ) { $restaurant['email'] = feast_seo_setting( 'public_email' ); }
	$hours = feast_opening_hours_schema();
	if ( $hours ) { $restaurant['openingHoursSpecification'] = $hours; }
	if ( is_numeric( feast_seo_setting( 'latitude' ) ) && is_numeric( feast_seo_setting( 'longitude' ) ) ) {
		$restaurant['geo'] = array( '@type' => 'GeoCoordinates', 'latitude' => (float) feast_seo_setting( 'latitude' ), 'longitude' => (float) feast_seo_setting( 'longitude' ) );
	}
	$graph = array(
		$restaurant,
		array( '@type' => 'WebSite', '@id' => $website_id, 'url' => $home, 'name' => feast_copy( 'brand_name' ), 'publisher' => array( '@id' => $business_id ), 'inLanguage' => 'en-AU' ),
	);
	if ( is_front_page() || is_singular( 'page' ) ) {
		$url = feast_canonical_url();
		$page = array( '@type' => 'WebPage', '@id' => $url . '#webpage', 'url' => $url, 'name' => wp_get_document_title(), 'description' => feast_meta_description(), 'isPartOf' => array( '@id' => $website_id ), 'about' => array( '@id' => $business_id ), 'inLanguage' => 'en-AU' );
		if ( ! is_front_page() ) {
			$page['breadcrumb'] = array( '@id' => $url . '#breadcrumb' );
			$graph[] = array( '@type' => 'BreadcrumbList', '@id' => $url . '#breadcrumb', 'itemListElement' => array( array( '@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $home ), array( '@type' => 'ListItem', 'position' => 2, 'name' => get_the_title( get_queried_object_id() ), 'item' => $url ) ) );
		}
		$graph[] = $page;
	}
	if ( is_page( 'menu' ) ) {
		$graph[] = feast_menu_schema();
	}
	if ( is_page( 'catering' ) ) {
		$service_id = home_url( '/catering/#service' );
		$graph[] = array( '@type' => 'Service', '@id' => $service_id, 'name' => 'Middle Eastern Catering', 'url' => home_url( '/catering/' ), 'description' => feast_meta_description(), 'provider' => array( '@id' => $business_id ), 'areaServed' => feast_seo_setting( 'service_area' ), 'serviceType' => 'Middle Eastern catering' );
		$faqs = feast_content_items( 'feast_faq' );
		if ( $faqs ) {
			$questions = array();
			foreach ( $faqs as $faq ) {
				$questions[] = array( '@type' => 'Question', 'name' => get_the_title( $faq ), 'acceptedAnswer' => array( '@type' => 'Answer', 'text' => wp_strip_all_tags( $faq->post_content ) ) );
			}
			$graph[] = array( '@type' => 'FAQPage', '@id' => home_url( '/catering/#faq' ), 'mainEntity' => $questions );
		}
	}
	return array( '@context' => 'https://schema.org', '@graph' => $graph );
}

function feast_output_structured_data() {
	if ( feast_has_seo_plugin() || is_admin() || is_feed() || is_search() || is_404() ) {
		return;
	}
	echo '<script type="application/ld+json">' . wp_json_encode( feast_structured_data_graph(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'feast_output_structured_data', 20 );
