<?php

/*
 * This file is part of AlgoliaIndex library.
 * (c) Raymond Rutjes <raymond.rutjes@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace WC_Order_Search_Admin;

class Plugin {

	/**
	 * @var self
	 */
	private static $instance;

	/**
	 * @var Options
	 */
	private $options;

	/**
	 * @param Options $options
	 */
	private function __construct( Options $options ) {
		global $wp_version;

		$this->options = $options;
		if ( ! $this->options->hasAlgoliaAccountSettings() ) {
			add_action( 'admin_notices', array( $this, 'configureAlgoliaSettingsNotice' ) );

			return;
		}

		$algolia_client = new Client( $options->getAlgoliaAppId(), $options->getAlgoliaAdminApiKey() );

		$integration_name = 'wc-order-search-admin';
		$ua = '; ' . $integration_name . ' integration (' . WC_OSA_VERSION . ')'
			. '; PHP (' . phpversion() . ')'
			. '; WordPress (' . $wp_version . ')';

		Version::$custom_value = $ua;

		$this->orders_index = new OrdersIndex( $options->getOrdersIndexName(), $algolia_client );
		new OrderChangeListener( $this->orders_index );
	}

	/**
	 * @param Options $options
	 *
	 * @return Plugin
	 */
	public static function initialize( Options $options ) {
		if ( null !== self::$instance ) {
			throw new \LogicException( 'Plugin has already been initialized!' );
		}

		self::$instance = new self( $options );

		return self::$instance;
	}

	/**
	 * @return Plugin
	 */
	public static function getInstance() {
		if ( null === self::$instance ) {
			throw new \LogicException( 'Plugin::initialize must be called first!' );
		}

		return self::$instance;
	}

	/**
	 * @return Options
	 */
	public function getOptions() {
		return $this->options;
	}

	/**
	 * @return OrdersIndex
	 */
	public function getOrdersIndex() {
		if ( null === $this->orders_index ) {
			throw new \LogicException( 'Orders index has not be initialized.' );
		}

		return $this->orders_index;
	}

	public function configureAlgoliaSettingsNotice() {
		$screen = get_current_screen();
		if ( 'settings_page_wc_osa_options' === $screen->id ) {
			return;
		} ?>
		<div class="notice notice-success">
			<p><?php esc_html_e( 'You are one step away from being able to have fast and relevant search powered by Algolia for finding WooCommerce orders.', 'wc-order-search-admin' ); ?></p>
			<p><a href="options-general.php?page=wc_osa_options" class="button button-primary"><?php esc_html_e( 'Setup now', 'wc-order-search-admin' ); ?></a></p>
		</div>
		<?php

	}
}
