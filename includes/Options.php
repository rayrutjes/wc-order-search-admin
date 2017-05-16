<?php

/*
 * This file is part of WooCommerce Order Search Admin plugin for WordPress.
 * (c) Raymond Rutjes <raymond.rutjes@gmail.com>
 * This source file is subject to the GPLv2 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace WC_Order_Search_Admin;

class Options
{
    public function hasAlgoliaAccountSettings()
    {
        return !empty($this->getAlgoliaAppId())
            && !empty($this->getAlgoliaSearchApiKey())
            && !empty($this->getAlgoliaAdminApiKey());
    }

    public function clearAlgoliaAccountSettings()
    {
        update_option('wc_osa_alg_app_id', '');
        update_option('wc_osa_alg_search_api_key', '');
        update_option('wc_osa_alg_admin_api_key', '');
    }

    public function getAlgoliaAppId()
    {
        return get_option('wc_osa_alg_app_id', '');
    }

    public function getAlgoliaSearchApiKey()
    {
        return get_option('wc_osa_alg_search_api_key', '');
    }

    public function getAlgoliaAdminApiKey()
    {
        return get_option('wc_osa_alg_admin_api_key', '');
    }

    public function setAlgoliaAccountSettings($appId, $searchKey, $adminKey)
    {
        $appId = trim($appId);
        $this->assertNotEmpty($appId, 'Algolia application ID');
        $searchKey = trim($searchKey);
        $this->assertNotEmpty($searchKey, 'Algolia search only API key');
        $adminKey = trim($adminKey);
        $this->assertNotEmpty($adminKey, 'Algolia admin API key');

        update_option('wc_osa_alg_app_id', $appId);
        update_option('wc_osa_alg_search_api_key', $searchKey);
        update_option('wc_osa_alg_admin_api_key', $adminKey);
    }

    public function getOrdersIndexName()
    {
        return get_option('wc_osa_orders_index_name', 'wc_orders');
    }

    public function setOrdersIndexName($ordersIndexName)
    {
        $ordersIndexName = trim((string) $ordersIndexName);
        $this->assertNotEmpty($ordersIndexName, 'Orders index name');
        update_option('wc_osa_orders_index_name', $ordersIndexName);
    }

    /**
     * @return int
     */
    public function getOrdersToIndexPerBatchCount()
    {
        return (int) get_option('wc_osa_orders_per_batch', 500);
    }

    public function setOrdersToIndexPerBatchCount($perBatch)
    {
        $perBatch = (int) $perBatch;
        if ($perBatch <= 0) {
            $perBatch = 500;
        }

        update_option('wc_osa_orders_per_batch', $perBatch);
    }

    private function assertNotEmpty($value, $attributeName)
    {
        if (strlen($value) === 0) {
            throw new \InvalidArgumentException($attributeName . ' should not be empty.');
        }
    }
}
