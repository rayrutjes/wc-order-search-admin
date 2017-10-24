<?php

/*
 * This file is part of WooCommerce Order Search Admin plugin for WordPress.
 * (c) Raymond Rutjes <raymond.rutjes@gmail.com>
 * This source file is subject to the GPLv2 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace WC_Order_Search_Admin\Admin;

use WC_Order_Search_Admin\Options;

class AjaxIndexingOptionsForm {

	/**
	 * @var Options
	 */
	private $options;

	/**
	 * @param Options $options
	 */
	public function __construct( Options $options ) {
		$this->options = $options;

		add_action( 'wp_ajax_wc_osa_save_indexing_options', array( $this, 'saveIndexingOptions' ) );
	}

	public function saveIndexingOptions() {
		if ( ! isset( $_POST['orders_index_name'] ) || ! isset( $_POST['orders_per_batch'] ) ) {
			wp_die( 'Hacker' );
		}

		try {
			$this->options->setOrdersIndexName( $_POST['orders_index_name'] );
		} catch ( \InvalidArgumentException $exception ) {
			wp_send_json_error(
				array(
					'message' => $exception->getMessage(),
				)
			);
		}

		$this->options->setOrdersToIndexPerBatchCount( $_POST['orders_per_batch'] );

		$response = array(
			'success' => true,
			'message' => __( 'Your indexing options have been saved. If you changed the index name, you will need to re-index your orders.', 'wc-order-search-admin' ),
		);

		wp_send_json( $response );
	}
}
