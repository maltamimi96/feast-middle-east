<?php
get_header();
while ( have_posts() ) : the_post();
	if ( feast_is_elementor_page() ) {
		echo '<main id="main-content">';
		the_content();
		echo '</main>';
		continue;
	}
	$gallery = feast_content_items( 'feast_gallery' );
	?>
	<main id="main-content">
		<?php get_template_part( 'template-parts/inner-hero', null, array( 'eyebrow' => 'Recent feasts', 'fallback_image' => 'hero-event-table.jpg' ) ); ?>
		<section class="section section--cream"><div class="site-wrap"><div class="page-intro"><?php the_content(); ?></div><div class="full-gallery-grid"><?php foreach ( $gallery as $item ) : if ( ! has_post_thumbnail( $item->ID ) ) { continue; } ?><figure><img src="<?php echo esc_url( get_the_post_thumbnail_url( $item->ID, 'large' ) ); ?>" alt="<?php echo esc_attr( get_the_title( $item ) ); ?>" loading="lazy"><figcaption><?php echo esc_html( get_the_title( $item ) ); ?></figcaption></figure><?php endforeach; ?></div><div class="gallery-follow"><a class="button button--outline" href="<?php echo esc_url( feast_setting( 'instagram' ) ); ?>" target="_blank" rel="noopener"><?php echo esc_html( feast_copy( 'gallery_follow_label' ) ); ?></a></div></div></section>
	</main>
	<?php
endwhile;
get_footer();
