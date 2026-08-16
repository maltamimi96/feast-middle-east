<?php
get_header();
while ( have_posts() ) : the_post();
	$dishes = feast_content_items( 'feast_dish' );
	?>
	<main id="main-content">
		<?php get_template_part( 'template-parts/inner-hero', null, array( 'eyebrow' => 'Traditional flavours', 'fallback_image' => 'menu-malfouf.jpg' ) ); ?>
		<section class="section"><div class="site-wrap"><div class="page-intro"><?php the_content(); ?></div>
		<?php foreach ( array( 'mains' => feast_copy( 'menu_category_mains' ), 'salads' => feast_copy( 'menu_category_salads' ), 'bites' => feast_copy( 'menu_category_bites' ) ) as $category => $label ) :
			$category_dishes = array();
			foreach ( $dishes as $dish ) { if ( $category === get_post_meta( $dish->ID, '_feast_category', true ) ) { $category_dishes[] = $dish; } }
			if ( ! $category_dishes ) { continue; }
			?><div class="menu-category"><div class="section-heading"><div><p class="eyebrow"><?php echo esc_html( feast_copy( 'menu_page_eyebrow' ) ); ?></p><h2><?php echo esc_html( $label ); ?></h2></div></div><div class="page-menu-grid"><?php foreach ( $category_dishes as $dish ) : ?><article class="page-dish"><?php if ( has_post_thumbnail( $dish->ID ) ) : ?><img src="<?php echo esc_url( get_the_post_thumbnail_url( $dish->ID, 'medium_large' ) ); ?>" alt="<?php echo esc_attr( get_the_title( $dish ) ); ?>" loading="lazy"><?php endif; ?><div><div class="page-dish__title"><h3><?php echo esc_html( get_the_title( $dish ) ); ?></h3><?php if ( get_post_meta( $dish->ID, '_feast_price', true ) ) : ?><strong><?php echo esc_html( get_post_meta( $dish->ID, '_feast_price', true ) ); ?></strong><?php endif; ?></div><?php if ( $dish->post_excerpt ) : ?><p><?php echo esc_html( $dish->post_excerpt ); ?></p><?php endif; ?><?php if ( get_post_meta( $dish->ID, '_feast_dietary', true ) ) : ?><small><?php echo esc_html( get_post_meta( $dish->ID, '_feast_dietary', true ) ); ?></small><?php endif; ?></div></article><?php endforeach; ?></div></div>
		<?php endforeach; ?>
		<?php $menu_cta_label = get_post_meta( get_the_ID(), '_feast_page_cta_label', true ) ?: feast_copy( 'menu_cta_label' ); $menu_cta_link = get_post_meta( get_the_ID(), '_feast_page_cta_link', true ) ?: feast_copy( 'menu_cta_link' ); ?>
		<div class="page-cta"><div><p class="eyebrow eyebrow--light"><?php echo esc_html( feast_copy( 'menu_cta_eyebrow' ) ); ?></p><h2><?php echo esc_html( feast_copy( 'menu_cta_title' ) ); ?></h2></div><a class="button button--light" href="<?php echo esc_url( feast_resolve_url( $menu_cta_link ) ); ?>"><?php echo esc_html( $menu_cta_label ); ?></a></div>
		</div></section>
	</main>
	<?php
endwhile;
get_footer();
