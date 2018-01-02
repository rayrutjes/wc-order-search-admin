<?php

/*
 * This file is part of WooCommerce Order Search Admin plugin for WordPress.
 * (c) Raymond Rutjes <raymond.rutjes@gmail.com>
 * This source file is subject to the GPLv2 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace WC_Order_Search_Admin\Admin;

use WC_Order_Search_Admin\AlgoliaSearch\AlgoliaException;
use WC_Order_Search_Admin\Options;
use WC_Order_Search_Admin\Orders_Index;

class Ajax_Reindex {

	/**
	 * @var Orders_Index
	 */
	private $orders_index;

	/**
	 * @var Options
	 */
	private $options;

	/**
	 * @param Orders_Index $orders_index
	 * @param Options     $options
	 */
	public function __construct( Orders_Index $orders_index, Options $options ) {
		$this->options = $options;

		add_action( 'wp_ajax_wc_osa_reindex', array( $this, 're_index' ) );
		$this->orders_index = $orders_index;
	}

	public function re_index() {
		check_ajax_referer( 're_index_nonce' );
		if ( isset( $_POST['page'] ) ) {
			$page = (int) $_POST['page'];
		} else {
			$page = 1;
		}

		if ( 1 === $page ) {
			try {
				$this->orders_index->clear();
				$this->orders_index->pushSettings();
			} catch ( AlgoliaException $exception ) {
				wp_send_json_error(
					array(
						'message' => $exception->getMessage(),
					)
				);
			}
		}

		$per_page    = $this->options->get_orders_to_index_per_batch_count();
		$total_pages = $this->orders_index->getTotalPagesCount( $this->options->get_orders_to_index_per_batch_count() );

		try {
			$records_pushed_count = $this->orders_index->pushRecords( $page, $per_page );
		} catch ( AlgoliaException $exception ) {
			wp_send_json_error(
				array(
					'message' => $exception->getMessage(),
				)
			);
		}

		$response = array(
			'recordsPushedCount' => $records_pushed_count,
			'totalPagesCount'    => $total_pages,
			'finished'           => $page >= $total_pages,
		);

		wp_send_json( $response );
	}
}
