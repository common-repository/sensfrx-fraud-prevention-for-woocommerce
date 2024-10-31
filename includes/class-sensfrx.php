<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://sensfrx.ai
 * @since      1.0.1
 *
 * @package    SensFRX
 * @subpackage SensFRX/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    SensFRX
 * @subpackage SensFRX/includes
 */

class SensFRX {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @var      SensFRX_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */

	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $SensFRX    The string used to uniquely identify this plugin.
	 */

	protected $SensFRX;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $version    The current version of the plugin.
	 */

	protected $version;

	/**
	 * The core functionality of the SensFRX plugin.
	 *
	 * @since    1.0.0
	 */

	public function __construct() {
		$this->constants();
		if ( defined( 'SENSFRX_VERSION' ) ) {
			$this->version = SENSFRX_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->SensFRX = 'SensFRX';
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	public function constants() {
		if (!defined('SENSFRX_VERSION')) {
			define('SENSFRX_VERSION', '1.0.0');
		}
		if (!defined('SENSFRX_AUTHOR')) {
			define('SENSFRX_AUTHOR', 'SensFRX');
		}
		if (!defined('SENSFRX_NAME')) { 
			define('SENSFRX_NAME', __('SensFRX', 'sensfrx_fpwoo'));
		}
		if (!defined('SENSFRX_PATH')) {
			define('SENSFRX_PATH', 'admin.php?page=wc-settings&tab=sensfrx');
		}
		if (!defined('SENSFRX_API_URL')) { 
			define('SENSFRX_API_URL', 'http://a.sensfrx.ai/v1');
		}
	}

	public static function default_options() {
		$options = array(
			'sensfrx_property_id'    => '',
			'sensfrx_property_secret'=> '',
			'default_options' => 0
		);
		return apply_filters('sensfrx_default_options', $options);
	}

	public static function default_policy_options() {
		$options = array(
			'sensfrx_medium_email'    => '1',
			'sensfrx_challenge_email'=> '1',
			'sensfrx_deny_email'=> '1',
			'sensfrx_allow_payment_email' => '1',
			'sensfrx_challenge_payment_email' => '1',
			'sensfrx_deny_payment_email' => '1',
			'sensfrx_allow_register_email' => '1',
			'sensfrx_challenge_register_email' => '1',
			'sensfrx_deny_register_email' => '1',
			'sensfrx_webhook_allow' => '1',
			'sensfrx_whitle_list_email' => '1',
		);
		return apply_filters('sensfrx_default_policy_options', $options);
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - SensFRX_Loader. Orchestrates the hooks of the plugin.
	 * - SensFRXi18n. Defines internationalization functionality.
	 * - SensFRX_Admin. Defines all hooks for the admin area.
	 * - SensFRX_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 *
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sensfrx-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sensfrx-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-sensfrx-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-sensfrx-public.php';
		$this->loader = new SensFRX_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Sensfrxi18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 */

	private function set_locale() {
		$plugin_i18n = new SensFRXi18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * 
	 */

	private function define_admin_hooks() {

		$plugin_admin = new SensFRX_Admin( $this->get_SensFRX(), $this->get_version() );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_sensfrx_menu' );
		$this->loader->add_filter( 'admin_init', $plugin_admin, 'add_settings' );
		// $this->loader->add_filter( 'cron_schedules', $plugin_admin, 'sensfrx_cron_intervals' );
		// $this->loader->add_filter( 'wp', $plugin_admin, 'sensfrx_interval_schedule_cron' );
		// $this->loader->add_filter( 'sensfrx_interval_cron_event', $plugin_admin, 'sensfrx_interval_cron_function' );
		// $this->loader->add_filter( 'upgrader_process_complete', $plugin_admin, 'sensfrx_interval_update' );
		$this->loader->add_filter( 'plugin_action_links', $plugin_admin, 'action_links', 10, 2 );
		$this->loader->add_action( 'wp_head', $plugin_admin, 'webhook_implementation' );
		$this->loader->add_action( 'rest_api_init', $plugin_admin, 'sensfrx_webhook' ); 
		$this->loader->add_action( 'rest_api_init', $plugin_admin, 'sensfrx__transaction_webhook' ); 
		$this->loader->add_action( 'plugins_loaded', $plugin_admin, 'sensfrx_check_update' );
		$this->loader->add_action( 'woocommerce_registration_errors', $plugin_admin, 'registration_function', 10, 3 );
		$this->loader->add_filter( 'login_message', $plugin_admin, 'as_login_message', 10, 2 );
		$this->loader->add_action( 'woocommerce_save_account_details_errors', $plugin_admin, 'user_profile_updates', 10, 1 );
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'add_dashboard_notification' );
		$this->loader->add_action( 'wp_login', $plugin_admin, 'wp_login_track', 10, 2 );
		// $this->loader->add_filter( 'woocommerce_before_checkout_form', $plugin_admin, 'payment_attempt', 10, 2 );
		$this->loader->add_filter( 'woocommerce_checkout_process', $plugin_admin, 'payment_attempt', 10, 2 );
		$this->loader->add_action( 'woocommerce_thankyou', $plugin_admin, 'payment_complete', 10, 2 );
		$this->loader->add_action( 'wp_login_failed', $plugin_admin, 'wp_login_failed_track', 10, 2 );
		$this->loader->add_action( 'after_password_reset', $plugin_admin, 'password_reset_track', 10, 2 );
		$this->loader->add_action( 'lostpassword_post', $plugin_admin, 'password_reset_failed_track', 10, 2 );
		// $this->loader->add_action( 'wp_logout', $plugin_admin, 'wp_logout_track', 10, 2);
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		// Woocommerce Extension Development
		$this->loader->add_filter( 'woocommerce_get_settings_pages', $plugin_admin, 'woocommerce_settings_page', 10, 1 );
		$this->loader->add_filter( 'woocommerce_settings_tabs_array', $plugin_admin, 'woocommerce_settings_tab', 50 );
		$this->loader->add_action( 'woocommerce_settings_tabs_sensfrx', $plugin_admin, 'woocommerce_settings_tab_content' );
		// $this->loader->add_action( 'custom_woocommerce_integration_tab_content', $plugin_admin, 'custom_woocommerce_integration_tab_content' );
		$this->loader->add_action( 'woocommerce_validation_rules_tab_content', $plugin_admin, 'woocommerce_validation_rules_tab_content' );
		$this->loader->add_action( 'woocommerce_policies_tab_content', $plugin_admin, 'woocommerce_policies_tab_content' );
		$this->loader->add_action( 'woocommerce_notifications_alerts_tab_content', $plugin_admin, 'woocommerce_notifications_alerts_tab_content' );
		$this->loader->add_action( 'woocommerce_license_information_tab_content', $plugin_admin, 'woocommerce_license_information_tab_content' );
		$this->loader->add_action( 'account_privacy_tab_content', $plugin_admin, 'account_privacy_tab_content' );
		$this->loader->add_action( 'woocommerce_profile_tab_content', $plugin_admin, 'woocommerce_profile_tab_content' );
		// $this->loader->add_action( 'admin_menu', $plugin_admin, 'custom_woocommerce_add_menu' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * 
	 */

	private function define_public_hooks() {
		$plugin_public = new SensFRX_Public( $this->get_SensFRX(), $this->get_version() );
		$this->loader->add_action( 'init', $plugin_public, 'get_user_verify');
		$this->loader->add_action( 'wp_head', $plugin_public, 'sensfrx_nonce' );
		$this->loader->add_action( 'wp_enqueue_scripts'	  , $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts'	  , $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'login_enqueue_scripts', $plugin_public, 'enqueue_scripts_login' );
		$this->loader->add_action( 'woocommerce_login_form', $plugin_public, 'add_field_login_form' );
		$this->loader->add_action( 'woocommerce_register_form', $plugin_public, 'add_field_registration_form' );
		$this->loader->add_action( 'woocommerce_edit_account_form', $plugin_public, 'add_field_edit_password_form' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts_for_registration_form' );
		$this->loader->add_action( 'woocommerce_billing_fields', $plugin_public, 'sensfrx_woocommerce_billing_fields' );
		$this->loader->add_action( 'woocommerce_before_cart', $plugin_public, 'enqueue_scripts_transaction_attempt' );
		$this->loader->add_action( 'woocommerce_cart_collaterals', $plugin_public, 'custom_cart_collaterals' );
		$this->loader->add_action( 'woocommerce_before_checkout_form', $plugin_public, 'my_custom_script_on_checkout_page' );
		$this->loader->add_action( 'woocommerce_checkout_before_order_review', $plugin_public, 'add_custom_checkout_field' );
		$this->loader->add_action( 'woocommerce_edit_account_form', $plugin_public, 'profile_update' );
		$this->loader->add_action( 'woocommerce_edit_account_form_start', $plugin_public, 'prefill_custom_input_field' );
		$this->loader->add_action( 'woocommerce_save_account_details', $plugin_public, 'save_custom_input_field' );
		$this->loader->add_action( 'woocommerce_checkout_update_order_meta', $plugin_public, 'save_custom_field_value_to_session' );
		$this->loader->add_filter( 'template_redirect'	  , $plugin_public, 'as_logout', 10, 2 );
		$this->loader->add_action( 'login_message'		  , $plugin_public, 'as_login_message' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */

	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */

	public function get_SensFRX() {
		return $this->SensFRX;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    SensFRX_Loader    Orchestrates the hooks of the plugin.
	 */

	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */

	public function get_version() {
		return $this->version;
	}

}

