<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main-content">Skip to content</a>
<header class="site-header" id="site-header">
	<div class="announcement-bar">
		<div class="site-wrap announcement-bar__inner">
			<span class="announcement-bar__desktop"><?php echo esc_html( feast_copy( 'announcement_text' ) ); ?></span>
			<span class="announcement-bar__mobile"><?php echo esc_html( feast_copy( 'announcement_mobile' ) ); ?></span>
			<a href="tel:<?php echo esc_attr( feast_setting( 'phone_link' ) ); ?>"><?php echo esc_html( feast_copy( 'call_label' ) ); ?> <?php echo esc_html( feast_setting( 'phone_display' ) ); ?></a>
		</div>
	</div>
	<div class="site-wrap header-inner">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Feast in the Middle East home">
			<?php $logo_id = get_theme_mod( 'custom_logo' ); ?>
			<?php if ( $logo_id ) : echo wp_get_attachment_image( $logo_id, 'thumbnail', false, array( 'alt' => '' ) ); else : ?><img src="<?php echo feast_asset( 'logo.jpg' ); ?>" alt="" width="64" height="64"><?php endif; ?>
			<span class="brand__text"><?php echo esc_html( feast_copy( 'brand_name' ) ); ?> <small><?php echo esc_html( feast_copy( 'brand_tagline' ) ); ?></small></span>
		</a>
		<button class="menu-toggle" type="button" aria-expanded="false" aria-controls="main-navigation" aria-label="Open menu"><span></span><span></span><span></span></button>
		<nav class="main-nav" id="main-navigation" aria-label="Primary navigation">
			<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'menu_class' => 'main-nav__links', 'fallback_cb' => 'feast_primary_menu_fallback' ) ); ?>
			<a class="button button--light" href="<?php echo esc_url( feast_resolve_url( feast_copy( 'header_cta_link' ) ) ); ?>"><?php echo esc_html( feast_copy( 'header_cta_label' ) ); ?></a>
		</nav>
	</div>
</header>
