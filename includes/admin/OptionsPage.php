<?php

/*
 * This file is part of WooCommerce Order Search Admin plugin for WordPress.
 * (c) Raymond Rutjes <raymond.rutjes@gmail.com>
 * This source file is subject to the GPLv2 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace WC_Order_Search_Admin\Admin;

use WC_Order_Search_Admin\Options;

class OptionsPage {

	/**
	 * @var Options
	 */
	private $options;

	/**
	 * @param Options $options
	 */
	public function __construct( Options $options ) {
		add_action( 'admin_menu', array( $this, 'register_page_in_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		$this->options = $options;
	}

	public function enqueue_scripts() {
		$screen = get_current_screen();
		if ( 'settings_page_wc_osa_options' !== $screen->id ) {
			return;
		}

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		wp_enqueue_script( 'wc_osa_orders_search', plugin_dir_url( WC_OSA_FILE ) . 'assets/js/reindex-orders-button' . $suffix . '.js', array( 'jquery' ), WC_OSA_VERSION, true );
		wp_enqueue_script( 'wc_osa_ajax_forms', plugin_dir_url( WC_OSA_FILE ) . 'assets/js/ajax-forms' . $suffix . '.js', array( 'jquery' ), WC_OSA_VERSION, true );
	}

	public function register_page_in_menu() {
		add_options_page(
			__( 'WooCommerce Order Search Admin', 'wc-order-search-admin' ),
			__( 'WooCommerce Order Search Admin', 'wc-order-search-admin' ),
			'manage_options',
			'wc_osa_options',
			array( $this, 'render_page' )
		);
	}

	public function render_page() {
		// check user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		include 'views/options.php';
	}
}
