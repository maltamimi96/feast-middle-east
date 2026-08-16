<footer class="site-footer">
	<div class="site-wrap">
		<div class="footer-grid">
			<div class="footer-brand">
				<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo feast_asset( 'logo.jpg' ); ?>" alt="" width="64" height="64"><span class="brand__text">Feast in the Middle East <small>Granville, Sydney</small></span></a>
				<p>Generous Middle Eastern food for family tables, workplace lunches and the celebrations that matter.</p>
			</div>
			<div class="footer-col">
				<h3>Visit & contact</h3>
				<p><?php echo nl2br( esc_html( feast_setting( 'address' ) ) ); ?></p>
				<a href="tel:<?php echo esc_attr( feast_setting( 'phone_link' ) ); ?>"><?php echo esc_html( feast_setting( 'phone_display' ) ); ?></a>
			</div>
			<div class="footer-col">
				<h3>Explore</h3>
				<a href="<?php echo esc_url( home_url( '/catering/' ) ); ?>">Catering</a>
				<a href="<?php echo esc_url( home_url( '/menu/' ) ); ?>">Full menu</a>
				<a href="<?php echo esc_url( home_url( '/our-story/' ) ); ?>">Our story</a>
				<a href="<?php echo esc_url( home_url( '/gallery/' ) ); ?>">Gallery</a>
				<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Request a quote</a>
				<a href="<?php echo esc_url( feast_setting( 'instagram' ) ); ?>" target="_blank" rel="noopener">Follow on Instagram</a>
			</div>
		</div>
		<div class="footer-bottom"><span>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> Feast in the Middle East.</span><span>Traditional food. Generous hospitality.</span></div>
	</div>
</footer>
<a class="button mobile-quote" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Get a catering quote</a>
<?php wp_footer(); ?>
</body>
</html>
