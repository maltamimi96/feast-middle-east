<?php if ( feast_elementor_location( 'footer' ) ) : ?>
<?php else : ?>
<footer class="site-footer">
	<div class="site-wrap">
		<div class="footer-grid">
			<div class="footer-brand">
				<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php $logo_id = get_theme_mod( 'custom_logo' ); if ( $logo_id ) : echo wp_get_attachment_image( $logo_id, 'thumbnail', false, array( 'alt' => '' ) ); else : ?><img src="<?php echo feast_asset( 'logo.jpg' ); ?>" alt="" width="64" height="64"><?php endif; ?><span class="brand__text"><?php echo esc_html( feast_copy( 'brand_name' ) ); ?> <small><?php echo esc_html( feast_copy( 'footer_location' ) ); ?></small></span></a>
				<p><?php echo esc_html( feast_copy( 'footer_description' ) ); ?></p>
			</div>
			<div class="footer-col">
				<h3><?php echo esc_html( feast_copy( 'footer_contact_title' ) ); ?></h3>
				<p><?php echo nl2br( esc_html( feast_setting( 'address' ) ) ); ?></p>
				<a href="tel:<?php echo esc_attr( feast_setting( 'phone_link' ) ); ?>"><?php echo esc_html( feast_setting( 'phone_display' ) ); ?></a>
			</div>
			<div class="footer-col">
				<h3><?php echo esc_html( feast_copy( 'footer_explore_title' ) ); ?></h3>
				<?php wp_nav_menu( array( 'theme_location' => 'footer', 'container' => false, 'menu_class' => 'footer-menu', 'fallback_cb' => 'feast_footer_menu_fallback' ) ); ?>
				<a href="<?php echo esc_url( feast_setting( 'instagram' ) ); ?>" target="_blank" rel="noopener"><?php echo esc_html( feast_copy( 'gallery_follow_label' ) ); ?></a>
			</div>
		</div>
		<div class="footer-bottom"><span>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( feast_copy( 'brand_name' ) ); ?>.</span><span><?php echo esc_html( feast_copy( 'footer_bottom_text' ) ); ?></span></div>
	</div>
</footer>
<a class="button mobile-quote" href="<?php echo esc_url( feast_resolve_url( feast_copy( 'mobile_cta_link' ) ) ); ?>"><?php echo esc_html( feast_copy( 'mobile_cta_label' ) ); ?></a>
<?php endif; ?>
<?php wp_footer(); ?>
</body>
</html>
