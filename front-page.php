<?php
get_header();
$cms_offers  = feast_content_items( 'feast_offer' );
$cms_bundles = feast_content_items( 'feast_bundle' );
$cms_dishes  = feast_content_items( 'feast_dish' );
$cms_gallery = feast_content_items( 'feast_gallery', 6 );
$hero_count  = ! empty( $cms_offers ) ? count( $cms_offers ) : 3;
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
					?>
					<article class="hero-slide<?php echo 0 === $index ? ' is-active' : ''; ?>" data-slide aria-hidden="<?php echo 0 === $index ? 'false' : 'true'; ?>">
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

	<div class="trust-bar"><div class="site-wrap trust-grid">
		<div class="trust-item"><strong>Made fresh</strong><span>From our Granville kitchen</span></div>
		<div class="trust-item"><strong>Custom menus</strong><span>Built around your event</span></div>
		<div class="trust-item"><strong>10–100+ guests</strong><span>Small gatherings to big days</span></div>
		<div class="trust-item"><strong>Pickup or delivery</strong><span>Ask about your location</span></div>
	</div></div>

	<section class="section section--cream" id="catering">
		<div class="site-wrap">
			<div class="section-heading"><div><p class="eyebrow">Catering made simple</p><h2>Choose your kind of feast.</h2></div><p class="lead">Start with one of our popular catering styles, then we’ll tailor the dishes and quantities to your guests.</p></div>
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
							<?php if ( ! empty( $features ) ) : ?><ul><?php foreach ( $features as $feature ) : ?><li><?php echo esc_html( $feature ); ?></li><?php endforeach; ?></ul><?php endif; ?>
							<a class="text-link" href="#catering-enquiry" data-bundle="<?php echo esc_attr( get_the_title( $bundle ) ); ?>"><?php echo esc_html( get_post_meta( $bundle->ID, '_feast_cta_label', true ) ? get_post_meta( $bundle->ID, '_feast_cta_label', true ) : 'Ask about this package' ); ?> →</a>
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

	<section class="section" id="menu">
		<div class="site-wrap">
			<div class="section-heading"><div><p class="eyebrow">From our kitchen</p><h2>The dishes people come back for.</h2></div><p class="lead">Traditional Middle Eastern flavours, generous portions and plenty made for sharing.</p></div>
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
					<?php foreach ( $showcase_dishes as $dish ) : ?><article class="dish-card"><img src="<?php echo esc_url( get_the_post_thumbnail_url( $dish->ID, 'large' ) ); ?>" alt="<?php echo esc_attr( get_the_title( $dish ) ); ?>" loading="lazy"><div class="dish-card__copy"><h3><?php echo esc_html( get_the_title( $dish ) ); ?></h3><p><?php echo esc_html( $dish->post_excerpt ); ?></p></div></article><?php endforeach; ?>
				</div><?php endif; ?>
				<div class="menu-list">
					<?php
					$category_labels = array( 'mains' => 'Hearty mains', 'salads' => 'Salads & sides', 'bites' => 'Bites & extras' );
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
						<div class="menu-group"><h3><?php echo esc_html( $category_label ); ?></h3><?php foreach ( $category_dishes as $dish ) : ?><p><?php echo esc_html( get_the_title( $dish ) ); ?></p><?php endforeach; ?></div>
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
			<div class="section-heading"><div><p class="eyebrow eyebrow--light">How it works</p><h2>From your idea to their plates.</h2></div><p class="lead">No complicated ordering. Just tell us what you’re planning and we’ll help take care of the food.</p></div>
			<div class="steps">
				<div class="step"><span class="step__number">01</span><h3>Tell us about the event</h3><p>Share your date, guest count, event style and any dishes you already have in mind.</p></div>
				<div class="step"><span class="step__number">02</span><h3>We build your menu</h3><p>We’ll recommend the right mix and quantities, then send you a custom quote.</p></div>
				<div class="step"><span class="step__number">03</span><h3>We prepare the feast</h3><p>Your food is freshly prepared and organised for pickup or an agreed delivery.</p></div>
			</div>
		</div>
	</section>

	<section class="section" id="our-story">
		<div class="site-wrap story-grid">
			<div class="story-image"><img src="<?php echo feast_asset( 'owner-kitchen.jpg' ); ?>" alt="The Feast in the Middle East kitchen team preparing food" loading="lazy"><span class="story-stamp">Made with<br>love in<br>Granville</span></div>
			<div class="story-copy"><p class="eyebrow">Our table is your table</p><h2>Food that feels like home.</h2><p class="lead">Feast in the Middle East is built around the food we love to cook and share: generous, traditional dishes that bring people together.</p><p>Whether you’re feeding the family or celebrating with a room full of people, every order gets the same care from our Granville kitchen.</p><a class="button button--outline" href="#catering-enquiry">Let’s plan your feast</a></div>
		</div>
	</section>

	<section class="section section--cream" id="gallery">
		<div class="site-wrap">
			<div class="section-heading"><div><p class="eyebrow">Recent feasts</p><h2>Made to be shared.</h2></div><a class="text-link" href="<?php echo esc_url( feast_setting( 'instagram' ) ); ?>" target="_blank" rel="noopener">See more on Instagram →</a></div>
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
			<div class="enquiry-copy"><p class="eyebrow">Start your catering order</p><h2>Let’s put a feast on the table.</h2><p class="lead">Send us the basics and we’ll get in touch to discuss the menu, quantities and a custom quote.</p><div class="contact-list"><a href="tel:<?php echo esc_attr( feast_setting( 'phone_link' ) ); ?>">Call <?php echo esc_html( feast_setting( 'phone_display' ) ); ?></a><span><?php echo nl2br( esc_html( feast_setting( 'address' ) ) ); ?></span></div></div>
			<form class="form-card" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
				<input type="hidden" name="action" value="feast_catering_enquiry">
				<?php wp_nonce_field( 'feast_catering_enquiry', 'feast_nonce' ); ?>
				<div class="form-honeypot" aria-hidden="true"><label for="website">Leave this blank</label><input id="website" name="website" type="text" tabindex="-1" autocomplete="off"></div>
				<?php $enquiry_status = isset( $_GET['enquiry'] ) ? sanitize_key( wp_unslash( $_GET['enquiry'] ) ) : ''; if ( 'sent' === $enquiry_status ) : ?><p class="form-status form-status--success" role="status">Thanks! Your catering request has been sent. We’ll be in touch soon.</p><?php elseif ( $enquiry_status ) : ?><p class="form-status form-status--error" role="alert">We couldn’t send that yet. Check the required fields or call us on <?php echo esc_html( feast_setting( 'phone_display' ) ); ?>.</p><?php endif; ?>
				<div class="form-row"><div class="form-field"><label for="name">Your name *</label><input id="name" name="name" type="text" autocomplete="name" required></div><div class="form-field"><label for="phone">Phone number *</label><input id="phone" name="phone" type="tel" autocomplete="tel" required></div></div>
				<div class="form-row"><div class="form-field"><label for="email">Email *</label><input id="email" name="email" type="email" autocomplete="email" required></div><div class="form-field"><label for="event-date">Event date</label><input id="event-date" name="event_date" type="date"></div></div>
				<div class="form-row"><div class="form-field"><label for="guests">Approximate guests</label><input id="guests" name="guests" type="number" min="1" inputmode="numeric" placeholder="e.g. 40"></div><div class="form-field"><label for="event-type">What are you planning?</label><select id="event-type" name="event_type"><option value="">Choose one</option><option>Family gathering</option><option>Wedding or celebration</option><option>Office lunch</option><option>Community event</option><option>Other</option></select></div></div>
				<div class="form-field"><label for="message">Tell us about your feast</label><textarea id="message" name="message" placeholder="Your preferred dishes, venue or anything we should know..."></textarea></div>
				<button class="button button--wide" type="submit">Request my catering quote</button><p class="form-note">No payment is taken here. We’ll contact you to confirm the details and quote.</p>
			</form>
		</div>
	</section>
</main>
<?php get_footer(); ?>
