<?php

/*
 * This file is part of WooCommerce Order Search Admin plugin for WordPress.
 * (c) Raymond Rutjes <raymond.rutjes@gmail.com>
 * This source file is subject to the GPLv2 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace WC_Order_Search_Admin;

class OrderChangeListener {

	/**
	 * @var OrdersIndex
	 */
	private $orders_index;

	/**
	 * @param OrdersIndex $orders_index
	 */
	public function __construct( OrdersIndex $orders_index ) {
		$this->orders_index = $orders_index;
		add_action( 'save_post', array( $this, 'pushOrderRecords' ), 10, 2 );
		add_action( 'before_delete_post', array( $this, 'deleteOrderRecords' ) );
		add_action( 'wp_trash_post', array( $this, 'deleteOrderRecords' ) );
	}

	/**
	 * @param mixed $post_id
	 * @param mixed $post
	 */
	public function pushOrderRecords( $post_id, $post ) {
		if ( 'shop_order' !== $post->post_type
			|| 'auto-draft' === $post->post_status
			|| 'trash' === $post->post_status
		) {
			return;
		}

		$order = wc_get_order( $post_id );
		try {
			$this->orders_index->pushRecordsForOrder( $order );
		} catch ( AlgoliaException $exception ) {
			error_log( $exception->getMessage() );
		}
	}

	public function deleteOrderRecords( $post_id ) {
		$post = get_post( $post_id );

		if ( 'shop_order' !== $post->post_type ) {
			return;
		}

		try {
			$this->orders_index->deleteRecordsByOrderId( $post->ID );
		} catch ( AlgoliaException $exception ) {
			error_log( $exception->getMessage() );
		}
	}
}
