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
	<div class="site-wrap header-inner">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Feast in the Middle East home">
			<img src="<?php echo feast_asset( 'logo.jpg' ); ?>" alt="" width="64" height="64">
			<span class="brand__text">Feast in the Middle East <small>Traditional food made with love</small></span>
		</a>
		<button class="menu-toggle" type="button" aria-expanded="false" aria-controls="main-navigation" aria-label="Open menu"><span></span><span></span><span></span></button>
		<nav class="main-nav" id="main-navigation" aria-label="Primary navigation">
			<a href="<?php echo esc_url( home_url( '/#catering' ) ); ?>">Catering</a>
			<a href="<?php echo esc_url( home_url( '/#menu' ) ); ?>">Menu</a>
			<a href="<?php echo esc_url( home_url( '/#our-story' ) ); ?>">Our story</a>
			<a href="<?php echo esc_url( home_url( '/#gallery' ) ); ?>">Gallery</a>
			<a class="button button--light" href="<?php echo esc_url( home_url( '/#catering-enquiry' ) ); ?>">Get a catering quote</a>
		</nav>
	</div>
</header>
