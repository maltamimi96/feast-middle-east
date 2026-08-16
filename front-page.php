<?php
get_header();
$cms_offers  = feast_content_items( 'feast_offer' );
$cms_bundles = feast_content_items( 'feast_bundle' );
$cms_dishes  = feast_content_items( 'feast_dish' );
$cms_gallery = feast_content_items( 'feast_gallery', 8 );
$hero_count  = ! empty( $cms_offers ) ? count( $cms_offers ) : 3;
$story_page  = get_page_by_path( 'our-story', OBJECT, 'page' );
$story_image = $story_page && has_post_thumbnail( $story_page->ID ) ? get_the_post_thumbnail_url( $story_page->ID, 'large' ) : feast_asset( 'owner-kitchen.jpg' );
?>
<main id="main-content">
	<section class="hero" aria-label="Featured catering offers">
		<div class="hero-slides" data-slider>
			<?php if ( ! empty( $cms_offers ) ) : ?>
				<?php foreach ( $cms_offers as $index => $offer ) :
					$image = get_the_post_thumbnail_url( $offer->ID, 'full' );
					if ( ! $image ) {
						$image = feast_asset( 'hero-catering-spread.jpg' );
					}
					$primary_link = get_post_meta( $offer->ID, '_feast_primary_link', true );
					$second_link  = get_post_meta( $offer->ID, '_feast_second_link', true );
					$overlay      = sanitize_hex_color( get_post_meta( $offer->ID, '_feast_overlay_color', true ) );
					$opacity      = absint( get_post_meta( $offer->ID, '_feast_overlay_opacity', true ) );
					$slide_vars   = array(
						'--slide-overlay:' . ( $overlay ? $overlay : '#071309' ),
						'--slide-opacity:' . ( $opacity ? min( 95, $opacity ) : 82 ) . '%',
						'--slide-text:' . ( sanitize_hex_color( get_post_meta( $offer->ID, '_feast_text_color', true ) ) ?: feast_design( 'hero_text' ) ),
						'--slide-accent:' . ( sanitize_hex_color( get_post_meta( $offer->ID, '_feast_accent_color', true ) ) ?: feast_design( 'hero_accent' ) ),
						'--slide-primary:' . ( sanitize_hex_color( get_post_meta( $offer->ID, '_feast_primary_color', true ) ) ?: '#ffffff' ),
						'--slide-primary-text:' . ( sanitize_hex_color( get_post_meta( $offer->ID, '_feast_primary_text', true ) ) ?: feast_design( 'forest' ) ),
						'--slide-second:' . ( sanitize_hex_color( get_post_meta( $offer->ID, '_feast_second_color', true ) ) ?: feast_design( 'forest' ) ),
						'--slide-second-text:' . ( sanitize_hex_color( get_post_meta( $offer->ID, '_feast_second_text', true ) ) ?: '#ffffff' ),
					);
					?>
					<article class="hero-slide<?php echo 0 === $index ? ' is-active' : ''; ?>" style="<?php echo esc_attr( implode( ';', $slide_vars ) ); ?>" data-slide aria-hidden="<?php echo 0 === $index ? 'false' : 'true'; ?>">
						<img class="hero-slide__image" src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( get_the_title( $offer ) ); ?>" <?php echo 0 === $index ? 'fetchpriority="high"' : 'loading="lazy"'; ?>>
						<div class="site-wrap hero-content"><div class="hero-copy">
							<p class="eyebrow eyebrow--light"><?php echo esc_html( get_post_meta( $offer->ID, '_feast_eyebrow', true ) ); ?></p>
							<h1><?php echo esc_html( get_the_title( $offer ) ); ?></h1>
							<p><?php echo esc_html( $offer->post_excerpt ); ?></p>
							<div class="hero-actions">
								<?php if ( get_post_meta( $offer->ID, '_feast_primary_label', true ) ) : ?><a class="button button--light" href="<?php echo esc_url( $primary_link ? $primary_link : '#catering-enquiry' ); ?>"><?php echo esc_html( get_post_meta( $offer->ID, '_feast_primary_label', true ) ); ?></a><?php endif; ?>
								<?php if ( get_post_meta( $offer->ID, '_feast_second_label', true ) ) : ?><a class="button" href="<?php echo esc_url( $second_link ? $second_link : '#catering' ); ?>"><?php echo esc_html( get_post_meta( $offer->ID, '_feast_second_label', true ) ); ?></a><?php endif; ?>
							</div>
							<?php if ( get_post_meta( $offer->ID, '_feast_note', true ) ) : ?><div class="hero-note"><?php echo esc_html( get_post_meta( $offer->ID, '_feast_note', true ) ); ?></div><?php endif; ?>
						</div></div>
					</article>
				<?php endforeach; ?>
			<?php else : ?>
			<article class="hero-slide is-active" data-slide aria-hidden="false">
				<img class="hero-slide__image" src="<?php echo feast_asset( 'hero-catering-spread.jpg' ); ?>" alt="A generous spread of Middle Eastern catering dishes" fetchpriority="high">
				<div class="site-wrap hero-content"><div class="hero-copy">
					<p class="eyebrow eyebrow--light">Middle Eastern catering across Sydney</p>
					<h1>A feast worth gathering for.</h1>
					<p>Generous, traditional food for weddings, work lunches, family celebrations and everything in between.</p>
					<div class="hero-actions"><a class="button button--light" href="#catering-enquiry">Request a catering quote</a><a class="button" href="#catering">Explore catering</a></div>
					<div class="hero-note">Freshly prepared in Granville</div>
				</div></div>
			</article>
			<article class="hero-slide" data-slide aria-hidden="true">
				<img class="hero-slide__image" src="<?php echo feast_asset( 'hero-chicken-mansaf.jpg' ); ?>" alt="Chicken mansaf served for a shared meal" loading="lazy">
				<div class="site-wrap hero-content"><div class="hero-copy">
					<p class="eyebrow eyebrow--light">The family feast</p>
					<h1>Big tables. Full plates. Happy people.</h1>
					<p>Build a share-style feast with hot mains, fresh salads, sides and sweets, made to suit your gathering.</p>
					<div class="hero-actions"><a class="button button--light" href="#catering-enquiry">Build your feast</a><a class="button" href="#menu">See menu favourites</a></div>
					<div class="hero-note">Custom menus for 10–100+ guests</div>
				</div></div>
			</article>
			<article class="hero-slide" data-slide aria-hidden="true">
				<img class="hero-slide__image" src="<?php echo feast_asset( 'hero-event-table.jpg' ); ?>" alt="A colourful catered event table" loading="lazy">
				<div class="site-wrap hero-content"><div class="hero-copy">
					<p class="eyebrow eyebrow--light">Office & event catering</p>
					<h1>Your next event, beautifully fed.</h1>
					<p>From team lunches to milestone celebrations, tell us your guest count and we’ll help plan the food.</p>
					<div class="hero-actions"><a class="button button--light" href="#catering-enquiry">Cater my event</a><a class="button" href="tel:<?php echo esc_attr( feast_setting( 'phone_link' ) ); ?>">Call <?php echo esc_html( feast_setting( 'phone_display' ) ); ?></a></div>
					<div class="hero-note">Pickup and delivery options available</div>
				</div></div>
			</article>
			<?php endif; ?>
		</div>
		<div class="slider-controls">
			<div class="slider-dots" aria-label="Choose an offer">
				<?php for ( $dot = 0; $dot < $hero_count; $dot++ ) : ?><button class="slider-dot<?php echo 0 === $dot ? ' is-active' : ''; ?>" type="button" data-slide-to="<?php echo esc_attr( $dot ); ?>" aria-label="Show catering offer <?php echo esc_attr( $dot + 1 ); ?>" <?php echo 0 === $dot ? 'aria-current="true"' : ''; ?>></button><?php endfor; ?>
			</div>
			<div class="slider-arrows"><button class="slider-arrow" type="button" data-slide-prev aria-label="Previous offer">&#8592;</button><button class="slider-arrow" type="button" data-slide-next aria-label="Next offer">&#8594;</button></div>
		</div>
	</section>

	<section class="section section--cream" id="catering">
		<div class="site-wrap">
			<div class="section-heading"><div><p class="eyebrow"><?php echo esc_html( feast_copy( 'catering_eyebrow' ) ); ?></p><h2><?php echo esc_html( feast_copy( 'catering_title' ) ); ?></h2></div><p class="lead"><?php echo esc_html( feast_copy( 'catering_intro' ) ); ?></p></div>
			<div class="bundle-grid">
				<?php if ( ! empty( $cms_bundles ) ) : ?>
					<?php foreach ( $cms_bundles as $bundle ) :
						$features = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', get_post_meta( $bundle->ID, '_feast_features', true ) ) ) );
						$featured = '1' === get_post_meta( $bundle->ID, '_feast_featured', true );
						?>
						<article class="bundle-card<?php echo $featured ? ' bundle-card--featured' : ''; ?>">
							<?php if ( get_post_meta( $bundle->ID, '_feast_tag', true ) ) : ?><span class="bundle-tag"><?php echo esc_html( get_post_meta( $bundle->ID, '_feast_tag', true ) ); ?></span><?php endif; ?>
							<h3><?php echo esc_html( get_the_title( $bundle ) ); ?></h3>
							<span class="bundle-for"><?php echo esc_html( get_post_meta( $bundle->ID, '_feast_audience', true ) ); ?></span>
							<?php if ( get_post_meta( $bundle->ID, '_feast_price', true ) ) : ?><strong class="bundle-price"><?php echo esc_html( get_post_meta( $bundle->ID, '_feast_price', true ) ); ?></strong><?php endif; ?>
							<?php if ( ! empty( $features ) ) : ?><ul><?php foreach ( $features as $feature ) : ?><li><?php echo esc_html( $feature ); ?></li><?php endforeach; ?></ul><?php endif; ?>
							<a class="text-link" href="<?php echo esc_url( feast_resolve_url( get_post_meta( $bundle->ID, '_feast_cta_link', true ) ?: '#catering-enquiry' ) ); ?>" data-bundle="<?php echo esc_attr( get_the_title( $bundle ) ); ?>"><?php echo esc_html( get_post_meta( $bundle->ID, '_feast_cta_label', true ) ? get_post_meta( $bundle->ID, '_feast_cta_label', true ) : 'Ask about this package' ); ?> →</a>
						</article>
					<?php endforeach; ?>
				<?php else : ?>
				<article class="bundle-card"><span class="bundle-tag">Warm & generous</span><h3>Family Table</h3><span class="bundle-for">Ideal for 10–25 guests</span><ul><li>Your choice of hearty main dishes</li><li>Fresh salads and traditional sides</li><li>Share-style trays, ready for the table</li><li>Custom quote based on your menu</li></ul><a class="text-link" href="#catering-enquiry" data-bundle="Family Table">Ask about this feast →</a></article>
				<article class="bundle-card bundle-card--featured"><span class="bundle-tag">Most popular</span><h3>Celebration Feast</h3><span class="bundle-for">Ideal for 25–100+ guests</span><ul><li>A generous mix of mains and favourites</li><li>Salads, dips, sides and finger food</li><li>Designed for weddings and big occasions</li><li>Custom quote built for your guest count</li></ul><a class="text-link" href="#catering-enquiry" data-bundle="Celebration Feast">Plan my celebration →</a></article>
				<article class="bundle-card"><span class="bundle-tag">Easy crowd-pleaser</span><h3>Office Lunch</h3><span class="bundle-for">Ideal for teams of 10+</span><ul><li>Easy-to-serve hot mains or wraps</li><li>Fresh salads, dips and sides</li><li>Flexible options for team preferences</li><li>Custom quote for pickup or delivery</li></ul><a class="text-link" href="#catering-enquiry" data-bundle="Office Lunch">Feed the team →</a></article>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<?php if ( shortcode_exists( 'trustindex' ) ) : ?>
	<section class="section reviews-section" id="reviews" aria-labelledby="reviews-title">
		<div class="site-wrap">
			<div class="section-heading"><div><p class="eyebrow"><?php echo esc_html( feast_copy( 'reviews_eyebrow' ) ); ?></p><h2 id="reviews-title"><?php echo esc_html( feast_copy( 'reviews_title' ) ); ?></h2></div><p class="lead"><?php echo esc_html( feast_copy( 'reviews_intro' ) ); ?></p></div>
			<div class="reviews-widget"><?php echo do_shortcode( '[trustindex no-registration=google]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		</div>
	</section>
	<?php endif; ?>

	<section class="section" id="menu">
		<div class="site-wrap">
			<div class="section-heading"><div><p class="eyebrow"><?php echo esc_html( feast_copy( 'menu_eyebrow' ) ); ?></p><h2><?php echo esc_html( feast_copy( 'menu_title' ) ); ?></h2></div><p class="lead"><?php echo esc_html( feast_copy( 'menu_intro' ) ); ?></p></div>
			<?php if ( ! empty( $cms_dishes ) ) :
				$showcase_dishes = array();
				foreach ( $cms_dishes as $dish ) {
					if ( '1' === get_post_meta( $dish->ID, '_feast_showcase', true ) && has_post_thumbnail( $dish->ID ) ) {
						$showcase_dishes[] = $dish;
					}
				}
				if ( empty( $showcase_dishes ) ) {
					foreach ( $cms_dishes as $dish ) {
						if ( has_post_thumbnail( $dish->ID ) ) {
							$showcase_dishes[] = $dish;
						}
						if ( 3 === count( $showcase_dishes ) ) {
							break;
						}
					}
				}
				$showcase_dishes = array_slice( $showcase_dishes, 0, 3 );
				?>
				<?php if ( ! empty( $showcase_dishes ) ) : ?><div class="menu-showcase">
					<?php foreach ( $showcase_dishes as $dish ) : ?><article class="dish-card"><img src="<?php echo esc_url( get_the_post_thumbnail_url( $dish->ID, 'large' ) ); ?>" alt="<?php echo esc_attr( get_the_title( $dish ) ); ?>" loading="lazy"><div class="dish-card__copy"><div class="dish-title-row"><h3><?php echo esc_html( get_the_title( $dish ) ); ?></h3><?php if ( get_post_meta( $dish->ID, '_feast_price', true ) ) : ?><strong><?php echo esc_html( get_post_meta( $dish->ID, '_feast_price', true ) ); ?></strong><?php endif; ?></div><p><?php echo esc_html( $dish->post_excerpt ); ?></p><?php if ( get_post_meta( $dish->ID, '_feast_dietary', true ) ) : ?><small><?php echo esc_html( get_post_meta( $dish->ID, '_feast_dietary', true ) ); ?></small><?php endif; ?></div></article><?php endforeach; ?>
				</div><?php endif; ?>
				<div class="menu-list">
					<?php
					$category_labels = array( 'mains' => feast_copy( 'menu_category_mains' ), 'salads' => feast_copy( 'menu_category_salads' ), 'bites' => feast_copy( 'menu_category_bites' ) );
					foreach ( $category_labels as $category_key => $category_label ) :
						$category_dishes = array();
						foreach ( $cms_dishes as $dish ) {
							if ( $category_key === get_post_meta( $dish->ID, '_feast_category', true ) ) {
								$category_dishes[] = $dish;
							}
						}
						if ( empty( $category_dishes ) ) {
							continue;
						}
						?>
						<div class="menu-group"><h3><?php echo esc_html( $category_label ); ?></h3><?php foreach ( $category_dishes as $dish ) : ?><p><span><?php echo esc_html( get_the_title( $dish ) ); ?></span><?php if ( get_post_meta( $dish->ID, '_feast_price', true ) ) : ?><strong><?php echo esc_html( get_post_meta( $dish->ID, '_feast_price', true ) ); ?></strong><?php endif; ?></p><?php endforeach; ?></div>
					<?php endforeach; ?>
				</div>
			<?php else : ?>
			<div class="menu-showcase">
				<article class="dish-card"><img src="<?php echo feast_asset( 'menu-malfouf.jpg' ); ?>" alt="Malfouf, stuffed cabbage rolls served with meat" loading="lazy"><div class="dish-card__copy"><h3>Malfouf</h3><p>Tender stuffed cabbage rolls, slow-cooked and deeply comforting.</p></div></article>
				<article class="dish-card"><img src="<?php echo feast_asset( 'menu-fattoush.jpg' ); ?>" alt="Fresh colourful fattoush salad" loading="lazy"><div class="dish-card__copy"><h3>Fresh salads</h3><p>Bright, crisp and made to balance every feast.</p></div></article>
				<article class="dish-card"><img src="<?php echo feast_asset( 'menu-warak-enab.jpg' ); ?>" alt="Traditional stuffed vine leaves" loading="lazy"><div class="dish-card__copy"><h3>Warak Enab</h3><p>Stuffed vine leaves, rolled by hand and full of flavour.</p></div></article>
			</div>
			<div class="menu-list">
				<div class="menu-group"><h3>Hearty mains</h3><p>Chicken mansaf</p><p>Malfouf</p><p>Dawood basha</p><p>Seasonal rice dishes</p></div>
				<div class="menu-group"><h3>Salads & sides</h3><p>Fattoush</p><p>Tabouli</p><p>Hummus</p><p>Batata harra</p></div>
				<div class="menu-group"><h3>Bites & extras</h3><p>Kibbeh</p><p>Sambousek</p><p>Stuffed vine leaves</p><p>Fresh wraps</p></div>
			</div>
			<?php endif; ?>
		</div>
	</section>

	<section class="section process">
		<div class="site-wrap">
			<div class="section-heading"><div><p class="eyebrow eyebrow--light"><?php echo esc_html( feast_copy( 'process_eyebrow' ) ); ?></p><h2><?php echo esc_html( feast_copy( 'process_title' ) ); ?></h2></div><p class="lead"><?php echo esc_html( feast_copy( 'process_intro' ) ); ?></p></div>
			<div class="steps">
				<?php for ( $step = 1; $step <= 3; $step++ ) : ?><div class="step"><span class="step__number">0<?php echo esc_html( $step ); ?></span><h3><?php echo esc_html( feast_copy( 'step_' . $step . '_title' ) ); ?></h3><p><?php echo esc_html( feast_copy( 'step_' . $step . '_text' ) ); ?></p></div><?php endfor; ?>
			</div>
		</div>
	</section>

	<section class="section" id="our-story">
		<div class="site-wrap story-grid">
			<div class="story-image"><img src="<?php echo esc_url( $story_image ); ?>" alt="The Feast in the Middle East kitchen team preparing food" loading="lazy"><span class="story-stamp"><?php echo nl2br( esc_html( feast_copy( 'story_stamp' ) ) ); ?></span></div>
			<div class="story-copy"><p class="eyebrow"><?php echo esc_html( feast_copy( 'story_eyebrow' ) ); ?></p><h2><?php echo esc_html( feast_copy( 'story_title' ) ); ?></h2><p class="lead"><?php echo esc_html( feast_copy( 'story_lead' ) ); ?></p><p><?php echo esc_html( feast_copy( 'story_text' ) ); ?></p><a class="button button--outline" href="<?php echo esc_url( feast_resolve_url( feast_copy( 'story_cta_link' ) ) ); ?>"><?php echo esc_html( feast_copy( 'story_cta_label' ) ); ?></a></div>
		</div>
	</section>

	<section class="section section--cream" id="gallery">
		<div class="site-wrap">
			<div class="section-heading"><div><p class="eyebrow"><?php echo esc_html( feast_copy( 'gallery_eyebrow' ) ); ?></p><h2><?php echo esc_html( feast_copy( 'gallery_title' ) ); ?></h2></div><a class="text-link" href="<?php echo esc_url( feast_resolve_url( feast_copy( 'gallery_cta_link' ) ) ); ?>"><?php echo esc_html( feast_copy( 'gallery_cta_label' ) ); ?> →</a></div>
			<div class="gallery-grid">
				<?php if ( ! empty( $cms_gallery ) ) : ?>
					<?php foreach ( $cms_gallery as $gallery_item ) : if ( ! has_post_thumbnail( $gallery_item->ID ) ) { continue; } ?><figure><img src="<?php echo esc_url( get_the_post_thumbnail_url( $gallery_item->ID, 'large' ) ); ?>" alt="<?php echo esc_attr( get_the_title( $gallery_item ) ); ?>" loading="lazy"></figure><?php endforeach; ?>
				<?php else : ?>
				<figure><img src="<?php echo feast_asset( 'catering-selection.jpg' ); ?>" alt="A selection of catered Middle Eastern dishes" loading="lazy"></figure>
				<figure><img src="<?php echo feast_asset( 'menu-wrap.jpg' ); ?>" alt="A freshly prepared Middle Eastern wrap" loading="lazy"></figure>
				<figure><img src="<?php echo feast_asset( 'event-salads.jpg' ); ?>" alt="Colourful salads prepared for an event" loading="lazy"></figure>
				<figure><img src="<?php echo feast_asset( 'hero-event-table.jpg' ); ?>" alt="A catered celebration table" loading="lazy"></figure>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<section class="section enquiry" id="catering-enquiry">
		<div class="site-wrap enquiry-grid">
			<div class="enquiry-copy"><p class="eyebrow"><?php echo esc_html( feast_copy( 'enquiry_eyebrow' ) ); ?></p><h2><?php echo esc_html( feast_copy( 'enquiry_title' ) ); ?></h2><p class="lead"><?php echo esc_html( feast_copy( 'enquiry_intro' ) ); ?></p><div class="contact-list"><a href="tel:<?php echo esc_attr( feast_setting( 'phone_link' ) ); ?>"><?php echo esc_html( feast_copy( 'call_label' ) ); ?> <?php echo esc_html( feast_setting( 'phone_display' ) ); ?></a><span><?php echo nl2br( esc_html( feast_setting( 'address' ) ) ); ?></span></div></div>
			<?php get_template_part( 'template-parts/enquiry-form' ); ?>
		</div>
	</section>
</main>
<?php get_footer(); ?>
