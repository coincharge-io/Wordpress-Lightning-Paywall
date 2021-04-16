<?php


class Elementor_LNPW_Start_Video_Widget extends \Elementor\Widget_Base {


	/**
	 * @return string
	 */
	public function get_name() {
		return 'elementor_lnpw_start_video';
	}

	/**
	 * @return string
	 */
	public function get_title() {
		return 'LP Start Paid Video Content';
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
			]
		);

		$this->end_controls_section();

	}

	/**
	 *
	 */
	protected function render() {

		$settings         = $this->get_settings_for_display();
		$enable_pay_view_block = $settings[ 'pay_view_block' ];
        $title = $settings[ 'title' ];
        $description = $settings[ 'description' ];
        $preview = $settings[ 'preview' ]['url'];
		
		if($enable_pay_view_block){
			echo do_shortcode( "[lnpw_start_video pay_view_block='true' title='{$title}' description='{$description}' preview='{$preview}']" );
		}

	}

}