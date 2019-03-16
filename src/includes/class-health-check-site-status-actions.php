<?php

// Make sure the file is not directly accessible.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

class Health_Check_Site_Status_Actions {

	public function __construct() {
		$this->init();
	}

	public function init() {
		add_action( 'wp_ajax_health-check-site-status-action', array( $this, 'maybe_do_site_action' ) );
	}

	public function maybe_do_site_action() {
		check_ajax_referer( 'health-check-site-status-action' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}

		$function = sprintf(
			'action_%s',
			$_POST['status_action']
		);

		// If the method isn't part of our stack, return as it's likely added by another plugin or theme so they can catch it.
		if ( ! method_exists( $this, $function ) ) {
			return;
		}

		call_user_func( array( $this, $function ) );
	}

	function action_update_plugins() {
		if ( ! class_exists( 'WP_Upgrader' ) ) {
			include_once( trailingslashit( ABSPATH ) . 'wp-admin/includes/class-wp-upgrader.php' );
		}
		if ( ! class_exists( 'Plugin_Upgrader' ) ) {
			include_once( trailingslashit( ABSPATH ) . 'wp-admin/includes/class-plugin-upgrader.php' );
		}
		$upgrader = new Plugin_Upgrader();

		$need_updates      = array();
		$need_updates_list = get_plugin_updates();

		foreach ( $need_updates_list as $slug => $plugin ) {
			$need_updates[] = $slug;
		}

		$updated = $upgrader->bulk_upgrade( $need_updates );

		if ( false === $updated ) {
			wp_send_json_error();
		}

		wp_send_json_success();
	}

	function action_delete_inactive_plugins() {

	}

	function action_update_themes() {

	}

	function action_delete_inactive_themes() {

	}

	function action_lock_debug_log_file() {

	}

	function action_disable_debug_mode_display() {

	}

	function action_enable_site_https() {

	}
}

new Health_Check_Site_Status_Actions();
