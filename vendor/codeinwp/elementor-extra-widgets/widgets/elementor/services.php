<?php
/**
 * Services widget for Elementor builder
 *
 * @link       https://themeisle.com
 * @since      1.0.0
 *
 * @package    ThemeIsle\ElementorExtraWidgets
 */
namespace ThemeIsle\ElementorExtraWidgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // End if().

/**
 * Class Services
 *
 * @package ThemeIsle\ElementorExtraWidgets
 */
class Services extends \Elementor\Widget_Base {

	/**
	 * Widget name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'obfx-services';
	}

	/**
	 * Widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Services', 'elementor-addon-widgets' );
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fa fa-diamond';
	}


	/**
	 * Widget Category
	 *
	 * @return array
	 */
	public function get_categories() {
		$category_args = apply_filters( 'elementor_extra_widgets_category_args', array() );
		$slug = isset( $category_args['slug'] ) ?  $category_args['slug'] : 'obfx-elementor-widgets';
		return [ $slug ];
	}

	/**
	 * Register Elementor Controls
	 */
	protected function _register_controls() {
		$this->services_content();
		$this->style_icon();
		$this->style_grid_options();
	}

	/**
	 * Content controls
	 */
	private function services_content() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Services', 'elementor-addon-widgets' ),
			]
		);

		$this->add_control(
			'services_list',
			[
				'label'       => __( 'Services', 'elementor-addon-widgets' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'default'     => [
					[
						'title' => __( 'Award-Winning​', 'elementor-addon-widgets' ),
						'text'  => __( 'Add some text here to describe your services to the page visitors.​', 'elementor-addon-widgets' ),
						'icon'  => 'fa fa-trophy',
						'color' => '#333333',
						'type' => 'icon',
					],
					[
						'title' => __( 'Professional​', 'elementor-addon-widgets' ),
						'text'  => __( 'Add some text here to describe your services to the page visitors.​', 'elementor-addon-widgets' ),
						'icon'  => 'fa fa-suitcase',
						'color' => '#333333',
						'type' => 'icon',
					],
					[
						'title' => __( 'Consulting​', 'elementor-addon-widgets' ),
						'text'  => __( 'Add some text here to describe your services to the page visitors.​', 'elementor-addon-widgets' ),
						'icon'  => 'fa fa-handshake-o',
						'color' => '#333333',
						'type' => 'icon',
					],
				],
				'fields'      => [
					[
						'type'    => \Elementor\Controls_Manager::CHOOSE,
						'name'    => 'type',
						'label_block' => true,
						'label'   => __( 'Type', 'elementor-addon-widgets' ),
						'default' => 'icon',
						'options'   => [
							'icon'   => [
								'title' => __( 'Icon', 'elementor-addon-widgets' ),
								'icon'  => 'fa fa-diamond',
							],
							'image' => [
								'title' => __( 'Image', 'elementor-addon-widgets' ),
								'icon'  => 'fa fa-photo',
							],
						],
					],
					[
						'type'    => \Elementor\Controls_Manager::TEXT,
						'name'    => 'title',
						'label_block' => true,
						'label'   => __( 'Title & Description', 'elementor-addon-widgets' ),
						'default' => __( 'Service Title', 'elementor-addon-widgets' ),
					],
					[
						'type'        => \Elementor\Controls_Manager::TEXTAREA,
						'name'        => 'text',
						'placeholder' => __( 'Plan Features', 'elementor-addon-widgets' ),
						'default'     => __( 'Feature', 'elementor-addon-widgets' ),
					],
					[
						'type'    => \Elementor\Controls_Manager::ICON,
						'name'    => 'icon',
						'label'   => __( 'Icon', 'elementor-addon-widgets' ),
						'default' => 'fa fa-diamond',
						'condition' => [
							'type' => 'icon',
						],
					],
					[
						'type'        => \Elementor\Controls_Manager::COLOR,
						'name'        => 'color',
						'label_block' => false,
						'label'       => __( 'Icon Color', 'elementor-addon-widgets' ),
						'default'     => '#333333',
						'condition' => [
							'type' => 'icon',
						],
					],
					[
						'type'    => \Elementor\Controls_Manager::MEDIA,
						'name'    => 'image',
						'label'   => __( 'Image', 'elementor-addon-widgets' ),
						'condition' => [
							'type' => 'image',
						],
					],
					[
						'type'        => \Elementor\Controls_Manager::URL,
						'name'        => 'link',
						'label'       => __( 'Link to', 'elementor-addon-widgets' ),
						'separator' => 'before',
						'placeholder' => __( 'https://example.com', 'elementor-addon-widgets' ),
					],
				],
				'title_field' => '{{title}}',
			]
		);

		$this->add_control(
			'align',
			[
				'label'     => '<i class="fa fa-arrows"></i> ' . __( 'Icon Position', 'elementor-addon-widgets' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'elementor-addon-widgets' ),
						'icon'  => 'fa fa-angle-left',
					],
					'top' => [
						'title' => __( 'Top', 'elementor-addon-widgets' ),
						'icon'  => 'fa fa-angle-up',
					],
					'right'  => [
						'title' => __( 'Right', 'elementor-addon-widgets' ),
						'icon'  => 'fa fa-angle-right',
					],
				],
				'default'   => 'top',
				'prefix_class' => 'obfx-position-',
				'toggle' => false,
			]
		);

		// Columns.
		$this->add_responsive_control(
			'grid_columns',
			[
				'type'           => \Elementor\Controls_Manager::SELECT,
				'label'          => '<i class="fa fa-columns"></i> ' . __( 'Columns', 'elementor-addon-widgets' ),
				'default'        => 3,
				'tablet_default' => 2,
				'mobile_default' => 1,
				'options'        => [
					1 => 1,
					2 => 2,
					3 => 3,
					4 => 4,
					5 => 5,
				],
			]
		);
		$this->end_controls_section();
	}

	/**
	 * Icon Style Controls
	 */
	private function style_icon() {
		$this->start_controls_section(
			'section_style_icon',
			[
				'label' => __( 'Icon / Image', 'elementor-addon-widgets' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'icon_space',
			[
				'label' => __( 'Spacing', 'elementor-addon-widgets' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.obfx-position-right .obfx-icon, {{WRAPPER}}.obfx-position-right .obfx-image' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.obfx-position-left .obfx-icon, {{WRAPPER}}.obfx-position-left .obfx-image' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.obfx-position-top .obfx-icon, {{WRAPPER}}.obfx-position-top .obfx-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'icon_size',
			[
				'label' => __( 'Size', 'elementor-addon-widgets' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
				],
				'default' => [
					'size' => 35,
				],
				'selectors' => [
					'{{WRAPPER}} .obfx-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .obfx-image' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_style_content',
			[
				'label' => __( 'Content', 'elementor-addon-widgets' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'text_align',
			[
				'label' => __( 'Alignment', 'elementor-addon-widgets' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'toggle' => false,
				'default' => 'center',
				'options' => [
					'left' => [
						'title' => __( 'Left', 'elementor-addon-widgets' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elementor-addon-widgets' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'elementor-addon-widgets' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .obfx-grid .obfx-grid-container .obfx-grid-wrapper .obfx-service-box' => 'text-align: {{VALUE}}; justify-content: {{VALUE}};',
					'{{WRAPPER}} .obfx-grid .obfx-grid-container .obfx-grid-wrapper .obfx-service-box .obfx-service-text' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'heading_title',
			[
				'label' => __( 'Title', 'elementor-addon-widgets' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'title_bottom_space',
			[
				'label' => __( 'Spacing', 'elementor-addon-widgets' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .obfx-service-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'elementor-addon-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .obfx-service-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .obfx-service-title',
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'heading_description',
			[
				'label' => __( 'Description', 'elementor-addon-widgets' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => __( 'Color', 'elementor-addon-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .obfx-service-text' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_3,
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'selector' => '{{WRAPPER}} .obfx-service-text',
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_3,
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Grid Style Controls
	 */
	private function style_grid_options() {
		$this->start_controls_section(
			'section_grid_style',
			[
				'label' => __( 'Grid', 'elementor-addon-widgets' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Columns margin.
		$this->add_control(
			'grid_style_columns_margin',
			[
				'label'     => __( 'Columns margin', 'elementor-addon-widgets' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'default'   => [
					'size' => 15,
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .obfx-grid-wrapper'   => 'padding-right: calc( {{SIZE}}{{UNIT}} ); padding-left: calc( {{SIZE}}{{UNIT}} );',
					'{{WRAPPER}} .obfx-grid-container' => 'margin-left: calc( -{{SIZE}}{{UNIT}} ); margin-right: calc( -{{SIZE}}{{UNIT}} );',
				],
			]
		);

		// Row margin.
		$this->add_control(
			'grid_style_rows_margin',
			[
				'label'     => __( 'Rows margin', 'elementor-addon-widgets' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'default'   => [
					'size' => 30,
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .obfx-grid-wrapper' => 'padding-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Background.
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'     => 'grid_style_background',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .obfx-grid',
			]
		);

		// Items options.
		$this->add_control(
			'grid_items_style_heading',
			[
				'label'     => __( 'Items', 'elementor-addon-widgets' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		// Items internal padding.
		$this->add_control(
			'grid_items_style_padding',
			[
				'label'      => __( 'Padding', 'elementor-addon-widgets' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .obfx-grid-col' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Items border radius.
		$this->add_control(
			'grid_items_style_border_radius',
			[
				'label'      => __( 'Border Radius', 'elementor-addon-widgets' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .obfx-grid-col' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->items_style_tabs();
		$this->end_controls_section();
	}

	/**
	 * Items Style Controls
	 */
	private function items_style_tabs() {
		$this->start_controls_tabs( 'tabs_background' );

		$this->start_controls_tab(
			'tab_background_normal',
			[
				'label' => __( 'Normal', 'elementor-addon-widgets' ),
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'     => 'grid_items_background',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .obfx-service-box',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'grid_items_box_shadow',
				'selector'  => '{{WRAPPER}} .obfx-service-box',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_background_hover',
			[
				'label' => __( 'Hover', 'elementor-addon-widgets' ),
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'     => 'grid_items_background_hover',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .obfx-service-box:hover',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'grid_items_box_shadow_hover',
				'selector'  => '{{WRAPPER}} .obfx-service-box:hover',
			]
		);

		$this->add_control(
			'hover_transition',
			[
				'label'       => __( 'Transition Duration', 'elementor-addon-widgets' ),
				'type'        => \Elementor\Controls_Manager::SLIDER,
				'default'     => [
					'size' => 0.3,
				],
				'range'       => [
					'px' => [
						'max'  => 3,
						'step' => 0.1,
					],
				],
				'selectors'   => [
					'{{WRAPPER}} .obfx-service-box' => 'transition: all {{SIZE}}s ease;',
				],
			]
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();
	}

	/**
	 * Render function to output the pricing table.
	 */
	protected function render() {
		$settings = $this->get_settings();

		$this->maybe_load_widget_style();

		echo '<div class="obfx-grid"><div class="obfx-grid-container' . ( ! empty( $settings['grid_columns_mobile'] ) ? ' obfx-grid-mobile-' . $settings['grid_columns_mobile'] : '' ) . ( ! empty( $settings['grid_columns_tablet'] ) ? ' obfx-grid-tablet-' . $settings['grid_columns_tablet'] : '' ) . ( ! empty( $settings['grid_columns'] ) ? ' obfx-grid-desktop-' . $settings['grid_columns'] : '' ) . '">';
		foreach ( $settings['services_list'] as $service ) {
			$icon_tag = 'span';

			if ( ! empty( $service['link']['url'] ) ) {
				$this->add_render_attribute( 'link', 'href', $settings['link']['url'] );
				$icon_tag = 'a';

				if ( $service['link']['is_external'] ) {
					$this->add_render_attribute( 'link', 'target', '_blank' );
				}

				if ( $service['link']['nofollow'] ) {
					$this->add_render_attribute( 'link', 'rel', 'nofollow' );
				}
			} ?>
			<div class="obfx-grid-wrapper">
				<?php
				if ( ! empty( $service['link']['url'] ) ) {
					$link_props = ' href="' . esc_url( $service['link']['url'] ) . '" ';
					if ( $service['link']['is_external'] === 'on' ) {
						$link_props .= ' target="_blank" ';
					}
					if ( $service['link']['nofollow'] === 'on' ) {
						$link_props .= ' rel="nofollow" ';
					}
					echo '<a' . $link_props . '>';
				} ?>
				<div class="obfx-service-box obfx-grid-col">
					<?php
					if ( $service['type'] === 'icon' && ! empty( $service['icon'] ) ) { ?>
						<span class="obfx-icon-wrap"><i class="obfx-icon <?php echo esc_attr( $service['icon'] ); ?>" style="color: <?php echo esc_attr( $service['color'] ); ?>"></i></span>
						<?php
					} elseif ( $service['type'] === 'image' && ! empty( $service['image']['url'] ) ) { ?>
						<span class="obfx-image-wrap"><img class="obfx-image" src="<?php echo esc_url( $service['image']['url'] ); ?>"/></span>
						<?php
					}
					if ( ! empty( $service['title'] ) || ! empty( $service['text'] ) ) { ?>
						<div class="obfx-service-box-content">
							<?php if ( ! empty( $service['title'] ) ) { ?>
								<h4 class="obfx-service-title"><?php echo esc_attr( $service['title'] ); ?></h4>
								<?php
							}
							if ( ! empty( $service['text'] ) ) { ?>
								<p class="obfx-service-text"><?php echo esc_attr( $service['text'] ); ?></p>
							<?php } ?>
						</div><!-- /.obfx-service-box-content -->
					<?php } ?>
				</div><!-- /.obfx-service-box -->
				<?php
				if ( ! empty( $service['link'] ) ) {
					echo '</a>';
				} ?>
			</div><!-- /.obfx-grid-wrapper -->
			<?php
		}// End foreach().
		echo '</div></div>';

	}

	/**
	 * Load the widget style dynamically if it is a widget preview
	 * or enqueue style and scripts if not
	 *
	 * This way we are sure that the assets files are loaded only when this block is present in page.
	 */
	protected function maybe_load_widget_style() {
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() === true && apply_filters( 'themeisle_content_forms_register_default_style', true ) ) { ?>
			<style>
				<?php echo file_get_contents( plugin_dir_path( dirname( dirname(__FILE__ ) ) ) . 'css/public.css' ) ?>
			</style>
			<?php
		} else {
			wp_enqueue_style('eaw-elementor');
		}
	}
}
