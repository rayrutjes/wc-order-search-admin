<?php

namespace AlgoliaOrdersSearch;

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
	public function reIndex( $args, $assoc_args ) {
		WP_CLI::line( 'About to re-index all orders in Algolia. Please be patient...' );
		$index = new OrdersIndex();
		$index->getRecords(1, 10);
	}
}

WP_CLI::add_command( 'orders', 'AlgoliaOrdersSearch\Commands' );
