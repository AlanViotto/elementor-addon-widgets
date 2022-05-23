<?php

class Elementor_Addon_Widgets {

	/**
	 * A reference to an instance of this class.
	 */
	private static $instance;

	/**
	 * Initializes the plugin by setting filters and administration functions.
	 */
	private function __construct() {
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		add_action( 'init', array( $this, 'load_template_directory_library' ) );
		add_action( 'init', array( $this, 'load_content_forms' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		$current_theme = wp_get_theme();
		$theme_name    = $current_theme->get( 'TextDomain' );
		$template      = $current_theme->get( 'Template' );
		if ( $theme_name !== 'neve' && $template !== 'neve' ) {
			add_action( 'admin_init', array( $this, 'eaw_update_dismissed' ) );
		}

		add_filter( 'admin_menu', array( $this, 'admin_pages' ) );

		add_filter( 'elementor_extra_widgets_category_args', array( $this, 'filter_category_args' ) );
		add_filter( 'content_forms_category_args', array( $this, 'filter_category_args' ) );

		if ( defined( 'EAW_PRO_VERSION' ) ) {
			add_filter( 'template_directory_templates_list', array( $this, 'filter_templates_preview' ) );
		}

		add_filter( 'eaw_should_load_placeholders', array( $this, 'show_placeholder_widgets' ) );

		add_filter( 'obfx_template_dir_products', array( $this, 'add_page' ) );
		add_filter( 'obfx_template_dir_page_title', array( $this, 'page_title' ) );

		// load library
		$this->load_composer_library();
	}

	/**
	 * Should we show the placeholder widget? Only when PRO exists!
	 */
	function show_placeholder_widgets() {
		return defined( 'EAW_PRO_VERSION' );
	}

	/**
	 * Load the Composer library with the base feature
	 */
	public function load_composer_library() {
		if ( defined( 'ELEMENTOR_PATH' ) && class_exists( '\ThemeIsle\ElementorExtraWidgets' ) ) {
			\ThemeIsle\ElementorExtraWidgets::instance();
		}
	}

	/**
	 * Returns an instance of this class.
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new Elementor_Addon_Widgets();
		}

		return self::$instance;
	}

	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'elementor-addon-widgets' );
	}

	public function enqueue_scripts() {
		$current_screen = get_current_screen();
		if ( $current_screen->id === 'sizzify_page_sizzify_more_features' || $current_screen->id === 'toplevel_page_sizzify-admin' ) {
			wp_enqueue_style( 'sizzify-admin-style', EA_URI . 'assets/css/admin.css', array(), EA_VERSION );
		}
	}

	/**
	 * Shows theme promotion box for Neve.
	 */
	public function show_theme_promotion() {

		global $current_user;
		$user_id        = $current_user->ID;
		$ignored_notice = get_user_meta( $user_id, 'sizzify_ignore_neve_notice' );

		if ( ! empty( $ignored_notice ) ) {
			return;
		}

		echo '<div class="pro-feature theme-promote">
			<div class="pro-feature-dismiss"><a href="' . esc_url( admin_url( 'admin.php?page=sizzify-admin&sizzify_ignore_notice=0' ) ) . '"><span class="dashicons dashicons-dismiss"></span></a></div>
			<div class="pro-feature-features">
				<h2>Suggested theme</h2>
				<p>Do you enjoy working with Elementor? Check out Neve, our new FREE multipurpose theme. It\' s simple, fast and fully compatible with both Elementor and Gutenberg. We recommend to try it out together with Sizzify Lite.</p>
				<a target="_blank" href="' . esc_url( admin_url( 'theme-install.php?theme=neve' ) ) . '" class="install-now">
				<span class="dashicons dashicons-admin-appearance"></span> Install Neve</a>
			</div>
			<div class="pro-feature-image">
				<img src="' . esc_url( EA_URI . '/assets/img/neve.jpg' ) . '" alt="Neve - Free Multipurpose Theme">
			</div>
			</div>';
	}


	/**
	 * Shows upsell plugin box.
	 */
	public function show_upsell_plugins( $list, $strings, $preferences ) {

		foreach ( $list as $item ) {
			echo '<div class="pro-feature theme-promote">
				<div class="pro-feature-features">
					<h2>' . $item->custom_name . '</h2>
					<p>' . esc_html( $item->short_description ) . '</p>
					<a class="thickbox open-plugin-details-modal install-now" href="' . esc_url( $item->custom_url ) . '"><span class="dashicons dashicons-admin-appearance"></span>' . esc_html( $strings['install'] ) . '</a>
				</div>
				<div class="pro-feature-image">
					<img src="' . $item->custom_image . '">
				</div>
				</div>';
		}
	}

	/**
	 * Shows upsell theme box.
	 */
	public function show_upsell_themes( $list, $strings, $preferences ) {
		// for some reason the array becomes an object, so we have to force it back into an array.
		if ( ! is_array( $list ) ) {
			$list = array( $list );
		}
		foreach ( $list as $item ) {
			echo '<div class="pro-feature theme-promote">
				<div class="pro-feature-features">
					<h2>' . $item->custom_name . '</h2>
					<p>' . esc_html( $item->description ) . '</p>
					<a class="thickbox open-plugin-details-modal install-now" href="' . esc_url( $item->custom_url ) . '"><span class="dashicons dashicons-admin-appearance"></span>' . esc_html( $strings['install'] ) . '</a>
				</div>
				<div class="pro-feature-image">
					<img src="' . $item->screenshot_url . '">
				</div>
				</div>';
		}
	}

	public function eaw_update_dismissed() {
		global $current_user;
		$user_id = $current_user->ID;
		if ( isset( $_GET['sizzify_ignore_notice'] ) && '0' === $_GET['sizzify_ignore_notice'] ) {
			add_user_meta( $user_id, 'sizzify_ignore_neve_notice', 'true', true );
		}
	}

	public function add_page( $products ) {
		$sizzify = array(
			'sizzify' => array(
				'directory_page_title' => __( 'Sizzify Template Directory', 'elementor-addon-widgets' ),
				'parent_page_slug'     => 'sizzify-admin',
				'page_slug'            => 'sizzify_template_dir',
			),
		);

		return array_merge( $products, $sizzify );
	}

	/**
	 * Change page title.
	 *
	 * @return string
	 */
	public function page_title() {
		return __( 'Sizzify Template Directory', 'elementor-addon-widgets' );
	}

	/**
	 *
	 *  Add page to the dashbord menu
	 *
	 * @since 1.0.0
	 */
	public function admin_pages() {

		add_menu_page(
			'Sizzify',
			'Sizzify',
			'manage_options',
			'sizzify-admin',
			array(
				$this,
				'render_main_page',
			),
			'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxOTMuMTcgMTU4LjUiPjxkZWZzPjxzdHlsZT4uYXtmaWxsOiNmMmYyZjI7fTwvc3R5bGU+PC9kZWZzPjx0aXRsZT5Bc3NldCA4PC90aXRsZT48Y2lyY2xlIGNsYXNzPSJhIiBjeD0iMTg1LjcyIiBjeT0iMTguNjgiIHI9IjcuNDUiLz48Y2lyY2xlIGNsYXNzPSJhIiBjeD0iMTUyLjk5IiBjeT0iMTkuNjYiIHI9IjUuMTgiLz48Y2lyY2xlIGNsYXNzPSJhIiBjeD0iMTYxLjk5IiBjeT0iNTUuMDYiIHI9IjEzIi8+PGNpcmNsZSBjbGFzcz0iYSIgY3g9IjE2OC4zNCIgY3k9IjkuOTgiIHI9IjUuMDQiLz48Y2lyY2xlIGNsYXNzPSJhIiBjeD0iMTUwLjUxIiBjeT0iMyIgcj0iMyIvPjxjaXJjbGUgY2xhc3M9ImEiIGN4PSIxNTguNTEiIGN5PSIzNCIgcj0iMyIvPjxjaXJjbGUgY2xhc3M9ImEiIGN4PSIxNDQuMTYiIGN5PSIzNi45MyIgcj0iNiIvPjxjaXJjbGUgY2xhc3M9ImEiIGN4PSIxODMuNSIgY3k9IjQwLjM2IiByPSI3LjUiLz48Y2lyY2xlIGNsYXNzPSJhIiBjeD0iMTY4LjQ3IiBjeT0iMjkuODMiIHI9IjQiLz48Y2lyY2xlIGNsYXNzPSJhIiBjeD0iMTM2LjI4IiBjeT0iMTYuNzYiIHI9IjYuODkiLz48cGF0aCBjbGFzcz0iYSIgZD0iTTEyNS42NywxMTAuNjZhNDEuNjUsNDEuNjUsMCwwLDEtNy41LDI0LjIycS03LjUsMTAuODUtMjIuNCwxNy4yM1Q1OS41OSwxNTguNWExNTAuNywxNTAuNywwLDAsMS0yMC4zNi0xLjM4LDgsOCwwLDEsMC0xNC41MS00LjY0LDguMjQsOC4yNCwwLDAsMCwuMjYsMkExMTEuNCwxMTEuNCwwLDAsMSw3LjY5LDE0OSw2LDYsMCwwLDAsLjExLDEzOS43YTcuNzgsNy43OCwwLDAsMSwuNjQtMi41NWw5LTIwLjMzYTgsOCwwLDAsMSw4LjE2LTQuNzIsOCw4LDAsMSwwLDEyLjgyLDYuMzgsNy43Miw3LjcyLDAsMCwwLS4xOC0xLjY4YzEuNzQuNTksMy41MSwxLjE1LDUuMzMsMS42N0E4OC45Miw4OC45MiwwLDAsMCw2MCwxMjJxOS45MywwLDE0LjE5LTEuOTJjMi44NC0xLjI5LDQuMjYtMy4yMSw0LjI2LTUuNzhxMC00LjQ1LTUuNTctNi42OXQtMTguMzUtNC44NmEyMDkuNTMsMjA5LjUzLDAsMCwxLTI3LjM2LTcuNGwtMS4yNy0uNUExMywxMywwLDAsMCw2LjczLDgwLjcyUTAsNzIsMCw1OEE0Myw0MywwLDAsMSwyLDQ0Ljc3LDgsOCwwLDAsMCw4LjIyLDM3YTguMDYsOC4wNiwwLDAsMC0uNzMtMy4zM2gwQTQ1LjU1LDQ1LjU1LDAsMCwxLDIyLjY1LDIwYTcuNDksNy40OSwwLDAsMCwxMi41Ny01LjUydjBxMTMuMjQtNC4zMSwzMC44NS00LjMzYTEzMS43NywxMzEuNzcsMCwwLDEsMjguNjksMy4xNEE5Ny45MSw5Ny45MSwwLDAsMSwxMTIuNiwxOWE4LDgsMCwwLDEsNC4xMiwxMC4zOGwtOC4zMywyMC4wOGE4LDgsMCwwLDEtMTAuNDYsNC4zMlE4MSw0Ni42MSw2NS42Nyw0Ni42MXEtMTguNDUsMC0xOC40NSw4LjkyLDAsNC4yNiw1LjQ4LDYuMzl0MTgsNC41NmExODMuOTMsMTgzLjkzLDAsMCwxLDI3LjM2LDcsNDcuNTgsNDcuNTgsMCwwLDEsMTkuMzYsMTIuODdRMTI1LjY3LDk1LjI3LDEyNS42NywxMTAuNjZaTTYxLjcyLDE1NS40OGEzLDMsMCwxLDAtMywzQTMsMywwLDAsMCw2MS43MiwxNTUuNDhabS02LTI2YTQsNCwwLDEsMC00LDRBNCw0LDAsMCwwLDU1LjcyLDEyOS40OFptLTE0LjUtNzRBMTAuNSwxMC41LDAsMSwwLDMwLjcyLDY2LDEwLjUsMTAuNSwwLDAsMCw0MS4yMiw1NS40OFoiLz48cGF0aCBjbGFzcz0iYSIgZD0iTTE2MS4zNSw3NkEyMywyMywwLDAsMSwxNDIuMSw2NS41NmEyLDIsMCwwLDAtMy42NiwxLjExdjgwLjU4YTgsOCwwLDAsMCw4LDhoMjkuODFhOCw4LDAsMCwwLDgtOFY2Ni42N2EyLDIsMCwwLDAtMy42NS0xLjExQTIzLDIzLDAsMCwxLDE2MS4zNSw3NloiLz48L3N2Zz4=',
			'76'
		);
		remove_submenu_page( 'sizzify-admin', 'sizzify-admin' );
	}

	public function render_main_page() {
		include_once EA_PATH . 'admin/partials/main.php';
	}

	/**
	 * Adjust the modules category name
	 *
	 * @param $args
	 *
	 * @return array
	 */
	public function filter_category_args( $args ) {
		return array(
			'slug'  => 'eaw-elementor-widgets',
			'title' => __( 'Sizzify Widgets', 'elementor-addon-widgets' ),
			'icon'  => 'fa fa-plug',
		);
	}

	/**
	 * Filter Template Previews
	 *
	 * @param $templates
	 *
	 * @return array
	 */
	public function filter_templates_preview( $templates ) {
		$screen = get_current_screen();
		if ( $screen->id !== 'sizzify_page_sizzify_template_dir' ) {
			return $templates;
		}

		$placeholders       = array(
			'hive-landing'   => array(
				'title'       => __( 'Hive - Landing Page', 'elementor-addon-widgets' ),
				'description' => __( 'A clean and modern design perfectly suitable for both corporate and creative businesses. Its sections come with a professional vibe and engaging elements such as progress bars, checklists, business-oriented icons, statistics counter, team members, testimonials, and call-to-action buttons.', 'elementor-addon-widgets' ),
				'demo_url'    => 'https://demo.themeisle.com/hestia-pro-demo-content/hive-landing-page/',
				'screenshot'  => esc_url( 'https://raw.githubusercontent.com/Codeinwp/obfx-templates/master/placeholders/hive-landing.png' ),
				'has_badge'   => __( 'Pro', 'elementor-addon-widgets' ),
			),
			'hive-about'     => array(
				'title'       => __( 'Hive - About Page', 'elementor-addon-widgets' ),
				'description' => __( 'A beautiful and complex layout for the About Us page, that allows you to introduce your team in a professional manner. The design of this page lets you highlight your team\'s skills, the services you provide, a hiring contact form for future members, and various other element styles - all put in the spotlight by a subtle orange color.', 'elementor-addon-widgets' ),
				'demo_url'    => 'https://demo.themeisle.com/hestia-pro-demo-content/hive-about/',
				'screenshot'  => esc_url( 'https://raw.githubusercontent.com/Codeinwp/obfx-templates/master/placeholders/hive-about.png' ),
				'has_badge'   => __( 'Pro', 'elementor-addon-widgets' ),
			),
			'sine-landing'   => array(
				'title'       => __( 'Sine - Landing Page', 'elementor-addon-widgets' ),
				'description' => __( 'This is an Elementor layout for digital agencies, meant to turn your business goals into conversions. The design catches your eye with its elegant parallax scrolling, classy color gradients, magazine-like typography, interactive counters and icons - all these elements being wrapped up in modern and joyful content blocks and forms.', 'elementor-addon-widgets' ),
				'demo_url'    => 'https://demo.themeisle.com/hestia-pro-demo-content/sine-landing-page/',
				'screenshot'  => esc_url( 'https://raw.githubusercontent.com/Codeinwp/obfx-templates/master/placeholders/sine-landing.png' ),
				'has_badge'   => __( 'Pro', 'elementor-addon-widgets' ),
			),
			'square-landing' => array(
				'title'       => __( 'Square - Landing Page', 'elementor-addon-widgets' ),
				'description' => __( 'A nice and complex landing page for products and apps, with a modern interface that combines orange and violet color tones. All the elements used in this layout are large and provide meaningful sections that describe your product comprehensively. The layout allows you to add videos, screenshots, pricing tables, progress bars and counters - all presented in a beautiful full-screen design.', 'elementor-addon-widgets' ),
				'demo_url'    => 'https://demo.themeisle.com/hestia-pro-demo-content/square-landing-page/',
				'screenshot'  => esc_url( 'https://raw.githubusercontent.com/Codeinwp/obfx-templates/master/placeholders/square-landing.png' ),
				'has_badge'   => __( 'Pro', 'elementor-addon-widgets' ),
			),
			'tekt-landing'   => array(
				'title'       => __( 'Tekt - Landing Page', 'elementor-addon-widgets' ),
				'description' => __( 'A layout for architects and interior design agencies, with a clean and simple interface. The full-width header, the modern content blocks, and the parallax backgrounds - all completed by the minimalist approach - make this template a perfect fit for your architecture agency.', 'elementor-addon-widgets' ),
				'demo_url'    => 'https://demo.themeisle.com/hestia-pro-demo-content/tekt-landing-page/',
				'screenshot'  => esc_url( 'https://raw.githubusercontent.com/Codeinwp/obfx-templates/master/placeholders/tekt-landing.png' ),
				'has_badge'   => __( 'Pro', 'elementor-addon-widgets' ),
			),
			'tekt-about'     => array(
				'title'       => __( 'Tekt - About Page', 'elementor-addon-widgets' ),
				'description' => __( 'This is the About page of a business based on interior design and architecture, which allows you to beautifully showcase your team members. Apart from introducing the team, you have modern blocks and toggle elements where you can talk more about your agency: add values, services, statistics, and images.', 'elementor-addon-widgets' ),
				'demo_url'    => 'https://demo.themeisle.com/hestia-pro-demo-content/tekt-about-page/',
				'screenshot'  => esc_url( 'https://raw.githubusercontent.com/Codeinwp/obfx-templates/master/placeholders/tekt-about.png' ),
				'has_badge'   => __( 'Pro', 'elementor-addon-widgets' ),
			),
		);
		$filtered_templates = array_merge( $templates, $placeholders );

		return $filtered_templates;
	}

	/**
	 * Call the Templates Directory library
	 */
	public function load_template_directory_library() {
		if ( class_exists( '\ThemeIsle\PageTemplatesDirectory' ) ) {
			\ThemeIsle\PageTemplatesDirectory::instance();
		}
	}

	/**
	 * If the content-forms library is available we should make the forms available for elementor
	 */
	public function load_content_forms() {
		if ( class_exists( '\ThemeIsle\ContentForms\ContactForm' ) ) {
			\ThemeIsle\ContentForms\ContactForm::instance();
			\ThemeIsle\ContentForms\NewsletterForm::instance();
			\ThemeIsle\ContentForms\RegistrationForm::instance();
		}
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'elementor-addon-widgets' ), '1.0.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'elementor-addon-widgets' ), '1.0.0' );
	}
}

add_action( 'plugins_loaded', array( 'Elementor_Addon_Widgets', 'get_instance' ) );
