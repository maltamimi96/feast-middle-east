<?php
/**
 * Reusable catering enquiry form.
 *
 * @package FeastMiddleEast
 */

$enquiry_status = isset( $_GET['enquiry'] ) ? sanitize_key( wp_unslash( $_GET['enquiry'] ) ) : '';
?>
<form class="form-card" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
	<input type="hidden" name="action" value="feast_catering_enquiry">
	<?php wp_nonce_field( 'feast_catering_enquiry', 'feast_nonce' ); ?>
	<div class="form-honeypot" aria-hidden="true"><label for="website">Leave this blank</label><input id="website" name="website" type="text" tabindex="-1" autocomplete="off"></div>
	<?php if ( 'sent' === $enquiry_status ) : ?><p class="form-status form-status--success" role="status">Thanks! Your catering request has been sent. We’ll be in touch soon.</p><?php elseif ( $enquiry_status ) : ?><p class="form-status form-status--error" role="alert">We couldn’t send that yet. Check the required fields or call us on <?php echo esc_html( feast_setting( 'phone_display' ) ); ?>.</p><?php endif; ?>
	<div class="form-row"><div class="form-field"><label for="name">Your name *</label><input id="name" name="name" type="text" autocomplete="name" required></div><div class="form-field"><label for="phone">Phone number *</label><input id="phone" name="phone" type="tel" autocomplete="tel" required></div></div>
	<div class="form-row"><div class="form-field"><label for="email">Email *</label><input id="email" name="email" type="email" autocomplete="email" required></div><div class="form-field"><label for="event-date">Event date</label><input id="event-date" name="event_date" type="date"></div></div>
	<div class="form-row"><div class="form-field"><label for="guests">Approximate guests</label><input id="guests" name="guests" type="number" min="1" inputmode="numeric" placeholder="e.g. 40"></div><div class="form-field"><label for="event-type">What are you planning?</label><select id="event-type" name="event_type"><option value="">Choose one</option><option>Family gathering</option><option>Wedding or celebration</option><option>Office lunch</option><option>Community event</option><option>Other</option></select></div></div>
	<div class="form-field"><label for="message">Tell us about your feast</label><textarea id="message" name="message" placeholder="Your preferred dishes, venue or anything we should know..."></textarea></div>
	<button class="button button--wide" type="submit">Request my catering quote</button><p class="form-note">No payment is taken here. We’ll contact you to confirm the details and quote.</p>
</form>
