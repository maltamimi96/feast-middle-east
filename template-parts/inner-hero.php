<?php
/**
 * Hero for editable WordPress pages.
 *
 * @package FeastMiddleEast
 */

$fallback_image = isset( $args['fallback_image'] ) ? $args['fallback_image'] : 'hero-catering-spread.jpg';
$saved_eyebrow  = get_post_meta( get_the_ID(), '_feast_page_eyebrow', true );
$eyebrow        = $saved_eyebrow ? $saved_eyebrow : ( isset( $args['eyebrow'] ) ? $args['eyebrow'] : 'Feast in the Middle East' );
$hero_image     = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'full' ) : feast_asset( $fallback_image );
?>
<section class="inner-hero">
	<img class="inner-hero__image" src="<?php echo esc_url( $hero_image ); ?>" alt="" fetchpriority="high">
	<div class="site-wrap inner-hero__content"><p class="eyebrow eyebrow--light"><?php echo esc_html( $eyebrow ); ?></p><h1><?php the_title(); ?></h1><?php if ( has_excerpt() ) : ?><p><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?></div>
</section>
