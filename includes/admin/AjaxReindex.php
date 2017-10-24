<?php

/*
 * This file is part of WooCommerce Order Search Admin plugin for WordPress.
 * (c) Raymond Rutjes <raymond.rutjes@gmail.com>
 * This source file is subject to the GPLv2 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace WC_Order_Search_Admin\Admin;

use WC_Order_Search_Admin\AlgoliaException;
use WC_Order_Search_Admin\Options;
use WC_Order_Search_Admin\OrdersIndex;

class AjaxReindex {

	/**
	 * @var OrdersIndex
	 */
	private $orders_index;

	/**
	 * @var Options
	 */
	private $options;

	/**
	 * @param OrdersIndex $orders_index
	 * @param Options     $options
	 */
	public function __construct( OrdersIndex $orders_index, Options $options ) {
		$this->options = $options;

		add_action( 'wp_ajax_wc_osa_reindex', array( $this, 'reIndex' ) );
		$this->orders_index = $orders_index;
	}

	public function reIndex() {
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

		$per_page = $this->options->getOrdersToIndexPerBatchCount();
		$total_pages = $this->orders_index->getTotalPagesCount( $this->options->getOrdersToIndexPerBatchCount() );

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
