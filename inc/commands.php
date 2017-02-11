<?php

namespace OrdersAlgoliaSearch;

use WP_CLI, WP_CLI_Command;

class Commands extends WP_CLI_Command {
	/**
	 * ReIndex all orders in Algolia.
	 * 
	 * ## EXAMPLES
	 *
	 *     wp orders reIndex
	 *
	 * @when before_wp_load
	 */
	public function seed( $args, $assoc_args ) {
		WP_CLI::line( 'About to re-index all orders in Algolia. Please be patient...' );
	}
}

WP_CLI::add_command( 'orders', 'OrdersAlgoliaSearch\Commands' );
