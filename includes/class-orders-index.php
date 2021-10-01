<?php

/*
 * This file is part of WooCommerce Order Search Admin plugin for WordPress.
 * (c) Raymond Rutjes <raymond.rutjes@gmail.com>
 * This source file is subject to the GPLv2 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace WC_Order_Search_Admin;

use WC_Order_Search_Admin\Algolia\Index\Index;
use WC_Order_Search_Admin\Algolia\Index\IndexSettings;
use WC_Order_Search_Admin\Algolia\Index\RecordsProvider;
use WC_Order_Search_Admin\AlgoliaSearch\Client;

class Orders_Index extends Index implements RecordsProvider {

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var Client
	 */
	private $client;

	/**
	 * @param string $name
	 * @param Client $client
	 */
	public function __construct( $name, Client $client ) {
		$this->name   = $name;
		$this->client = $client;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $order_id
	 */
	public function deleteRecordsByOrderId( $order_id ) {
		$this->getAlgoliaIndex()->deleteObject( (string) $order_id );
	}

	/**
	 * @param int $per_page
	 *
	 * @return int
	 */
	public function getTotalPagesCount( $per_page ) {
		$results = $this->newQuery(
			array(
				'posts_per_page' => (int) $per_page,
			)
		);

		return (int) $results->max_num_pages;
	}

	/**
	 * @param int $page
	 * @param int $per_page
	 *
	 * @return array
	 */
	public function getRecords( $page, $per_page ) {
		$query = $this->newQuery(
			array(
				'posts_per_page' => $per_page,
				'paged'          => $page,
			)
		);

		return $this->getRecordsForQuery( $query );
	}

	/**
	 * @param \WC_Abstract_Order $order
	 *
	 * @return int
	 */
	public function pushRecordsForOrder( \WC_Abstract_Order $order ) {
		$records             = $this->getRecordsForOrder( $order );
		$total_records_count = count( $records );
		if ( 0 === $total_records_count ) {
			return 0;
		}

		$this->getAlgoliaIndex()->addObjects( $records );

		return $total_records_count;
	}

	/**
	 * @param mixed $id
	 *
	 * @return array
	 */
	public function getRecordsForId( $id ) {
		$factory = new \WC_Order_Factory();
		$order   = $factory->get_order( $id );

		if ( ! $order instanceof \WC_Abstract_Order ) {
			return array();
		}

		return $this->getRecordsForOrder( $order );
	}

	/**
	 * @return RecordsProvider
	 */
	protected function getRecordsProvider() {
		return $this;
	}

	/**
	 * @return IndexSettings
	 */
	protected function getSettings() {
		return new IndexSettings(
			array(
				'searchableAttributes'             => array(
					'id',
					'number',
					'customer.display_name',
					'customer.email',
					'billing.display_name',
					'shipping.display_name',
					'billing.email',
					'billing.phone',
					'billing.company',
					'shipping.company',
					'billing.address_1',
					'shipping.address_1',
					'billing.address_2',
					'shipping.address_2',
					'billing.city',
					'shipping.city',
					'billing.state',
					'shipping.state',
					'billing.postcode',
					'shipping.postcode',
					'billing.country',
					'shipping.country',
					'items.sku',
					'status_name',
					'order_total',
				),
				'disableTypoToleranceOnAttributes' => array(
					'id',
					'number',
					'items.sku',
					'billing.phone',
					'order_total',
					'billing.postcode',
					'shipping.postcode',
				),
				'customRanking'                    => array(
					'desc(date_timestamp)',
				),
				'attributesForFaceting'            => array(
					'customer.display_name',
					'type',
					'items.sku',
					'order_total',
				),
			)
		);
	}

	/**
	 * @return Client
	 */
	protected function getAlgoliaClient() {
		return $this->client;
	}

	/**
	 * @param array $args
	 *
	 * @return \WP_Query
	 */
	private function newQuery( array $args = array() ) {
		$default_args = array(
			'post_type'   => wc_get_order_types(),
			'post_status' => array_keys( wc_get_order_statuses() ),
		);

		$args  = array_merge( $default_args, $args );
		$query = new \WP_Query( $args );

		return $query;
	}

	/**
	 * @param \WC_Abstract_Order $order
	 *
	 * @return array
	 */
	private function getRecordsForOrder( \WC_Abstract_Order $order ) {
		if ( ! defined( 'WC_VERSION' ) ) {
			return array();
		}

		if ( ! $order instanceof \WC_Order ) {
			// Only support default order type for now.
			return array();
		}

		$is_wc_3 = version_compare( '3', WC_VERSION ) <= 0;

		if ( ! $is_wc_3 ) {
			// We are dealing with WC 2.x
			$record = array(
				'objectID'              => (int) $order->id,
				'id'                    => (int) $order->id,
				'type'                  => $order->order_type,
				'number'                => (string) $order->get_order_number(),
				'status'                => $order->get_status(),
				'status_name'           => wc_get_order_status_name( $order->get_status() ),
				'date_timestamp'        => strtotime( $order->order_date ),
				'date_formatted'        => date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ),
				'order_total'           => (float) $order->get_total(),
				'formatted_order_total' => $order->get_formatted_order_total(),
				'items_count'           => (int) $order->get_item_count(),
				'payment_method_title'  => $order->payment_method_title,
				'shipping_method_title' => $order->shipping_method_title,
			);
		} else {
			// We are dealing with WC 3.x
			$date_created           = $order->get_date_created();
			$date_created_timestamp = null !== $date_created ? $date_created->getTimestamp() : 0;
			$date_created_i18n      = null !== $date_created ? $date_created->date_i18n( get_option( 'date_format' ) ) : '';

			$record = array(
				'objectID'              => (int) $order->get_id(),
				'id'                    => (int) $order->get_id(),
				'type'                  => $order->get_type(),
				'number'                => (string) $order->get_order_number(),
				'status'                => $order->get_status(),
				'status_name'           => wc_get_order_status_name( $order->get_status() ),
				'date_timestamp'        => $date_created_timestamp,
				'date_formatted'        => $date_created_i18n,
				'order_total'           => (float) $order->get_total(),
				'formatted_order_total' => $order->get_formatted_order_total(),
				'items_count'           => (int) $order->get_item_count(),
				'payment_method_title'  => $order->get_payment_method_title(),
				'shipping_method_title' => $order->get_shipping_method(),
			);
		}

		// Add user info.
		$user = $order->get_user();
		if ( $user ) {
			$record['customer'] = array(
				'id'           => (int) $user->ID,
				'display_name' => $user->first_name . ' ' . $user->last_name,
				'email'        => $user->user_email,
			);
		}

		$billing_country   = $is_wc_3 ? $order->get_billing_country() : $order->billing_country;
		$billing_country   = isset( WC()->countries->countries[ $billing_country ] ) ? WC()->countries->countries[ $billing_country ] : $billing_country;
		$record['billing'] = array(
			'display_name' => $order->get_formatted_billing_full_name(),
			'email'        => $is_wc_3 ? $order->get_billing_email() : $order->billing_email,
			'phone'        => $is_wc_3 ? $order->get_billing_phone() : $order->billing_phone,
			'company'      => $is_wc_3 ? $order->get_billing_company() : $order->billing_company,
			'address_1'    => $is_wc_3 ? $order->get_billing_address_1() : $order->billing_address_1,
			'address_2'    => $is_wc_3 ? $order->get_billing_address_2() : $order->billing_address_2,
			'city'         => $is_wc_3 ? $order->get_billing_city() : $order->billing_city,
			'state'        => $is_wc_3 ? $order->get_billing_state() : $order->billing_state,
			'postcode'     => $is_wc_3 ? $order->get_billing_postcode() : $order->billing_postcode,
			'country'      => $billing_country,
		);

		$shipping_country   = $is_wc_3 ? $order->get_shipping_country() : $order->shipping_country;
		$shipping_country   = isset( WC()->countries->countries[ $shipping_country ] ) ? WC()->countries->countries[ $shipping_country ] : $shipping_country;
		$record['shipping'] = array(
			'display_name' => $order->get_formatted_shipping_full_name(),
			'company'      => $is_wc_3 ? $order->get_shipping_company() : $order->shipping_company,
			'address_1'    => $is_wc_3 ? $order->get_shipping_address_1() : $order->shipping_address_1,
			'address_2'    => $is_wc_3 ? $order->get_shipping_address_2() : $order->shipping_address_2,
			'city'         => $is_wc_3 ? $order->get_shipping_city() : $order->shipping_city,
			'state'        => $is_wc_3 ? $order->get_shipping_state() : $order->shipping_state,
			'postcode'     => $is_wc_3 ? $order->get_shipping_postcode() : $order->shipping_postcode,
			'country'      => $shipping_country,
		);

		// Add items.
		$record['items'] = array();
		foreach ( $order->get_items() as $item_id => $item ) {
			if ( version_compare( WC_VERSION, '4.4.0', '>=' ) ) {
				$product = $item->get_product();
			} else {
				$product = $order->get_product_from_item( $item );
			}
			$record['items'][] = array(
				'id'   => (int) $item_id,
				'name' => apply_filters( 'woocommerce_order_item_name', esc_html( $item['name'] ), $item, false ),
				'qty'  => (int) $item['qty'],
				'sku'  => $product instanceof \WC_Product ? $product->get_sku() : '',
			);
		}

		return array( $record );
	}

	/**
	 * @param \WP_Query $query
	 *
	 * @return array
	 */
	private function getRecordsForQuery( \WP_Query $query ) {
		$records = array();
		$factory = new \WC_Order_Factory();
		foreach ( $query->posts as $post ) {
			$order = $factory->get_order( $post );
			if ( ! $order instanceof \WC_Abstract_Order ) {
				continue;
			}
			$records = array_merge( $records, $this->getRecordsForOrder( $order ) );
		}

		return $records;
	}
}
