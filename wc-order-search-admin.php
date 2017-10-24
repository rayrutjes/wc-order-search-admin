<?php

/**
 * Plugin Name: WooCommerce Order Search Admin powered by Algolia
 * Plugin URI:  https://github.com/rayrutjes/wc-order-search-admin
 * Description: Search for WooCommerce orders in the admin at the speed of thought with Algolia.
 * Author:      Raymond Rutjes
 * Author URI:  https://github.com/rayrutjes/
 * Version:     1.1.1
 * Domain Path: /languages.
 */
define( 'WC_OSA_VERSION', '1.1.1' );

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
		require_once WC_OSA_PATH . 'libs/autoload.php';

		// Resources
		require_once WC_OSA_PATH . 'includes/OrderChangeListener.php';
		require_once WC_OSA_PATH . 'includes/OrdersIndex.php';
		require_once WC_OSA_PATH . 'includes/Options.php';
		require_once WC_OSA_PATH . 'includes/Plugin.php';

		$plugin = \WC_Order_Search_Admin\Plugin::initialize( new \WC_Order_Search_Admin\Options() );

		if ( is_admin() ) {
			require_once WC_OSA_PATH . 'includes/admin/OptionsPage.php';
			require_once WC_OSA_PATH . 'includes/admin/OrdersListPage.php';
			require_once WC_OSA_PATH . 'includes/admin/AjaxReindex.php';
			require_once WC_OSA_PATH . 'includes/admin/AjaxIndexingOptionsForm.php';
			require_once WC_OSA_PATH . 'includes/admin/AjaxAlgoliaAccountSettingsForm.php';
			new \WC_Order_Search_Admin\Admin\OptionsPage( $plugin->getOptions() );
			new \WC_Order_Search_Admin\Admin\OrdersListPage( $plugin->getOptions() );
			if ( $plugin->getOptions()->hasAlgoliaAccountSettings() ) {
				new \WC_Order_Search_Admin\Admin\AjaxReindex( $plugin->getOrdersIndex(), $plugin->getOptions() );
			}
			new \WC_Order_Search_Admin\Admin\AjaxIndexingOptionsForm( $plugin->getOptions() );
			new \WC_Order_Search_Admin\Admin\AjaxAlgoliaAccountSettingsForm( $plugin->getOptions() );
		}

		// WP CLI commands
		if ( defined( 'WP_CLI' ) && WP_CLI && $plugin->getOptions()->hasAlgoliaAccountSettings() ) {
			require_once WC_OSA_PATH . 'includes/Commands.php';
			$commands = new \WC_Order_Search_Admin\Commands( $plugin->getOrdersIndex(), $plugin->getOptions() );
			WP_CLI::add_command( 'orders', $commands );
		}
	}
);
