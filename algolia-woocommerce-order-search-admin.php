<?php

/**
 * Plugin Name: Algolia WooCommerce Order Search Admin
 * Description: Adds a dropdown to the orders admin screen to find orders as you type
 * Author: Raymond Rutjes
 * Version: 0.7.0
 * Domain Path:     /languages
 */

define('AOS_VERSION', '0.7.0');

add_action( 'init', function() {
    $locale = apply_filters( 'plugin_locale', get_locale(), 'algolia-woocommerce-order-search-admin' );

    load_textdomain( 'algolia-woocommerce-order-search-admin', WP_LANG_DIR . '/algolia-woocommerce-order-search-admin/algolia-woocommerce-order-search-admin-' . $locale . '.mo' );
    load_plugin_textdomain( 'algolia-woocommerce-order-search-admin', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
});

add_action(
    'plugins_loaded',
    function () {
        if(!defined( 'WC_VERSION' )) {
            add_action('admin_notices', function() {
                ?>
                <div class="notice notice-error">
                    <p><?php esc_html_e('WC Orders Search Algolia requires the WooCommerce plugin to be active.', 'algolia-woocommerce-order-search-admin'); ?></p>
                </div>
                <?php
            });

            return;
        };

        // Composer dependencies
        require_once 'libs/autoload.php';

        // Resources
        require_once 'includes/OrderChangeListener.php';
        require_once 'includes/OrdersIndex.php';
        require_once 'includes/Options.php';
        require_once 'includes/Plugin.php';

        $plugin = \AlgoliaWooCommerceOrderSearchAdmin\Plugin::initialize(new \AlgoliaWooCommerceOrderSearchAdmin\Options());

        if (is_admin()) {
            require_once 'includes/admin/OptionsPage.php';
            require_once 'includes/admin/OrdersListPage.php';
            require_once 'includes/admin/AjaxReindex.php';
            require_once 'includes/admin/AjaxIndexingOptionsForm.php';
            require_once 'includes/admin/AjaxAlgoliaAccountSettingsForm.php';
            new \AlgoliaWooCommerceOrderSearchAdmin\Admin\OptionsPage($plugin->getOptions());
            new \AlgoliaWooCommerceOrderSearchAdmin\Admin\OrdersListPage($plugin->getOptions());
            if ($plugin->getOptions()->hasAlgoliaAccountSettings()) {
                new \AlgoliaWooCommerceOrderSearchAdmin\Admin\AjaxReindex($plugin->getOrdersIndex(), $plugin->getOptions());
            }
            new \AlgoliaWooCommerceOrderSearchAdmin\Admin\AjaxIndexingOptionsForm($plugin->getOptions());
            new \AlgoliaWooCommerceOrderSearchAdmin\Admin\AjaxAlgoliaAccountSettingsForm($plugin->getOptions());
        }

        // WP CLI commands
        if (defined('WP_CLI') && WP_CLI && $plugin->getOptions()->hasAlgoliaAccountSettings()) {
            require_once 'includes/Commands.php';
            $commands = new \AlgoliaWooCommerceOrderSearchAdmin\Commands($plugin->getOrdersIndex(), $plugin->getOptions());
            WP_CLI::add_command('orders', $commands);
        }

    }
);
