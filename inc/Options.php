<?php
/**
 * Created by PhpStorm.
 * User: raymond
 * Date: 11/02/2017
 * Time: 19:06
 */

namespace AlgoliaOrdersSearch;

class Options
{
    public function getAlgoliaAdminApiKey()
    {
        return get_option('wcos_alg_admin_api_key', '3b267a8ebd94792da44bc5f2b7b2b5be');
    }

    public function getAlgoliaSearchApiKey()
    {
        return get_option('wcos_alg_search_api_key', '46b62f87eb98cc9d25837393ebe30b94');
    }

    public function getAlgoliaAppId()
    {
        return get_option('wcos_alg_app_id', 'OU45P96HHI');
    }

    public function getOrdersIndexName()
    {
        return get_option('wcos_orders_index_name', 'wc_orders');
    }

    /**
     * @return int
     */
    public function getOrdersToIndexPerBatchCount()
    {
        return (int) get_option('wcos_orders_per_batch', 500);
    }
}
