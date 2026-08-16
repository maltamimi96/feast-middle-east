<?php
/**
 * Reusable catering enquiry form.
 *
 * @package FeastMiddleEast
 */

$enquiry_status = isset( $_GET['enquiry'] ) ? sanitize_key( wp_unslash( $_GET['enquiry'] ) ) : '';
$event_options  = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', feast_copy( 'form_type_options' ) ) ) );
?>
<form class="form-card" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
	<input type="hidden" name="action" value="feast_catering_enquiry">
	<?php wp_nonce_field( 'feast_catering_enquiry', 'feast_nonce' ); ?>
	<div class="form-honeypot" aria-hidden="true"><label for="website">Leave this blank</label><input id="website" name="website" type="text" tabindex="-1" autocomplete="off"></div>
	<?php if ( 'sent' === $enquiry_status ) : ?><p class="form-status form-status--success" role="status"><?php echo esc_html( feast_copy( 'form_success' ) ); ?></p><?php elseif ( $enquiry_status ) : ?><p class="form-status form-status--error" role="alert"><?php echo esc_html( feast_copy( 'form_error' ) ); ?> <?php echo esc_html( feast_setting( 'phone_display' ) ); ?>.</p><?php endif; ?>
	<div class="form-row"><div class="form-field"><label for="name"><?php echo esc_html( feast_copy( 'form_name_label' ) ); ?></label><input id="name" name="name" type="text" autocomplete="name" required></div><div class="form-field"><label for="phone"><?php echo esc_html( feast_copy( 'form_phone_label' ) ); ?></label><input id="phone" name="phone" type="tel" autocomplete="tel" required></div></div>
	<div class="form-row"><div class="form-field"><label for="email"><?php echo esc_html( feast_copy( 'form_email_label' ) ); ?></label><input id="email" name="email" type="email" autocomplete="email" required></div><div class="form-field"><label for="event-date"><?php echo esc_html( feast_copy( 'form_date_label' ) ); ?></label><input id="event-date" name="event_date" type="date"></div></div>
	<div class="form-row"><div class="form-field"><label for="guests"><?php echo esc_html( feast_copy( 'form_guests_label' ) ); ?></label><input id="guests" name="guests" type="number" min="1" inputmode="numeric" placeholder="<?php echo esc_attr( feast_copy( 'form_guests_placeholder' ) ); ?>"></div><div class="form-field"><label for="event-type"><?php echo esc_html( feast_copy( 'form_type_label' ) ); ?></label><select id="event-type" name="event_type"><option value=""><?php echo esc_html( feast_copy( 'form_type_placeholder' ) ); ?></option><?php foreach ( $event_options as $event_option ) : ?><option><?php echo esc_html( $event_option ); ?></option><?php endforeach; ?></select></div></div>
	<div class="form-field"><label for="message"><?php echo esc_html( feast_copy( 'form_message_label' ) ); ?></label><textarea id="message" name="message" placeholder="<?php echo esc_attr( feast_copy( 'form_message_placeholder' ) ); ?>"></textarea></div>
	<button class="button button--wide" type="submit"><?php echo esc_html( feast_copy( 'form_submit_label' ) ); ?></button><p class="form-note"><?php echo esc_html( feast_copy( 'form_note' ) ); ?></p>
</form>
