<?php


class Elementor_LNPW_Pay_Block_Widget extends \Elementor\Widget_Base {


	/**
	 * @return string
	 */
	public function get_name() {
		return 'elementor_lnpw_pay_block';
	}

	/**
	 * @return string
	 */
	public function get_title() {
		return 'LP Pay Widget';
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
		//
	}

	/**
	 *
	 */
	protected function render() {
		echo do_shortcode( "[lnpw_pay_block]" );
	}

}