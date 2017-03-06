<?php
/**
 * Plugin Name: Custom Data Stores for WooCommerce
 * Plugin URI: https://mikejolley.com/
 * Description: Adds new data stores to store orders in a custom table.
 * Version: 1.0.0
 * Author: Mike Jolley
 * Author URI: https://mikejolley.com/
 * Requires at least: 4.7
 * Tested up to: 4.7
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class WC_Custom_Table_Data_Stores {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'init' ) );
		add_filter( 'woocommerce_order_data_store', array( $this, 'order_data_store' ) );
		register_activation_hook( __FILE__, array( $this, 'install' ) );
	}

	/**
	 * Init the plugin.
	 */
	public function init() {
		include_once( 'includes/class-wc-order-data-store-custom-table.php' );
	}

	/**
	 * Init the order data store.
	 *
	 * @return string
	 */
	public function order_data_store() {
		return 'WC_Order_Data_Store_Custom_Table';
	}

	/**
	 * Install.
	 */
	public function install() {
		global $wpdb;

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$collate = '';

		if ( $wpdb->has_cap( 'collation' ) ) {
			$collate = $wpdb->get_charset_collate();
		}

		$tables = "
		CREATE TABLE {$wpdb->prefix}woocommerce_orders (
		order_id BIGINT UNSIGNED NOT NULL,
		order_key varchar(100) NOT NULL,
		customer_id BIGINT UNSIGNED NOT NULL,
		billing_first_name varchar(100) NOT NULL,
		billing_last_name varchar(100) NOT NULL,
		billing_company varchar(100) NOT NULL,
		billing_address_1 varchar(200) NOT NULL,
		billing_address_2 varchar(200) NOT NULL,
		billing_city varchar(100) NOT NULL,
		billing_state varchar(100) NOT NULL,
		billing_postcode varchar(100) NOT NULL,
		billing_country varchar(100) NOT NULL,
		billing_email varchar(200) NOT NULL,
		billing_phone varchar(200) NOT NULL,
		shipping_first_name varchar(100) NOT NULL,
		shipping_last_name varchar(100) NOT NULL,
		shipping_company varchar(100) NOT NULL,
		shipping_address_1 varchar(200) NOT NULL,
		shipping_address_2 varchar(200) NOT NULL,
		shipping_city varchar(100) NOT NULL,
		shipping_state varchar(100) NOT NULL,
		shipping_postcode varchar(100) NOT NULL,
		shipping_country varchar(100) NOT NULL,
		payment_method varchar(100) NOT NULL,
		payment_method_title varchar(100) NOT NULL,
		transaction_id varchar(200) NOT NULL,
		customer_ip_address varchar(40) NOT NULL,
		customer_user_agent varchar(200) NOT NULL,
		created_via varchar(200) NOT NULL,
		date_completed datetime NOT NULL,
		date_paid datetime NOT NULL,
		cart_hash varchar(32) NOT NULL,
		customer_note longtext NOT NULL,
		PRIMARY KEY  (order_id)
		) $collate;
		";

		dbDelta( $tables );
	}

}

new WC_Custom_Table_Data_Stores();
