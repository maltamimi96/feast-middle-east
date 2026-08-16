<?php
get_header();
while ( have_posts() ) : the_post();
	$dishes = feast_content_items( 'feast_dish' );
	?>
	<main id="main-content">
		<?php get_template_part( 'template-parts/inner-hero', null, array( 'eyebrow' => 'Traditional flavours', 'fallback_image' => 'menu-malfouf.jpg' ) ); ?>
		<section class="section"><div class="site-wrap"><div class="page-intro"><?php the_content(); ?></div>
		<?php foreach ( array( 'mains' => 'Hearty mains', 'salads' => 'Salads & sides', 'bites' => 'Bites & extras' ) as $category => $label ) :
			$category_dishes = array();
			foreach ( $dishes as $dish ) { if ( $category === get_post_meta( $dish->ID, '_feast_category', true ) ) { $category_dishes[] = $dish; } }
			if ( ! $category_dishes ) { continue; }
			?><div class="menu-category"><div class="section-heading"><div><p class="eyebrow">Our menu</p><h2><?php echo esc_html( $label ); ?></h2></div></div><div class="page-menu-grid"><?php foreach ( $category_dishes as $dish ) : ?><article class="page-dish"><?php if ( has_post_thumbnail( $dish->ID ) ) : ?><img src="<?php echo esc_url( get_the_post_thumbnail_url( $dish->ID, 'medium_large' ) ); ?>" alt="<?php echo esc_attr( get_the_title( $dish ) ); ?>" loading="lazy"><?php endif; ?><div><h3><?php echo esc_html( get_the_title( $dish ) ); ?></h3><?php if ( $dish->post_excerpt ) : ?><p><?php echo esc_html( $dish->post_excerpt ); ?></p><?php endif; ?></div></article><?php endforeach; ?></div></div>
		<?php endforeach; ?>
		<div class="page-cta"><div><p class="eyebrow eyebrow--light">Planning an event?</p><h2>Build a menu for your guests.</h2></div><a class="button button--light" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Request a catering quote</a></div>
		</div></section>
	</main>
	<?php
endwhile;
get_footer();
