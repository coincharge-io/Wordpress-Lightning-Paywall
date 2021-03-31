<?php


class Elementor_LNPW_Start_Content_Widget extends \Elementor\Widget_Base {


	/**
	 * @return string
	 */
	public function get_name() {
		return 'elementor_lnpw_start_content';
	}

	/**
	 * @return string
	 */
	public function get_title() {
		return 'LP Start Paid Text Content';
	}

	/**
	 * @return string
	 */
	public function get_icon() {
		return 'fa fa-code';
	}

	/**
	 * @return string[]
	 */
	public function get_categories() {
		return [ 'general' ];
	}

	/**
	 *
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'plugin-name' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'pay_block',
			[
				'label' => 'Enable payment block',
				'type'  => \Elementor\Controls_Manager::SWITCHER,
			]
		);

		$this->end_controls_section();

	}

	/**
	 *
	 */
	protected function render() {

		$settings         = $this->get_settings_for_display();
		$enable_pay_block = $settings[ 'pay_block' ];

		echo do_shortcode( "[lnpw_start_content pay_block='{$enable_pay_block}']" );

	}

}