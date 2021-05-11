<?php


class Elementor_LNPW_Start_Video_Widget extends \Elementor\Widget_Base
{


	/**
	 * @return string
	 */
	public function get_name()
	{
		return 'elementor_lnpw_start_video';
	}

	/**
	 * @return string
	 */
	public function get_title()
	{
		return 'LP Pay-per-View Start';
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
				'label' => __('Content', 'plugin-name'),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'pay_view_block',
			[
				'label' => 'Enable payment block',
				'type'  => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'title',
			[
				'label' => 'Title',
				'type'  => \Elementor\Controls_Manager::TEXT,
				'default' => 'Untitled',
			]
		);

		$this->add_control(
			'description',
			[
				'label' => 'Description',
				'type'  => \Elementor\Controls_Manager::TEXT,
				'default' => 'No description',
			]
		);

		$this->add_control(
			'preview',
			[
				'label' => 'Preview',
				'type'  => \Elementor\Controls_Manager::MEDIA,
				'default' =>
				['url' => "plugin_dir_url(__DIR__) . 'public/img/preview.png'"]
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
		$enable_pay_view_block = $settings['pay_view_block'];
		$title = $settings['title'];
		$description = $settings['description'];
		$preview = $settings['preview']['url'];
		$price = $settings['price'];
		$duration = $settings['duration'];
		$duration_type = $settings['duration_type'];
		$currency = $settings['currency'];
		$btc_format = $settings['btc_format'];

		if ($enable_pay_view_block) {
			echo do_shortcode("[lnpw_start_video pay_view_block='true' title='{$title}' description='{$description}' preview='{$preview}' currency='{$currency}' btc_format='{$btc_format}' duration='{$duration}' duration_type='{$duration_type}' price='{$price}']");
		}
	}
}
