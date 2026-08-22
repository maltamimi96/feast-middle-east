<?php
/**
 * Homepage template.
 *
 * The visual Gutenberg block renders the same coded sections used by the
 * fallback, so editor content and Git-deployed presentation stay separate.
 *
 * @package FeastMiddleEast
 */

get_header();

$home_content = get_post_field( 'post_content', get_queried_object_id() );
if ( $home_content ) {
	echo apply_filters( 'the_content', $home_content ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
} else {
	get_template_part( 'template-parts/homepage-sections' );
}

get_footer();
