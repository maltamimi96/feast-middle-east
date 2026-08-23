<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Feast_Core_Enquiry_Widget extends \Elementor\Widget_Base {
	public function get_name() { return 'feast-enquiry'; }
	public function get_title() { return __( 'Feast Catering Enquiry', 'feast-core' ); }
	public function get_icon() { return 'eicon-form-horizontal'; }
	public function get_categories() { return array( 'feast' ); }

	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Content', 'feast-core' ) ) );
		$this->add_control( 'eyebrow', array( 'label' => __( 'Eyebrow', 'feast-core' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Plan your feast' ) );
		$this->add_control( 'title', array( 'label' => __( 'Heading', 'feast-core' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Tell us about your event' ) );
		$this->add_control( 'intro', array( 'label' => __( 'Introduction', 'feast-core' ), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Share the date, guest count and style of event. We’ll help shape the right menu.' ) );
		$this->end_controls_section();
		$this->start_controls_section( 'style', array( 'label' => __( 'Style', 'feast-core' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ) );
		$this->add_control( 'background', array( 'label' => __( 'Form background', 'feast-core' ), 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#fffdf8', 'selectors' => array( '{{WRAPPER}} .form-card' => 'background: {{VALUE}};' ) ) );
		$this->add_responsive_control( 'gap', array( 'label' => __( 'Column gap', 'feast-core' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => array( 'px' => array( 'min' => 0, 'max' => 160 ) ), 'selectors' => array( '{{WRAPPER}} .enquiry-grid' => 'gap: {{SIZE}}{{UNIT}};' ) ) );
		$this->add_responsive_control( 'padding', array( 'label' => __( 'Form padding', 'feast-core' ), 'type' => \Elementor\Controls_Manager::DIMENSIONS, 'selectors' => array( '{{WRAPPER}} .form-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ) ) );
		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		?><div class="enquiry-grid"><div class="enquiry-copy"><p class="eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></p><h2><?php echo esc_html( $s['title'] ); ?></h2><p class="lead"><?php echo esc_html( $s['intro'] ); ?></p><?php if ( function_exists( 'feast_setting' ) ) : ?><div class="contact-list"><a href="tel:<?php echo esc_attr( feast_setting( 'phone_link' ) ); ?>"><?php echo esc_html( feast_setting( 'phone_display' ) ); ?></a><span><?php echo nl2br( esc_html( feast_setting( 'address' ) ) ); ?></span></div><?php endif; ?></div><?php if ( function_exists( 'feast_enquiry_form_shortcode' ) ) { echo feast_enquiry_form_shortcode(); } elseif ( locate_template( 'template-parts/enquiry-form.php' ) ) { get_template_part( 'template-parts/enquiry-form' ); } else { echo '<p>The Feast enquiry form template is unavailable.</p>'; } ?></div><?php
	}
}

