<?php
get_header();
while ( have_posts() ) : the_post();
	$image = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'large' ) : feast_asset( 'owner-kitchen.jpg' );
	?>
	<main id="main-content">
		<?php get_template_part( 'template-parts/inner-hero', null, array( 'eyebrow' => 'Our table is your table', 'fallback_image' => 'owner-kitchen.jpg' ) ); ?>
		<?php $story_page_cta_label = get_post_meta( get_the_ID(), '_feast_page_cta_label', true ) ?: feast_copy( 'story_page_cta_label' ); $story_page_cta_link = get_post_meta( get_the_ID(), '_feast_page_cta_link', true ) ?: feast_copy( 'story_page_cta_link' ); ?>
		<section class="section"><div class="site-wrap story-grid"><div class="story-image"><img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>"><span class="story-stamp"><?php echo nl2br( esc_html( feast_copy( 'story_stamp' ) ) ); ?></span></div><div class="story-copy page-copy"><p class="eyebrow"><?php echo esc_html( feast_copy( 'story_eyebrow' ) ); ?></p><?php the_content(); ?><a class="button button--outline" href="<?php echo esc_url( feast_resolve_url( $story_page_cta_link ) ); ?>"><?php echo esc_html( $story_page_cta_label ); ?></a></div></div></section>
		<section class="section process"><div class="site-wrap"><div class="section-heading"><div><p class="eyebrow eyebrow--light"><?php echo esc_html( feast_copy( 'values_eyebrow' ) ); ?></p><h2><?php echo esc_html( feast_copy( 'values_title' ) ); ?></h2></div><p class="lead"><?php echo esc_html( feast_copy( 'values_intro' ) ); ?></p></div></div></section>
	</main>
	<?php
endwhile;
get_footer();
