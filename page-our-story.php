<?php
get_header();
while ( have_posts() ) : the_post();
	$image = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'large' ) : feast_asset( 'owner-kitchen.jpg' );
	?>
	<main id="main-content">
		<?php get_template_part( 'template-parts/inner-hero', null, array( 'eyebrow' => 'Our table is your table', 'fallback_image' => 'owner-kitchen.jpg' ) ); ?>
		<section class="section"><div class="site-wrap story-grid"><div class="story-image"><img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>"><span class="story-stamp">Made with<br>love in<br>Granville</span></div><div class="story-copy page-copy"><p class="eyebrow"><?php echo esc_html( feast_copy( 'story_eyebrow' ) ); ?></p><?php the_content(); ?><a class="button button--outline" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Plan a feast with us</a></div></div></section>
		<section class="section process"><div class="site-wrap"><div class="section-heading"><div><p class="eyebrow eyebrow--light">What matters to us</p><h2>Traditional food. Generous hospitality.</h2></div><p class="lead">Food made carefully, served generously and designed to bring people together.</p></div></div></section>
	</main>
	<?php
endwhile;
get_footer();
