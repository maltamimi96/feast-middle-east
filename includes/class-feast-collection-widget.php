<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Feast_Core_Collection_Widget extends \Elementor\Widget_Base {
	public function get_name() { return 'feast-collection'; }
	public function get_title() { return __( 'Feast CMS Collection', 'feast-core' ); }
	public function get_icon() { return 'eicon-posts-grid'; }
	public function get_categories() { return array( 'feast' ); }
	public function get_keywords() { return array( 'menu', 'dish', 'package', 'gallery', 'faq', 'cms' ); }

	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Content', 'feast-core' ) ) );
		$this->add_control( 'source', array( 'label' => __( 'CMS content', 'feast-core' ), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'feast_dish', 'options' => array( 'feast_dish' => 'Menu dishes', 'feast_bundle' => 'Catering packages', 'feast_gallery' => 'Gallery images', 'feast_faq' => 'FAQs' ) ) );
		$this->add_control( 'limit', array( 'label' => __( 'Maximum items', 'feast-core' ), 'type' => \Elementor\Controls_Manager::NUMBER, 'min' => 1, 'max' => 100, 'default' => 12 ) );
		$this->add_control( 'category', array( 'label' => __( 'Dish category', 'feast-core' ), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => '', 'options' => array( '' => 'All categories', 'mains' => 'Mains', 'salads' => 'Salads', 'bites' => 'Bites' ), 'condition' => array( 'source' => 'feast_dish' ) ) );
		$this->add_control( 'show_excerpt', array( 'label' => __( 'Show description', 'feast-core' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => 'yes', 'condition' => array( 'source!' => array( 'feast_gallery', 'feast_faq' ) ) ) );
		$this->add_control( 'show_price', array( 'label' => __( 'Show price', 'feast-core' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => 'yes', 'condition' => array( 'source' => array( 'feast_dish', 'feast_bundle' ) ) ) );
		$this->end_controls_section();

		$this->start_controls_section( 'grid', array( 'label' => __( 'Grid', 'feast-core' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ) );
		$this->add_responsive_control( 'columns', array( 'label' => __( 'Columns', 'feast-core' ), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => '3', 'tablet_default' => '2', 'mobile_default' => '1', 'options' => array( '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5' ), 'selectors' => array( '{{WRAPPER}} .feast-cms-grid' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));' ) ) );
		$this->add_responsive_control( 'gap', array( 'label' => __( 'Gap', 'feast-core' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => array( 'px' => array( 'min' => 0, 'max' => 100 ) ), 'default' => array( 'size' => 20 ), 'selectors' => array( '{{WRAPPER}} .feast-cms-grid' => 'gap: {{SIZE}}{{UNIT}};' ) ) );
		$this->end_controls_section();

		$this->start_controls_section( 'card', array( 'label' => __( 'Cards', 'feast-core' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ) );
		$this->add_control( 'background', array( 'label' => __( 'Background', 'feast-core' ), 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#fffdf8', 'selectors' => array( '{{WRAPPER}} .feast-cms-card' => 'background: {{VALUE}};' ) ) );
		$this->add_control( 'text_color', array( 'label' => __( 'Text colour', 'feast-core' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .feast-cms-card' => 'color: {{VALUE}};' ) ) );
		$this->add_responsive_control( 'card_padding', array( 'label' => __( 'Text padding', 'feast-core' ), 'type' => \Elementor\Controls_Manager::DIMENSIONS, 'default' => array( 'top' => 28, 'right' => 28, 'bottom' => 28, 'left' => 28, 'unit' => 'px' ), 'selectors' => array( '{{WRAPPER}} .feast-cms-card__body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ) ) );
		$this->add_control( 'radius', array( 'label' => __( 'Corner radius', 'feast-core' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => array( 'px' => array( 'min' => 0, 'max' => 60 ) ), 'default' => array( 'size' => 10 ), 'selectors' => array( '{{WRAPPER}} .feast-cms-card' => 'border-radius: {{SIZE}}{{UNIT}};' ) ) );
		$this->add_group_control( \Elementor\Group_Control_Box_Shadow::get_type(), array( 'name' => 'shadow', 'selector' => '{{WRAPPER}} .feast-cms-card' ) );
		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array( 'name' => 'title_type', 'selector' => '{{WRAPPER}} .feast-cms-card h3' ) );
		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		$meta_query = array();
		if ( 'feast_dish' === $s['source'] && $s['category'] ) { $meta_query[] = array( 'key' => '_feast_category', 'value' => $s['category'] ); }
		$items = function_exists( 'feast_content_items' ) ? feast_content_items( $s['source'], absint( $s['limit'] ), $meta_query ) : get_posts( array( 'post_type' => $s['source'], 'post_status' => 'publish', 'posts_per_page' => absint( $s['limit'] ), 'orderby' => 'menu_order', 'order' => 'ASC', 'meta_query' => $meta_query ) );
		if ( ! $items ) { echo '<p>No published CMS items match these settings.</p>'; return; }
		if ( 'feast_faq' === $s['source'] ) { ?><div class="faq-list feast-cms-faqs"><?php foreach ( $items as $item ) : ?><details><summary><?php echo esc_html( get_the_title( $item ) ); ?></summary><div><?php echo wp_kses_post( wpautop( $item->post_content ) ); ?></div></details><?php endforeach; ?></div><?php return; }
		?><div class="feast-cms-grid feast-cms-grid--<?php echo esc_attr( $s['source'] ); ?>"><?php foreach ( $items as $item ) : ?><article class="feast-cms-card"><?php if ( has_post_thumbnail( $item->ID ) ) : ?><div class="feast-cms-card__image"><?php echo get_the_post_thumbnail( $item->ID, 'large', array( 'loading' => 'lazy' ) ); ?></div><?php endif; ?><?php if ( 'feast_gallery' !== $s['source'] ) : ?><div class="feast-cms-card__body"><div class="feast-cms-card__title"><h3><?php echo esc_html( get_the_title( $item ) ); ?></h3><?php if ( 'yes' === $s['show_price'] && get_post_meta( $item->ID, '_feast_price', true ) ) : ?><strong><?php echo esc_html( get_post_meta( $item->ID, '_feast_price', true ) ); ?></strong><?php endif; ?></div><?php if ( 'yes' === $s['show_excerpt'] && $item->post_excerpt ) : ?><p><?php echo esc_html( $item->post_excerpt ); ?></p><?php endif; ?><?php if ( 'feast_dish' === $s['source'] && get_post_meta( $item->ID, '_feast_dietary', true ) ) : ?><small><?php echo esc_html( get_post_meta( $item->ID, '_feast_dietary', true ) ); ?></small><?php endif; ?><?php if ( 'feast_bundle' === $s['source'] ) : $features = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', get_post_meta( $item->ID, '_feast_features', true ) ) ) ); if ( $features ) : ?><ul><?php foreach ( $features as $feature ) : ?><li><?php echo esc_html( $feature ); ?></li><?php endforeach; ?></ul><?php endif; endif; ?></div><?php endif; ?></article><?php endforeach; ?></div><?php
	}
}

