<?php
/**
 * The public-facingfunctionality of the SensFRX plugin.
 *
 * @link       https://sensfrx.ai
 * @since      1.0.0
 * @package    SensFRX
 * @subpackage SensFRX/public
 */
/**
 * The public-facingfunctionality of the SensFRX plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    SensFRX
 * @subpackage SensFRX/public
 * 
 */
class SensFRX_Public {
	/**
	 * The ID of this plugin.
	 * 
	 * @since    1.0.0
	 * @var      string    $SensFRX    The ID of this plugin.
	 */

	private $SensFRX;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $version    The current version of this plugin.
	 */

	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $SensFRX       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */

	public function __construct( $SensFRX, $version ) {
		$this->SensFRX = $SensFRX;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */

	public function enqueue_styles() {

		/**
		 * Include public-facing CSS files.
		 * 
		 */

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */

	public function enqueue_scripts() {
		/**
		 * Include public-facing Javascript files.
		 * 
		 */
		wp_enqueue_script( $this->SensFRX . '-public', plugin_dir_url( __FILE__ ) . 'js/sensfrx-public-publ.js', array(), '1.0.9', true );
		extract($this->sensfrx_settings_options());
		if ( ( isset($sensfrx_property_id) && !empty($sensfrx_property_id) ) && ( isset($sensfrx_property_secret) && !empty($sensfrx_property_secret) ) ) {
			wp_enqueue_script( $this->SensFRX, SENSFRX_PIXEL_URL . $sensfrx_property_id, array(), '1.0.0', false );
			if (is_user_logged_in()) {
				wp_add_inline_script( $this->SensFRX, '_sensfrx("userInit", "' . esc_attr(get_current_user_id()) . '");', 'after' );
			}
		}
	}

	public function enqueue_scripts_login() {
		extract($this->sensfrx_settings_options());
		wp_enqueue_script( $this->SensFRX, SENSFRX_PIXEL_URL . $sensfrx_property_id, array(), '1.0.0', false );
		wp_enqueue_script( $this->SensFRX . '-login', plugin_dir_url( __FILE__ ) . 'js/sensfrx-login.js', array(), '1.0.22', true );
	}

	public function enqueue_scripts_for_registration_form() {
		extract($this->sensfrx_settings_options());
		wp_enqueue_script( $this->SensFRX, SENSFRX_PIXEL_URL . $sensfrx_property_id, array(), '1.0.0', false );
		wp_enqueue_script( $this->SensFRX . '-reg', plugin_dir_url( __FILE__ ) . 'js/sensfrx-reg.js', array(), '1.0.22', true );
	}

	/**
	 * The function "enqueue_scripts_transaction_attempt" is used to enqueue scripts for a transaction
	 * attempt if the current page is the cart page.
	 */

	public function enqueue_scripts_transaction_attempt() {
		if (is_cart()) {
			extract($this->sensfrx_settings_options());
			wp_enqueue_script( $this->SensFRX, SENSFRX_PIXEL_URL . $sensfrx_property_id, array(), '1.0.0', false );
			wp_enqueue_script( $this->SensFRX . '-cart', plugin_dir_url( __FILE__ ) . 'js/sensfrx-cart.js', array(), '1.0.22', true );
		}
	}

	public function custom_cart_collaterals() {
		
		echo '<form id="myForm" method="POST" action="">';
		echo '<div class="custom-input-wrapper">';
		echo '<input type="hidden" name="custom" id="custom"  />';
		echo '<button type="button" id="submitButton" style="display: none;" >Submit Form</button>';
		echo '</div>';
		echo '<form>';
		?>
		<script type="text/javascript">

			function autoClickButton() {

				document.getElementById('submitButton').click();
				var pixel_string = document.getElementById('custom').value;

				function sliceString(str, length) {

					length = length/2+1
					if (length >= str.length) {
						return [str, ''];
					} else {
						return [str.slice(0, length), str.slice(length)];
					}

				}

				function setCookie(cookieName, cookieValue, daysToExpire) {                         // Length issue sometime get which prevent the cookie to generate
					let expires = '';
					if (daysToExpire) {
						const date = new Date();
						date.setTime(date.getTime() + (daysToExpire * 24 * 60 * 60 * 1000));
						expires = '; expires=' + date.toUTCString();
					}
					
					document.cookie = cookieName + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/';
					document.cookie = cookieName + '=' + cookieValue + expires + '; path=/';
				}

				var pixel_string_slice = sliceString(pixel_string, pixel_string.length);
	
				var device_id_0 = pixel_string_slice[0];
				var device_id_0_0 = sliceString(device_id_0, device_id_0.length);
				var device_id_0_1 = device_id_0_0[0];
				var device_id_0_2 = device_id_0_0[1];

				var device_id_1 = pixel_string_slice[1];
				var device_id_1_0 = sliceString(device_id_1, device_id_1.length);
				var device_id_1_1 = device_id_1_0[0];
				var device_id_1_2 = device_id_1_0[1];

				// console.log(pixel_string);
				// console.log(pixel_string_slice);
				// console.log(pixel_string_slice[0]);
				// console.log(pixel_string_slice[1]);
				// console.log(device_id_0_1);
				// console.log(device_id_0_2);
				// console.log(device_id_1_1);
				// console.log(device_id_1_2);
				
				// setCookie('device___id_0', device_id_0_1 , 7);
				// setCookie('device___id_1', device_id_0_2 , 7);
				// setCookie('device___id_2', device_id_1_1 , 7);
				// setCookie('device___id_3', device_id_1_2 , 7);
				

			}

			setTimeout(autoClickButton, 2000);

		</script>
		<?php

	}

	/**
	 * The function `my_custom_script_on_checkout_page()` enqueues two JavaScript files on the checkout
	 * page if it is currently being viewed.
	 */
	public function my_custom_script_on_checkout_page() {
		if (is_checkout()) {
			extract($this->sensfrx_settings_options());
			wp_enqueue_script( $this->SensFRX, SENSFRX_PIXEL_URL . $sensfrx_property_id, array(), '1.0.0', false );
			wp_enqueue_script( $this->SensFRX . '-woocommerce', plugin_dir_url( __FILE__ ) . 'js/sensfrx-woocommerce.js', array(), '1.0.22', true );
		}
	}

	public function add_field_login_form() {
		?>
		<input type="hidden" name="woocommerce-login-nonce" value="<?php echo esc_html(wp_create_nonce('woocommerce-login')); ?>" />
		<?php
	}

	public function add_field_registration_form() {
		?>
		<input type="hidden" name="woocommerce-register-nonce" value="<?php echo esc_html(wp_create_nonce('woocommerce-register')); ?>" />
		<?php
	}

	public function add_field_edit_password_form() {
		wp_nonce_field('sensfrx_password_profile_update_action', 'sensfrx_password_profile_update_nonce');
	}

	/**
	 * The function adds a custom checkout field to a PHP script.
	 */

	public function add_custom_checkout_field() {
		echo '<div id="custom_field_wrapper">';
		wp_nonce_field('sensfrx_field_action', 'sensfrx_field_nonce');
		echo '<input type="hidden" name="custom_field" id="custom_field" class="input-text"  />';
		echo '</div>';
	}

	/**
	 * The function saves a custom field value to the session in PHP.
	 */

	public function save_custom_field_value_to_session() {
		if ( !empty($_POST['custom_field']) && isset($_POST['sensfrx_field_nonce']) && wp_verify_nonce(sanitize_text_field($_POST['sensfrx_field_nonce']), 'sensfrx_field_action') ) {
			$custom_field_value = sanitize_text_field($_POST['custom_field']);
			WC()->session->set('custom_field_value', $custom_field_value);
		}
	}

	public function getUserUsername() {
		$user = get_userdata(get_current_user_id());
		return $user->data->user_login;
	}

	public function profile_update() {
		wp_nonce_field('sensfrx_profile_up_action', 'sensfrx_profile_up_nonce');
		?>
		<input type="hidden" class="woocommerce-Input woocommerce-Input--text input-text" name="profile_update" id="profile_update" value="<?php echo esc_attr(get_user_meta(get_current_user_id(), 'custom_field', true)); ?>" />
		<?php
	}

	public function prefill_custom_input_field() {

		extract($this->sensfrx_settings_options());
		wp_enqueue_script( $this->SensFRX, SENSFRX_PIXEL_URL . $sensfrx_property_id, array(), '1.0.0', false );
		wp_enqueue_script( $this->SensFRX . '-profile-update', plugin_dir_url( __FILE__ ) . 'js/sensfrx-profile-update.js', array(), '1.0.22', true );

	}

	public function save_custom_input_field($user_id) {
		if ( isset($_POST['profile_update']) && isset($_POST['sensfrx_profile_up_nonce']) && wp_verify_nonce(sanitize_text_field($_POST['sensfrx_profile_up_nonce']), 'sensfrx_profile_up_action') ) {
			$custom_field_value = sanitize_text_field($_POST['profile_update']);
			update_user_meta($user_id, 'profile_update', $custom_field_value);
		}
	}

	public function sensfrx_settings_options() {
		$options = get_option('sensfrx_options', SensFRX::default_options());
		$property_id = ( isset($options['sensfrx_property_id']) && !empty($options['sensfrx_property_id']) ) ? $options['sensfrx_property_id'] : '';
		$property_secret = ( isset($options['sensfrx_property_secret']) && !empty($options['sensfrx_property_secret']) ) ? $options['sensfrx_property_secret'] : '';
		$options_array = array(
			'options'         => $options,
			'sensfrx_property_id' => $property_id,
			'sensfrx_property_secret' => $property_secret,
			// 'pixel_track' 	  => $pixel_track,
			// 'login_track'     => $login_track,
			// 'reset_track'     => $reset_track
		);
		return apply_filters('sensfrx_settings_options_array', $options_array);
	}

	public function sensfrx_nonce() {
		 // Generate a nonce field
		 wp_nonce_field('sensfrx_custom_action', 'sensfrx_custom_nonce');
	}

	public function as_logout() {
		if (isset($_POST['sensfrx_custom_nonce']) && wp_verify_nonce(sanitize_text_field($_POST['sensfrx_custom_nonce'], 'sensfrx_custom_action')) && isset($_REQUEST['did'])) {
			$did = sanitize_text_field($_REQUEST['did']);
			$logout_url = sanitize_url(wp_logout_url( get_home_url() ));
			$logout_url.= '&did=' . $did;
			wp_safe_redirect( str_replace( '&amp;', '&', $logout_url ) );
			exit;
		}
	}

	public function as_login_message($message) {
		$action = isset($_POST['sensfrx_custom_nonce']) && wp_verify_nonce(sanitize_text_field($_POST['sensfrx_custom_nonce'], 'sensfrx_custom_action')) && isset( $_REQUEST['action'] ) ? sanitize_text_field($_REQUEST['action']) : null;
		$as_msg = isset($_POST['sensfrx_custom_nonce']) && wp_verify_nonce(sanitize_text_field($_POST['sensfrx_custom_nonce'], 'sensfrx_custom_action')) && isset( $_REQUEST['as_msg'] ) ? sanitize_text_field($_REQUEST['as_msg']) : null;
		$errors = new WP_Error();
		if ( isset($_POST['sensfrx_custom_nonce']) && wp_verify_nonce(sanitize_text_field($_POST['sensfrx_custom_nonce'], 'sensfrx_custom_action')) && isset( $_GET['key'] ) ) {
			$action = 'resetpass';
		}
		if ( isset($_POST['sensfrx_custom_nonce']) && wp_verify_nonce(sanitize_text_field($_POST['sensfrx_custom_nonce'], 'sensfrx_custom_action')) && isset( $_GET['checkemail'] ) ) {
			$action = 'checkemail';
		}
		if ('as_ch_dn' == $action) {
			// $message .= '<p class="message">' . __( urldecode($as_msg), 'text_domain' ) . '</p>';
			$message .= '<p class="message">' . urldecode($as_msg) . '</p>';
		}
		return $message;
	}

	public function sensfrx_woocommerce_billing_fields($fields) {
		// Add a custom text input field
		// $fields['billing_sensfrx_input'] = array(
		// 	'type'        => 'text',
		// 	'label'       => __('Sensfrx Input', 'woocommerce'),
		// 	'placeholder' => __('Enter your custom input', 'woocommerce'),
		// 	'required'    => false,  // Set to true if you want to make it mandatory
		// 	'class'       => array('form-row-wide'), // Adjust the class for layout
		// 	'priority'    => 120, // Adjust the position of the field in the form
		// );
	
		// Add a custom checkbox field
		// $fields['billing_sensfrx_checkbox'] = array(
		// 	'type'        => 'checkbox',
		// 	'label'       => __('Sensfrx Agree to Custom Terms', 'woocommerce'),
		// 	'required'    => false, // Set to true if you want to make it mandatory
		// 	'class'       => array('form-row-wide'), // Adjust the class for layout
		// 	'priority'    => 130, // Adjust the position of the checkbox in the form
		// );
	
		return $fields;
	}

	public function get_user_verify() {

		$options = get_option('sensfrx_options', SensFRX::default_options());
		if (isset($_POST['sensfrx_custom_nonce']) && wp_verify_nonce(sanitize_text_field($_POST['sensfrx_custom_nonce'], 'sensfrx_custom_action')) && isset($_GET['as_did_1337575'])) {
			$as_rs = sanitize_text_field($_GET['as_did_1337575']);
			if (isset($_GET['asda_ac'])) {
				$as_ac = sanitize_text_field($_GET['asda_ac']);
			} else {
				$as_ac = ''; 
			}
			if (in_array($as_ac, array('0','1'))) {
				$action = ( ( 1==$as_ac )?'approve':'deny' );				
				require_once(SENSFRX_DIR . 'sensfrx-php-sdk/SensFRX/autoload.php');
				$obj = new SensFRX\SensFRX([
					'property_id' => $options['sensfrx_property_id'],
					'property_secret' => $options['sensfrx_property_secret']
				]);
				if (1 == $as_ac) {
					$res = $obj->approveDevice($as_rs);
				} else {
					$res = $obj->denyDevice($as_rs);
				}
			}
		}
	}
}
