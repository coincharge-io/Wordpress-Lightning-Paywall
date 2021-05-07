<?php


class Elementor_LNPW_Start_Content_Widget extends \Elementor\Widget_Base
{


	/**
	 * @return string
	 */
	public function get_name()
	{
		return 'elementor_lnpw_start_content';
	}

	/**
	 * @return string
	 */
	public function get_title()
	{
		return 'LP Pay-per-Post Start';
	}

	/**
	 * @return string
	 */
	public function get_icon()
	{
		return 'fa fa-btc';
	}

	/**
	 * @return string[]
	 */
	public function get_categories()
	{
		return ['general'];
	}

	/**
	 *
	 */
	protected function _register_controls()
	{

		$this->start_controls_section(
			'content_section',
			[
				'label' => __('Content', 'lightning-paywall'),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'pay_block',
			[
				'label' => 'Enable payment block',
				'type'  => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'currency',
			[
				'label' => 'Currency',
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'Default' => '',
					'SATS' => 'SATS',
					'EUR' => 'EUR',
					'USD' => 'USD',
				],
				'default' => 'Default',
			]
		);
		$this->add_control(
			'btc_format',
			[
				'label' => 'BTC format',
				'type' => \Elementor\Controls_Manager::SELECT,
				'condition'	=> [
					'currency'	=> 'SATS'
				],
				'options' => [
					'Default' => '',
					'SATS' => 'SATS',
					'BTC'  => 'BTC',
				],
				'default' => 'Default',
			]
		);

		$this->add_control(
			'price',
			[
				'label' => 'Price',
				'type'  => \Elementor\Controls_Manager::NUMBER,
			]
		);

		$this->add_control(
			'duration_type',
			[
				'label' => 'Duration type',
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'default' => '',
					'minute' => 'minute',
					'hour' => 'hour',
					'week' => 'week',
					'month' => 'month',
					'year' => 'year',
					'onetime' => 'onetime',
					'unlimited' => 'unlimited'
				],
				'default' => 'default',
			]
		);

		$this->add_control(
			'duration',
			[
				'label' => 'Duration',
				'type'  => \Elementor\Controls_Manager::NUMBER,
			]
		);

		$this->end_controls_section();
	}

	/**
	 *
	 */
	protected function render()
	{

		$settings         = $this->get_settings_for_display();
		$enable_pay_block = $settings['pay_block'];
		$price = $settings['price'];
		$duration = $settings['duration'];
		$duration_type = $settings['duration_type'];
		$currency = $settings['currency'];
		$btc_format = $settings['btc_format'];

		if ($enable_pay_block) {
			echo do_shortcode("[lnpw_start_content pay_block='true' currency='{$currency}' btc_format='{$btc_format}' duration='{$duration}' duration_type='{$duration_type}' price='{$price}']");
		}
	}
}
