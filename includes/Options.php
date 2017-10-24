<?php

/*
 * This file is part of WooCommerce Order Search Admin plugin for WordPress.
 * (c) Raymond Rutjes <raymond.rutjes@gmail.com>
 * This source file is subject to the GPLv2 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace WC_Order_Search_Admin;

class Options {

	public function hasAlgoliaAccountSettings() {
		$app_id = $this->getAlgoliaAppId();
		$search_api_key = $this->getAlgoliaSearchApiKey();
		$admin_api_key = $this->getAlgoliaAdminApiKey();
		return ! empty( $app_id )
			&& ! empty( $search_api_key )
			&& ! empty( $admin_api_key );
	}

	public function clearAlgoliaAccountSettings() {
		update_option( 'wc_osa_alg_app_id', '' );
		update_option( 'wc_osa_alg_search_api_key', '' );
		update_option( 'wc_osa_alg_admin_api_key', '' );
	}

	public function getAlgoliaAppId() {
		return get_option( 'wc_osa_alg_app_id', '' );
	}

	public function getAlgoliaSearchApiKey() {
		return get_option( 'wc_osa_alg_search_api_key', '' );
	}

	public function getAlgoliaAdminApiKey() {
		return get_option( 'wc_osa_alg_admin_api_key', '' );
	}

	public function setAlgoliaAccountSettings( $app_id, $search_key, $admin_key ) {
		$app_id = trim( $app_id );
		$this->assertNotEmpty( $app_id, 'Algolia application ID' );
		$search_key = trim( $search_key );
		$this->assertNotEmpty( $search_key, 'Algolia search only API key' );
		$admin_key = trim( $admin_key );
		$this->assertNotEmpty( $admin_key, 'Algolia admin API key' );

		update_option( 'wc_osa_alg_app_id', $app_id );
		update_option( 'wc_osa_alg_search_api_key', $search_key );
		update_option( 'wc_osa_alg_admin_api_key', $admin_key );
	}

	public function getOrdersIndexName() {
		return get_option( 'wc_osa_orders_index_name', 'wc_orders' );
	}

	public function setOrdersIndexName( $orders_index_name ) {
		$orders_index_name = trim( (string) $orders_index_name );
		$this->assertNotEmpty( $orders_index_name, 'Orders index name' );
		update_option( 'wc_osa_orders_index_name', $orders_index_name );
	}

	/**
	 * @return int
	 */
	public function getOrdersToIndexPerBatchCount() {
		return (int) get_option( 'wc_osa_orders_per_batch', 500 );
	}

	public function setOrdersToIndexPerBatchCount( $per_batch ) {
		$per_batch = (int) $per_batch;
		if ( $per_batch <= 0 ) {
			$per_batch = 500;
		}

		update_option( 'wc_osa_orders_per_batch', $per_batch );
	}

	private function assertNotEmpty( $value, $attribute_name ) {
		if ( strlen( $value ) === 0 ) {
			throw new \InvalidArgumentException( $attribute_name . ' should not be empty.' );
		}
	}
}
