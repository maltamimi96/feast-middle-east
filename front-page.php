<?php get_header(); ?>
<main id="main-content">
	<section class="hero" aria-label="Featured catering offers">
		<div class="hero-slides" data-slider>
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
					<div class="hero-actions"><a class="button button--light" href="#catering-enquiry">Cater my event</a><a class="button" href="tel:+61407495908">Call 0407 495 908</a></div>
					<div class="hero-note">Pickup and delivery options available</div>
				</div></div>
			</article>
		</div>
		<div class="slider-controls">
			<div class="slider-dots" aria-label="Choose an offer">
				<button class="slider-dot is-active" type="button" data-slide-to="0" aria-label="Show catering offer 1" aria-current="true"></button>
				<button class="slider-dot" type="button" data-slide-to="1" aria-label="Show catering offer 2"></button>
				<button class="slider-dot" type="button" data-slide-to="2" aria-label="Show catering offer 3"></button>
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
				<article class="bundle-card"><span class="bundle-tag">Warm & generous</span><h3>Family Table</h3><span class="bundle-for">Ideal for 10–25 guests</span><ul><li>Your choice of hearty main dishes</li><li>Fresh salads and traditional sides</li><li>Share-style trays, ready for the table</li><li>Custom quote based on your menu</li></ul><a class="text-link" href="#catering-enquiry" data-bundle="Family Table">Ask about this feast →</a></article>
				<article class="bundle-card bundle-card--featured"><span class="bundle-tag">Most popular</span><h3>Celebration Feast</h3><span class="bundle-for">Ideal for 25–100+ guests</span><ul><li>A generous mix of mains and favourites</li><li>Salads, dips, sides and finger food</li><li>Designed for weddings and big occasions</li><li>Custom quote built for your guest count</li></ul><a class="text-link" href="#catering-enquiry" data-bundle="Celebration Feast">Plan my celebration →</a></article>
				<article class="bundle-card"><span class="bundle-tag">Easy crowd-pleaser</span><h3>Office Lunch</h3><span class="bundle-for">Ideal for teams of 10+</span><ul><li>Easy-to-serve hot mains or wraps</li><li>Fresh salads, dips and sides</li><li>Flexible options for team preferences</li><li>Custom quote for pickup or delivery</li></ul><a class="text-link" href="#catering-enquiry" data-bundle="Office Lunch">Feed the team →</a></article>
			</div>
		</div>
	</section>

	<section class="section" id="menu">
		<div class="site-wrap">
			<div class="section-heading"><div><p class="eyebrow">From our kitchen</p><h2>The dishes people come back for.</h2></div><p class="lead">Traditional Middle Eastern flavours, generous portions and plenty made for sharing.</p></div>
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
			<div class="section-heading"><div><p class="eyebrow">Recent feasts</p><h2>Made to be shared.</h2></div><a class="text-link" href="https://www.instagram.com/feast_in_the_middle_east/" target="_blank" rel="noopener">See more on Instagram →</a></div>
			<div class="gallery-grid">
				<figure><img src="<?php echo feast_asset( 'catering-selection.jpg' ); ?>" alt="A selection of catered Middle Eastern dishes" loading="lazy"></figure>
				<figure><img src="<?php echo feast_asset( 'menu-wrap.jpg' ); ?>" alt="A freshly prepared Middle Eastern wrap" loading="lazy"></figure>
				<figure><img src="<?php echo feast_asset( 'event-salads.jpg' ); ?>" alt="Colourful salads prepared for an event" loading="lazy"></figure>
				<figure><img src="<?php echo feast_asset( 'hero-event-table.jpg' ); ?>" alt="A catered celebration table" loading="lazy"></figure>
			</div>
		</div>
	</section>

	<section class="section enquiry" id="catering-enquiry">
		<div class="site-wrap enquiry-grid">
			<div class="enquiry-copy"><p class="eyebrow">Start your catering order</p><h2>Let’s put a feast on the table.</h2><p class="lead">Send us the basics and we’ll get in touch to discuss the menu, quantities and a custom quote.</p><div class="contact-list"><a href="tel:+61407495908">Call 0407 495 908</a><span>Inside HAWA Food Spot<br>43 South St, Granville NSW</span></div></div>
			<form class="form-card" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
				<input type="hidden" name="action" value="feast_catering_enquiry">
				<?php wp_nonce_field( 'feast_catering_enquiry', 'feast_nonce' ); ?>
				<div class="form-honeypot" aria-hidden="true"><label for="website">Leave this blank</label><input id="website" name="website" type="text" tabindex="-1" autocomplete="off"></div>
				<?php if ( isset( $_GET['enquiry'] ) && 'sent' === $_GET['enquiry'] ) : ?><p class="form-status form-status--success" role="status">Thanks! Your catering request has been sent. We’ll be in touch soon.</p><?php elseif ( isset( $_GET['enquiry'] ) ) : ?><p class="form-status form-status--error" role="alert">We couldn’t send that yet. Check the required fields or call us on 0407 495 908.</p><?php endif; ?>
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
