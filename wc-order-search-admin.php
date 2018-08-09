<?php

/**
 * Plugin Name: WooCommerce Order Search Admin powered by Algolia
 * Plugin URI:  https://github.com/rayrutjes/wc-order-search-admin
 * Description: Search for WooCommerce orders in the admin at the speed of thought with Algolia.
 * Author:      Raymond Rutjes
 * Author URI:  https://github.com/rayrutjes/
 * Version:     1.9.0
 * Domain Path: /languages.
 */
define( 'WC_OSA_VERSION', '1.9.0' );

if ( ! defined( 'WC_OSA_FILE' ) ) {
	define( 'WC_OSA_FILE', __FILE__ );
}

if ( ! defined( 'WC_OSA_PATH' ) ) {
	define( 'WC_OSA_PATH', plugin_dir_path( WC_OSA_FILE ) );
}

add_action(
	'init', function () {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'wc-order-search-admin' );

		load_textdomain( 'wc-order-search-admin', WP_LANG_DIR . '/wc-order-search-admin/wc-order-search-admin-' . $locale . '.mo' );
		load_plugin_textdomain( 'wc-order-search-admin', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
	}
);

add_filter(
	'plugin_action_links_' . plugin_basename( __FILE__ ), function ( $links ) {
		$links[] = '<a href="' . admin_url( 'options-general.php?page=wc_osa_options' ) . '">' . __( 'Settings', 'wc-order-search-admin' ) . '</a>';

		return $links;
	}
);

add_action(
	'plugins_loaded',
	function () {
		if ( ! defined( 'WC_VERSION' ) ) {
			add_action(
				'admin_notices', function () {
				?>
				<div class="notice notice-error">
					<p><?php esc_html_e( 'WooCommerce Order Search Admin requires the WooCommerce plugin to be active.', 'wc-order-search-admin' ); ?></p>
				</div>
				<?php

				}
			);

			return;
		}

		// Composer dependencies
		require_once WC_OSA_PATH . 'libs/vendor/algolia/algoliasearch-client-php/algoliasearch.php';
		require_once WC_OSA_PATH . 'libs/vendor/autoload.php';

		// Resources
		require_once WC_OSA_PATH . 'includes/class-order-change-listener.php';
		require_once WC_OSA_PATH . 'includes/class-orders-index.php';
		require_once WC_OSA_PATH . 'includes/class-options.php';
		require_once WC_OSA_PATH . 'includes/class-plugin.php';

		$plugin = \WC_Order_Search_Admin\Plugin::initialize( new \WC_Order_Search_Admin\Options() );

		if ( is_admin() ) {
			require_once WC_OSA_PATH . 'includes/admin/class-options-page.php';
			require_once WC_OSA_PATH . 'includes/admin/class-orders-list-page.php';
			require_once WC_OSA_PATH . 'includes/admin/class-ajax-reindex.php';
			require_once WC_OSA_PATH . 'includes/admin/class-ajax-indexing-options-form.php';
			require_once WC_OSA_PATH . 'includes/admin/class-ajax-algolia-account-settings-form.php';
			new \WC_Order_Search_Admin\Admin\Options_Page( $plugin->get_options() );
			new \WC_Order_Search_Admin\Admin\Orders_List_Page( $plugin->get_options() );
			if ( $plugin->get_options()->has_algolia_account_settings() ) {
				new \WC_Order_Search_Admin\Admin\Ajax_Reindex( $plugin->get_orders_index(), $plugin->get_options() );
			}
			new \WC_Order_Search_Admin\Admin\Ajax_Indexing_Options_Form( $plugin->get_options() );
			new \WC_Order_Search_Admin\Admin\Ajax_Algolia_Account_Settings_Form( $plugin->get_options() );
		}

		// WP CLI commands
		if ( defined( 'WP_CLI' ) && WP_CLI && $plugin->get_options()->has_algolia_account_settings() ) {
			require_once WC_OSA_PATH . 'includes/class-commands.php';
			$commands = new \WC_Order_Search_Admin\Commands( $plugin->get_orders_index(), $plugin->get_options() );
			WP_CLI::add_command( 'orders', $commands );
		}
	}
);
