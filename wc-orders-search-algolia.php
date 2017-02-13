<?php
/**
 * Plugin Name: WC Orders Search Algolia
 * Description: Adds a dropdown to the orders admin screen to find orders as you type
 * Version: 0.1.0
 * Author Name: Raymond Rutjes
 */

add_action( 'plugins_loaded', function() {
	// Check `composer install` has been ran
	if ( file_exists( dirname( __FILE__ ) . '/vendor' ) ) { 
		// Composer dependencies
		require_once( 'vendor/autoload.php' );

		// Resources
		require_once( 'inc/OrdersIndex.php' );
		require_once( 'inc/Options.php' );
		require_once( 'inc/Plugin.php' );

		$plugin = \AlgoliaOrdersSearch\Plugin::initialize(new \AlgoliaOrdersSearch\Options());

		if(is_admin()) {
            require_once( 'inc/admin/OptionsPage.php' );
		    new \AlgoliaOrdersSearch\Admin\OptionsPage();
        }
		// WP CLI commands
		if ( defined('WP_CLI') && WP_CLI ) {
			require_once( 'inc/Commands.php');
			$commands = new \AlgoliaOrdersSearch\Commands($plugin->getOrdersIndex(), $plugin->getOptions());
            WP_CLI::add_command( 'orders', $commands );
		}
	}
} );
