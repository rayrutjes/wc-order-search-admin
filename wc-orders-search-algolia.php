<?php
/**
 * Plugin Name: WC Orders Search Algolia
 * Description: Adds a dropdown to the orders admin screen to find orders as you type
 * Version: 0.1.0
 * Author Name: Raymond Rutjes
 */

define('AOS_VERSION', '0.1.0');

add_action(
    'plugins_loaded',
    function () {
        // Check `composer install` has been ran
        if (file_exists(dirname(__FILE__) . '/vendor')) {
            // Composer dependencies
            require_once('vendor/autoload.php');

            // Resources
            require_once('inc/OrderChangeListener.php');
            require_once('inc/OrdersIndex.php');
            require_once('inc/Options.php');
            require_once('inc/Plugin.php');

            $plugin = \AlgoliaOrdersSearch\Plugin::initialize(new \AlgoliaOrdersSearch\Options());

            if (is_admin()) {
                require_once('inc/admin/OptionsPage.php');
                require_once('inc/admin/OrdersListPage.php');
                require_once('inc/admin/AjaxReindex.php');
                require_once('inc/admin/AjaxIndexingOptionsForm.php');
                require_once('inc/admin/AjaxAlgoliaAccountSettingsForm.php');
                new \AlgoliaOrdersSearch\Admin\OptionsPage($plugin->getOptions());
                new \AlgoliaOrdersSearch\Admin\OrdersListPage($plugin->getOptions());
                if($plugin->getOptions()->hasAlgoliaAccountSettings()) {
                    new \AlgoliaOrdersSearch\Admin\AjaxReindex($plugin->getOrdersIndex(), $plugin->getOptions());
                }
                new \AlgoliaOrdersSearch\Admin\AjaxIndexingOptionsForm($plugin);
                new \AlgoliaOrdersSearch\Admin\AjaxAlgoliaAccountSettingsForm($plugin->getOptions());
            }

            // WP CLI commands
            if (defined('WP_CLI') && WP_CLI) {
                require_once('inc/Commands.php');
                $commands = new \AlgoliaOrdersSearch\Commands($plugin->getOrdersIndex(), $plugin->getOptions());
                WP_CLI::add_command('orders', $commands);
            }
        }
    }
);
