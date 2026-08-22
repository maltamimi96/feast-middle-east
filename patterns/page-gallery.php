<?php
/**
 * Title: Editable gallery page
 * Slug: feast-middle-east/page-gallery
 * Categories: feast
 */
$base = esc_url( get_template_directory_uri() . '/assets/images/' );
?>
<!-- wp:group {"className":"feast-native-page section native-page-top","layout":{"type":"constrained"}} --><div class="wp-block-group feast-native-page section native-page-top"><!-- wp:paragraph {"className":"eyebrow"} --><p class="eyebrow">Fresh from Feast</p><!-- /wp:paragraph --><!-- wp:heading {"level":1} --><h1 class="wp-block-heading">Inside our kitchen and events.</h1><!-- /wp:heading --><!-- wp:gallery {"columns":3,"linkTo":"none"} --><figure class="wp-block-gallery has-nested-images columns-3 is-cropped"><!-- wp:image --><figure class="wp-block-image"><img src="<?php echo $base; ?>catering-selection.jpg" alt="Middle Eastern catering selection"/></figure><!-- /wp:image --><!-- wp:image --><figure class="wp-block-image"><img src="<?php echo $base; ?>hero-event-table.jpg" alt="Catered event table"/></figure><!-- /wp:image --><!-- wp:image --><figure class="wp-block-image"><img src="<?php echo $base; ?>event-salads.jpg" alt="Fresh salads"/></figure><!-- /wp:image --><!-- wp:image --><figure class="wp-block-image"><img src="<?php echo $base; ?>menu-malfouf.jpg" alt="Malfouf"/></figure><!-- /wp:image --><!-- wp:image --><figure class="wp-block-image"><img src="<?php echo $base; ?>menu-warak-enab.jpg" alt="Warak enab"/></figure><!-- /wp:image --><!-- wp:image --><figure class="wp-block-image"><img src="<?php echo $base; ?>menu-wrap.jpg" alt="Fresh wrap"/></figure><!-- /wp:image --></figure><!-- /wp:gallery --></div><!-- /wp:group -->
