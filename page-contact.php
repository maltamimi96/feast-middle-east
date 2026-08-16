<?php
get_header();
while ( have_posts() ) : the_post();
	?>
	<main id="main-content">
		<?php get_template_part( 'template-parts/inner-hero', null, array( 'eyebrow' => 'Catering enquiries', 'fallback_image' => 'catering-selection.jpg' ) ); ?>
		<section class="section enquiry"><div class="site-wrap enquiry-grid"><div class="enquiry-copy"><div class="page-copy"><?php the_content(); ?></div><div class="contact-list"><a href="tel:<?php echo esc_attr( feast_setting( 'phone_link' ) ); ?>"><?php echo esc_html( feast_copy( 'call_label' ) ); ?> <?php echo esc_html( feast_setting( 'phone_display' ) ); ?></a><span><?php echo nl2br( esc_html( feast_setting( 'address' ) ) ); ?></span><a href="<?php echo esc_url( feast_setting( 'instagram' ) ); ?>" target="_blank" rel="noopener"><?php echo esc_html( feast_copy( 'contact_instagram_label' ) ); ?></a></div></div><?php get_template_part( 'template-parts/enquiry-form' ); ?></div></section>
	</main>
	<?php
endwhile;
get_footer();
