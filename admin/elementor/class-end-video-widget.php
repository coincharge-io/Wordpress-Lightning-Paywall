<?php


class Elementor_LNPW_End_Video_Widget extends \Elementor\Widget_Base
{


	/**
	 * @return string
	 */
	public function get_name()
	{
		return 'elementor_lnpw_end_video';
	}

	/**
	 * @return string
	 */
	public function get_title()
	{
		return 'LP Pay-per-View End';
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
		//
	}

	/**
	 *
	 */
	protected function render()
	{
		echo do_shortcode("[lnpw_end_video]");
	}
}
