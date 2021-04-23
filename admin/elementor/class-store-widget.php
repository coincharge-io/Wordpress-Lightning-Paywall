<?php


class Elementor_LNPW_Store_Widget extends \Elementor\Widget_Base
{


	/**
	 * @return string
	 */
	public function get_name()
	{
		return 'elementor_lnpw_video_catalog';
	}

	/**
	 * @return string
	 */
	public function get_title()
	{
		return 'LP Video Catalog';
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
		echo do_shortcode("[lnpw_video_catalog]");
	}
}
