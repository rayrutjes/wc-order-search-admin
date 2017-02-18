<?php

/*
 * This file is part of AlgoliaIndex library.
 * (c) Raymond Rutjes <raymond.rutjes@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace AlgoliaOrdersSearch;

use AlgoliaOrdersSearch\AlgoliaIndex\Index;
use AlgoliaOrdersSearch\AlgoliaIndex\IndexSettings;
use AlgoliaOrdersSearch\AlgoliaIndex\RecordsProvider;

class OrdersIndex extends Index implements RecordsProvider
{
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
    public function __construct($name, Client $client)
    {
        $this->name = $name;
        $this->client = $client;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $orderId
     */
    public function deleteRecordsByOrderId($orderId)
    {
        $this->getAlgoliaIndex()->deleteObject((string) $orderId);
    }

    /**
     * @param int $perPage
     *
     * @return int
     */
    public function getTotalPagesCount($perPage)
    {
        $results = $this->newQuery(array('posts_per_page' => (int) $perPage));

        return (int) $results->max_num_pages;
    }

    /**
     * @param int $page
     * @param int $perPage
     *
     * @return array
     */
    public function getRecords($page, $perPage)
    {
        $query = $this->newQuery(array(
            'posts_per_page' => $perPage,
            'paged' => $page,
        ));

        return $this->getRecordsForQuery($query);
    }

    /**
     * @param \WC_Abstract_Order $order
     *
     * @return int
     */
    public function pushRecordsForOrder(\WC_Abstract_Order $order)
    {
        $records = $this->getRecordsForOrder($order);
        $totalRecordsCount = count($records);
        if ($totalRecordsCount === 0) {
            return 0;
        }

        $this->getAlgoliaIndex()->addObjects($records);

        return $totalRecordsCount;
    }

    /**
     * @return RecordsProvider
     */
    protected function getRecordsProvider()
    {
        return $this;
    }

    /**
     * @return IndexSettings
     */
    protected function getSettings()
    {
        return new IndexSettings(array(
            'searchableAttributes' => array(
                'id',
                'number',
                'customer.display_name',
                'customer.email',
                'items.sku',
                'status_name',
            ),
            'disableTypoToleranceOnAttributes' => array(
                'id',
                'number',
                'items.sku',
            ),
            'customRanking' => array(
                'desc(date_timestamp)',
            ),
            'attributesForFaceting' => array(
                'customer.display_name',
                'type',
                'items.sku',
            ),
        ));
    }

    /**
     * @return Client
     */
    protected function getAlgoliaClient()
    {
        return $this->client;
    }

    /**
     * @param array $args
     *
     * @return \WP_Query
     */
    private function newQuery(array $args = array())
    {
        $defaultArgs = array(
            'post_type' => wc_get_order_types(),
            'post_status' => array_keys(wc_get_order_statuses()),
        );

        $args = array_merge($defaultArgs, $args);
        $query = new \WP_Query($args);

        return $query;
    }

    /**
     * @param \WC_Abstract_Order $order
     *
     * @return array
     */
    private function getRecordsForOrder(\WC_Abstract_Order $order)
    {
        $record = array(
            'objectID' => (int) $order->id,
            'id' => (int) $order->id,
            'type' => $order->order_type,
            'number' => (string) $order->get_order_number(),
            'status' => $order->get_status(),
            'status_name' => wc_get_order_status_name($order->get_status()),
            'date_timestamp' => strtotime($order->order_date),
            'date_formatted' => date_i18n(get_option('date_format'), strtotime($order->order_date)),
            'formatted_order_total' => $order->get_formatted_order_total(),
            'items_count' => $order->get_item_count(),
            'payment_method_title' => $order->payment_method_title,
            'shipping_method_title' => $order->shipping_method_title,
        );

        // Add user info.
        $user = $order->get_user();
        if ($user) {
            $record['customer'] = array(
                'id' => (int) $user->ID,
                'display_name' => $user->display_name,
                'email' => $user->user_email,
            );
        } else {
            // Deal with guest checkouts.
            $record['customer'] = array(
                'display_name' => $order->get_formatted_billing_full_name(),
                'email' => $order->billing_email,
            );
        }

        // Add items.
        $record['items'] = array();
        foreach ($order->get_items() as $itemId => $item) {
            $product = $order->get_product_from_item($item);

            $record['items'][] = array(
                'id' => (int) $itemId,
                'name' => apply_filters('woocommerce_order_item_name', esc_html($item['name']), $item, false),
                'qty' => (int) $item['qty'],
                'sku' => $product instanceof \WC_Product ? $product->get_sku() : '',
            );
        }

        return array($record);
    }

    /**
     * @param mixed $id
     *
     * @return array
     */
    public function getRecordsForId($id)
    {
        $factory = new \WC_Order_Factory();
        $order = $factory->get_order($id);

        if(!$order instanceof \WC_Abstract_Order) {
            return array();
        }

        return $this->getRecordsForOrder($order);
    }

    /**
     * @param \WP_Query $query
     *
     * @return array
     */
    private function getRecordsForQuery(\WP_Query $query)
    {
        $records = array();
        $factory = new \WC_Order_Factory();
        foreach ($query->posts as $post) {
            $order = $factory->get_order($post);
            if(!$order instanceof \WC_Abstract_Order) {
                continue;
            }
            $records = array_merge($records, $this->getRecordsForOrder($order));
        }

        return $records;
    }
}
