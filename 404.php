<?php
/**
 * Not found template.
 *
 * Replaces the removed block template so 404s keep the site header, footer
 * and CMS-driven copy instead of falling through to index.php.
 *
 * @package FeastMiddleEast
 */

get_header();
?>
<main id="main-content" class="content-page">
	<div class="site-wrap narrow-wrap">
		<p class="eyebrow"><?php echo esc_html( feast_copy( 'notfound_eyebrow' ) ); ?></p>
		<h1><?php echo esc_html( feast_copy( 'notfound_title' ) ); ?></h1>
		<p class="lead"><?php echo esc_html( feast_copy( 'notfound_text' ) ); ?></p>
		<a class="button" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( feast_copy( 'notfound_cta_label' ) ); ?></a>
	</div>
</main>
<?php
get_footer();
