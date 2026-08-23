<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Feast_Core_Hero_Widget extends \Elementor\Widget_Base {
	public function get_name() { return 'feast-hero'; }
	public function get_title() { return __( 'Feast Hero Slider', 'feast-core' ); }
	public function get_icon() { return 'eicon-slides'; }
	public function get_categories() { return array( 'feast' ); }
	public function get_keywords() { return array( 'feast', 'hero', 'slider', 'offer', 'catering' ); }

	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Content', 'feast-core' ) ) );
		$this->add_control( 'limit', array( 'label' => __( 'Number of offers', 'feast-core' ), 'type' => \Elementor\Controls_Manager::NUMBER, 'min' => 1, 'max' => 12, 'default' => 5 ) );
		$this->add_control( 'dots', array( 'label' => __( 'Show dots', 'feast-core' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => 'yes' ) );
		$this->add_control( 'arrows', array( 'label' => __( 'Show arrows', 'feast-core' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => 'yes' ) );
		$this->end_controls_section();

		$this->start_controls_section( 'layout', array( 'label' => __( 'Layout', 'feast-core' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ) );
		$this->add_responsive_control( 'height', array( 'label' => __( 'Minimum height', 'feast-core' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'size_units' => array( 'px', 'vh', 'svh' ), 'range' => array( 'px' => array( 'min' => 360, 'max' => 1100 ), 'vh' => array( 'min' => 40, 'max' => 100 ), 'svh' => array( 'min' => 40, 'max' => 100 ) ), 'default' => array( 'unit' => 'vh', 'size' => 70 ), 'selectors' => array( '{{WRAPPER}} .hero' => 'min-height: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};' ) ) );
		$this->add_responsive_control( 'content_width', array( 'label' => __( 'Text width', 'feast-core' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'size_units' => array( 'px', '%' ), 'range' => array( 'px' => array( 'min' => 320, 'max' => 1300 ), '%' => array( 'min' => 30, 'max' => 100 ) ), 'default' => array( 'unit' => 'px', 'size' => 980 ), 'selectors' => array( '{{WRAPPER}} .hero-copy' => 'max-width: {{SIZE}}{{UNIT}};' ) ) );
		$this->add_responsive_control( 'padding', array( 'label' => __( 'Content padding', 'feast-core' ), 'type' => \Elementor\Controls_Manager::DIMENSIONS, 'size_units' => array( 'px', '%', 'em' ), 'selectors' => array( '{{WRAPPER}} .hero-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ) ) );
		$this->end_controls_section();

		$this->start_controls_section( 'type', array( 'label' => __( 'Typography & buttons', 'feast-core' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ) );
		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array( 'name' => 'title_type', 'selector' => '{{WRAPPER}} .hero-copy h1' ) );
		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array( 'name' => 'body_type', 'selector' => '{{WRAPPER}} .hero-copy p:not(.eyebrow)' ) );
		$this->add_control( 'button_radius', array( 'label' => __( 'Button radius', 'feast-core' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => array( 'px' => array( 'min' => 0, 'max' => 50 ) ), 'selectors' => array( '{{WRAPPER}} .button' => 'border-radius: {{SIZE}}{{UNIT}};' ) ) );
		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		$offers = function_exists( 'feast_content_items' ) ? feast_content_items( 'feast_offer', absint( $s['limit'] ) ) : get_posts( array( 'post_type' => 'feast_offer', 'post_status' => 'publish', 'posts_per_page' => absint( $s['limit'] ), 'orderby' => 'menu_order', 'order' => 'ASC' ) );
		if ( ! $offers ) { echo '<p>Edit Hero Offers in Feast CMS to populate this slider.</p>'; return; }
		?><section class="hero" aria-label="Featured catering offers"><div class="hero-slides"><?php foreach ( $offers as $index => $offer ) :
			$style = sprintf( '--slide-overlay:%s;--slide-opacity:%d%%;--slide-text:%s;--slide-accent:%s;--slide-primary:%s;--slide-primary-text:%s;--slide-second:%s;--slide-second-text:%s;', get_post_meta( $offer->ID, '_feast_overlay_color', true ) ?: '#071309', absint( get_post_meta( $offer->ID, '_feast_overlay_opacity', true ) ?: 75 ), get_post_meta( $offer->ID, '_feast_text_color', true ) ?: '#fffdf8', get_post_meta( $offer->ID, '_feast_accent_color', true ) ?: '#edd7ad', get_post_meta( $offer->ID, '_feast_primary_color', true ) ?: '#ffffff', get_post_meta( $offer->ID, '_feast_primary_text', true ) ?: '#173f32', get_post_meta( $offer->ID, '_feast_second_color', true ) ?: '#173f32', get_post_meta( $offer->ID, '_feast_second_text', true ) ?: '#ffffff' );
			?><article class="hero-slide<?php echo 0 === $index ? ' is-active' : ''; ?>" style="<?php echo esc_attr( $style ); ?>"><?php if ( has_post_thumbnail( $offer->ID ) ) : ?><?php echo get_the_post_thumbnail( $offer->ID, 'full', array( 'class' => 'hero-slide__image', 'alt' => '' ) ); ?><?php endif; ?><div class="site-wrap hero-content"><div class="hero-copy"><p class="eyebrow"><?php echo esc_html( get_post_meta( $offer->ID, '_feast_eyebrow', true ) ); ?></p><h1><?php echo esc_html( get_the_title( $offer ) ); ?></h1><p><?php echo esc_html( $offer->post_excerpt ); ?></p><div class="hero-actions"><?php $p_label = get_post_meta( $offer->ID, '_feast_primary_label', true ); if ( $p_label ) : ?><a class="button button--light" href="<?php echo esc_url( function_exists( 'feast_resolve_url' ) ? feast_resolve_url( get_post_meta( $offer->ID, '_feast_primary_link', true ) ) : get_post_meta( $offer->ID, '_feast_primary_link', true ) ); ?>"><?php echo esc_html( $p_label ); ?></a><?php endif; ?><?php $s_label = get_post_meta( $offer->ID, '_feast_second_label', true ); if ( $s_label ) : ?><a class="button" href="<?php echo esc_url( function_exists( 'feast_resolve_url' ) ? feast_resolve_url( get_post_meta( $offer->ID, '_feast_second_link', true ) ) : get_post_meta( $offer->ID, '_feast_second_link', true ) ); ?>"><?php echo esc_html( $s_label ); ?></a><?php endif; ?></div><?php if ( get_post_meta( $offer->ID, '_feast_note', true ) ) : ?><div class="hero-note"><?php echo esc_html( get_post_meta( $offer->ID, '_feast_note', true ) ); ?></div><?php endif; ?></div></div></article><?php endforeach; ?></div>
		<?php if ( count( $offers ) > 1 && ( 'yes' === $s['dots'] || 'yes' === $s['arrows'] ) ) : ?><div class="slider-controls"><?php if ( 'yes' === $s['dots'] ) : ?><div class="slider-dots"><?php foreach ( $offers as $index => $offer ) : ?><button class="slider-dot<?php echo 0 === $index ? ' is-active' : ''; ?>" aria-label="Show offer <?php echo esc_attr( $index + 1 ); ?>"></button><?php endforeach; ?></div><?php endif; ?><?php if ( 'yes' === $s['arrows'] ) : ?><div class="slider-arrows"><button class="slider-arrow slider-prev" aria-label="Previous offer">←</button><button class="slider-arrow slider-next" aria-label="Next offer">→</button></div><?php endif; ?></div><?php endif; ?></section><?php
	}
}

