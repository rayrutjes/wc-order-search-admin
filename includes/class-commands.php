<?php

/*
 * This file is part of WooCommerce Order Search Admin plugin for WordPress.
 * (c) Raymond Rutjes <raymond.rutjes@gmail.com>
 * This source file is subject to the GPLv2 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace WC_Order_Search_Admin;

use WP_CLI;
use WP_CLI_Command;

class Commands extends WP_CLI_Command {

	/**
	 * @var Orders_Index
	 */
	private $index;

	/**
	 * @var Options
	 */
	private $options;

	/**
	 * @var \cli\progress\Bar
	 */
	private $progress;

	/**
	 * @param Orders_Index $index
	 * @param Options     $options
	 */
	public function __construct( Orders_Index $index, Options $options ) {
		$this->index   = $index;
		$this->options = $options;
	}

	/**
	 * ReIndex all orders in Algolia.
	 *
	 * ## EXAMPLES
	 *
	 *     wp orders reindex
	 *
	 * @when before_wp_load
	 * @alias re-index
	 *
	 * @param mixed $args
	 * @param mixed $assoc_args
	 */
	public function reindex( $args, $assoc_args ) {
		/* translators: placeholder will contain the index name. */
		WP_CLI::log( sprintf( __( 'About to clear existing orders from index %s...', 'wc-order-search-admin' ), $this->index->getName() ) );
		$this->index->clear();
		/* translators: placeholder will contain the index name. */
		WP_CLI::success( sprintf( __( 'Correctly cleared orders from index "%s".', 'wc-order-search-admin' ), $this->index->getName() ) );

		/* translators: placeholder will contain the index name. */
		WP_CLI::log( sprintf( __( 'About push the settings for index %s...', 'wc-order-search-admin' ), $this->index->getName() ) );
		$this->index->pushSettings();
		/* translators: placeholder will contain the index name. */
		WP_CLI::success( sprintf( __( 'Correctly pushed settings for index "%s".', 'wc-order-search-admin' ), $this->index->getName() ) );

		WP_CLI::log( __( 'About to push all orders to Algolia. Please be patient...', 'wc-order-search-admin' ) );

		$start = microtime( true );

		$per_page = $this->options->get_orders_to_index_per_batch_count();

		$self = $this;

		$total_records_count = $this->index->reIndex(
			false,
			$per_page,
			function ( $records, $page, $total_pages ) use ( $self ) {
				if ( null === $self->progress ) {
					$self->progress = WP_CLI\Utils\make_progress_bar( __( 'Indexing WooCommerce orders', 'wc-order-search-admin' ), $total_pages );
				}
				$self->progress->tick();
			}
		);

		if ( null !== $this->progress ) {
			$this->progress->finish();
		}

		$elapsed = microtime( true ) - $start;

		/* translators: 1st placeholder will contain the total number of orders indexed and second placeholder indicates the processing time in seconds. */
		WP_CLI::success( sprintf( __( '%1$d orders indexed in %2$d seconds!', 'wc-order-search-admin' ), $total_records_count, $elapsed ) );
	}
}
