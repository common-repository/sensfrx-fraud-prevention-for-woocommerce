<?php

if (!defined('ABSPATH')) {
	// exit;
}
return apply_filters('custom_woocommerce_settings_options', array(
	'sensfrx_section_title' => array(
		'name' => __('SensFRX Settings', 'sensfrx_fpwoo'),
		'type' => 'title',
		'desc' => __('Custom settings for SensFRX', 'sensfrx_fpwoo'),
		'id'   => 'sensfrx_section_title',
	),
	'sensfrx_option' => array(
		'name' => __('SensFRX Option', 'sensfrx_fpwoo'),
		'type' => 'text',
		'desc' => __('Enter your SensFRX option', 'sensfrx_fpwoo'),
		'id'   => 'sensfrx_option',
	),
	// 'integration_title' => array(
	//     'name' => __('Integration', 'sensfrx_fpwoo'),
	//     'type' => 'title',
	//     'desc' => __('Settings for Integration', 'sensfrx_fpwoo'),
	//     'id'   => 'integration_title',
	// ),
	// 'integration_option' => array(
	//     'name' => __('Integration Option', 'sensfrx_fpwoo'),
	//     'type' => 'text',
	//     'desc' => __('Enter your Integration option', 'sensfrx_fpwoo'),
	//     'id'   => 'integration_option',
	// ),
	'validation_rules_title' => array(
		'name' => __('Validation Rules', 'sensfrx_fpwoo'),
		'type' => 'title',
		'desc' => __('Settings for Validation Rules', 'sensfrx_fpwoo'),
		'id'   => 'validation_rules_title',
	),
	'validation_rules_option' => array(
		'name' => __('Validation Rules Option', 'sensfrx_fpwoo'),
		'type' => 'text',
		'desc' => __('Enter your Validation Rules option', 'sensfrx_fpwoo'),
		'id'   => 'validation_rules_option',
	),
	'policies_settings_title' => array(
		'name' => __('Policies Settings', 'sensfrx_fpwoo'),
		'type' => 'title',
		'desc' => __('Settings for Policies Settings', 'sensfrx_fpwoo'),
		'id'   => 'policies_settings_title',
	),
	'policies_settings_option' => array(
		'name' => __('Policies Settings Option', 'sensfrx_fpwoo'),
		'type' => 'text',
		'desc' => __('Enter your Policies Settings option', 'sensfrx_fpwoo'),
		'id'   => 'policies_settings_option',
	),
	'notifications_alerts_title' => array(
		'name' => __('Notifications/Alerts', 'sensfrx_fpwoo'),
		'type' => 'title',
		'desc' => __('Settings for Notifications/Alerts', 'sensfrx_fpwoo'),
		'id'   => 'notifications_alerts_title',
	),
	'notifications_alerts_option' => array(
		'name' => __('Notifications/Alerts Option', 'sensfrx_fpwoo'),
		'type' => 'text',
		'desc' => __('Enter your Notifications/Alerts option', 'sensfrx_fpwoo'),
		'id'   => 'notifications_alerts_option',
	),
	'license_information_title' => array(
		'name' => __('License Information', 'sensfrx_fpwoo'),
		'type' => 'title',
		'desc' => __('Settings for License Information', 'sensfrx_fpwoo'),
		'id'   => 'license_information_title',
	),
	'license_information_option' => array(
		'name' => __('License Information Option', 'sensfrx_fpwoo'),
		'type' => 'text',
		'desc' => __('Enter your License Information option', 'sensfrx_fpwoo'),
		'id'   => 'license_information_option',
	),
	'account_privacy_title' => array(
		'name' => __('Account & Privacy', 'sensfrx_fpwoo'),
		'type' => 'title',
		'desc' => __('Settings for Account & Privacy', 'sensfrx_fpwoo'),
		'id'   => 'account_privacy_title',
	),
	'account_privacy_option' => array(
		'name' => __('Account & Privacy Option', 'sensfrx_fpwoo'),
		'type' => 'text',
		'desc' => __('Enter your Account & Privacy option', 'sensfrx_fpwoo'),
		'id'   => 'account_privacy_option',
	),
	'profile_title' => array(
		'name' => __('Profile', 'sensfrx_fpwoo'),
		'type' => 'title',
		'desc' => __('Settings for Profile', 'sensfrx_fpwoo'),
		'id'   => 'profile_title',
	),
	'profile_option' => array(
		'name' => __('Profile Option', 'sensfrx_fpwoo'),
		'type' => 'text',
		'desc' => __('Enter your Profile option', 'sensfrx_fpwoo'),
		'id'   => 'profile_option',
	),
	'sensfrx_custom_slug' => array(
		'name' => __('sensfrx_custom_slug Option', 'sensfrx_fpwoo'),
		'type' => 'text',
		'desc' => __('Enter your sensfrx_custom_slug option', 'sensfrx_fpwoo'),
		'id'   => 'sensfrx_custom_slug',
	),
	'sensfrx_section_end'   => array(
		'type' => 'sectionend',
		'id'   => 'sensfrx_section_end',
	),
));
