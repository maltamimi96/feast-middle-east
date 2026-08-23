<?php
get_header();
while ( have_posts() ) : the_post();
	if ( feast_is_elementor_page() ) {
		echo '<main id="main-content">';
		the_content();
		echo '</main>';
		continue;
	}
	$bundles = feast_content_items( 'feast_bundle' );
	$faqs    = feast_content_items( 'feast_faq' );
	?>
	<main id="main-content">
		<?php get_template_part( 'template-parts/inner-hero', null, array( 'eyebrow' => 'Catering in Sydney', 'fallback_image' => 'hero-catering-spread.jpg' ) ); ?>
		<section class="section section--cream"><div class="site-wrap"><div class="page-intro"><?php the_content(); ?></div>
			<div class="section-heading"><div><p class="eyebrow"><?php echo esc_html( feast_copy( 'catering_eyebrow' ) ); ?></p><h2><?php echo esc_html( feast_copy( 'catering_title' ) ); ?></h2></div><p class="lead"><?php echo esc_html( feast_copy( 'catering_intro' ) ); ?></p></div>
			<div class="bundle-grid">
			<?php foreach ( $bundles as $bundle ) :
				$features = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', get_post_meta( $bundle->ID, '_feast_features', true ) ) ) );
				$featured = '1' === get_post_meta( $bundle->ID, '_feast_featured', true );
				?><article class="bundle-card<?php echo $featured ? ' bundle-card--featured' : ''; ?>"><?php if ( get_post_meta( $bundle->ID, '_feast_tag', true ) ) : ?><span class="bundle-tag"><?php echo esc_html( get_post_meta( $bundle->ID, '_feast_tag', true ) ); ?></span><?php endif; ?><h3><?php echo esc_html( get_the_title( $bundle ) ); ?></h3><span class="bundle-for"><?php echo esc_html( get_post_meta( $bundle->ID, '_feast_audience', true ) ); ?></span><?php if ( get_post_meta( $bundle->ID, '_feast_price', true ) ) : ?><strong class="bundle-price"><?php echo esc_html( get_post_meta( $bundle->ID, '_feast_price', true ) ); ?></strong><?php endif; ?><?php if ( $features ) : ?><ul><?php foreach ( $features as $feature ) : ?><li><?php echo esc_html( $feature ); ?></li><?php endforeach; ?></ul><?php endif; ?><a class="text-link" href="<?php echo esc_url( feast_resolve_url( get_post_meta( $bundle->ID, '_feast_cta_link', true ) ?: '/contact/' ) ); ?>" data-bundle="<?php echo esc_attr( get_the_title( $bundle ) ); ?>"><?php echo esc_html( get_post_meta( $bundle->ID, '_feast_cta_label', true ) ); ?> →</a></article><?php endforeach; ?>
			</div>
		</div></section>
		<section class="section process"><div class="site-wrap"><div class="section-heading"><div><p class="eyebrow eyebrow--light"><?php echo esc_html( feast_copy( 'process_eyebrow' ) ); ?></p><h2><?php echo esc_html( feast_copy( 'process_title' ) ); ?></h2></div><p class="lead"><?php echo esc_html( feast_copy( 'process_intro' ) ); ?></p></div><div class="steps"><?php for ( $step = 1; $step <= 3; $step++ ) : ?><div class="step"><span class="step__number">0<?php echo esc_html( $step ); ?></span><h3><?php echo esc_html( feast_copy( 'step_' . $step . '_title' ) ); ?></h3><p><?php echo esc_html( feast_copy( 'step_' . $step . '_text' ) ); ?></p></div><?php endfor; ?></div></div></section>
		<?php if ( $faqs ) : ?><section class="section"><div class="site-wrap narrow-wrap"><p class="eyebrow"><?php echo esc_html( feast_copy( 'faq_eyebrow' ) ); ?></p><h2><?php echo esc_html( feast_copy( 'faq_title' ) ); ?></h2><div class="faq-list"><?php foreach ( $faqs as $faq ) : ?><details><summary><?php echo esc_html( get_the_title( $faq ) ); ?></summary><div><?php echo wp_kses_post( wpautop( $faq->post_content ) ); ?></div></details><?php endforeach; ?></div></div></section><?php endif; ?>
		<section class="section enquiry"><div class="site-wrap enquiry-grid"><div class="enquiry-copy"><p class="eyebrow"><?php echo esc_html( feast_copy( 'enquiry_eyebrow' ) ); ?></p><h2><?php echo esc_html( feast_copy( 'enquiry_title' ) ); ?></h2><p class="lead"><?php echo esc_html( feast_copy( 'enquiry_intro' ) ); ?></p><div class="contact-list"><a href="tel:<?php echo esc_attr( feast_setting( 'phone_link' ) ); ?>"><?php echo esc_html( feast_copy( 'call_label' ) ); ?> <?php echo esc_html( feast_setting( 'phone_display' ) ); ?></a><span><?php echo nl2br( esc_html( feast_setting( 'address' ) ) ); ?></span></div></div><?php get_template_part( 'template-parts/enquiry-form' ); ?></div></section>
	</main>
	<?php
endwhile;
get_footer();
