<?php

namespace AlgoliaOrdersSearch;

use AlgoliaSearch\Client;
use RayRutjes\AlgoliaIntegration\Index\Index;
use RayRutjes\AlgoliaIntegration\Index\IndexSettings;
use RayRutjes\AlgoliaIntegration\Index\RecordsProvider;

class OrdersIndex extends Index implements RecordsProvider {

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
        return new IndexSettings([
            'searchableAttributes' => [
                'id',
                'number',
                'customer.display_name',
                'customer.email',
                'items.sku',
                'status_name',
            ],
            'disableTypoToleranceOnAttributes' => [
                'id',
                'number',
                'items.sku',
            ],
            'customRanking' => [
                'desc(date_timestamp)'
            ],
            'attributesForFaceting' => [
                'customer.display_name',
                'type',
                'items.sku',
            ],
        ]);
    }

    /**
     * @param int $perPage
     *
     * @return int
     */
    public function getTotalPagesCount($perPage)
    {
        $results = $this->newQuery(['posts_per_page' => (int)$perPage]);

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
        $results = $this->newQuery([
            'posts_per_page' => $perPage,
            'paged' => $page,
        ]);

        // http://stackoverflow.com/questions/39401393/how-to-get-woocommerce-order-details
        $records = [];
        $factory = new \WC_Order_Factory();
        foreach ($results->posts as $post) {
            $order = $factory->get_order($post);
            $records = array_merge($records, $this->getRecordsForOrder($order));
        }

        return $records;
    }

    private function newQuery(array $args = [])
    {
        $defaultArgs = [
            'post_type'   => wc_get_order_types(),
            'post_status' => array_keys( wc_get_order_statuses() ),
        ];

        $args = array_merge($defaultArgs, $args);
        $query = new \WP_Query($args);

        return $query;
    }

    /**
     * @return Client
     */
    protected function getAlgoliaClient()
    {
        return $this->client;
    }

    /**
     * @param \WC_Abstract_Order $order
     *
     * @return array
     */
    private function getRecordsForOrder(\WC_Abstract_Order $order)
    {
        $record = [
            'objectID' => (int) $order->id,
            'id' => (int) $order->id,
            'type' => $order->order_type,
            'number' => (string) $order->get_order_number(),
            'status' => $order->get_status(),
            'status_name' => wc_get_order_status_name( $order->get_status() ),
            'date_timestamp' => strtotime( $order->order_date ),
            'date_formatted' => date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ),
            'formatted_order_total' => $order->get_formatted_order_total(),
            'items_count' => $order->get_item_count(),
        ];

        // Add user info.
        $user = $order->get_user();
        if($user) {
            $record['customer'] = [
                'id' => (int) $user->ID,
                'display_name' => $user->display_name,
                'email' => $user->user_email,
            ];
        }

        // Add items.
        $record['items'] = [];
        foreach ($order->get_items() as $itemId => $item) {
            $product = $order->get_product_from_item( $item );
            $record['items'][] = [
                'id' => (int) $itemId,
                'name' => apply_filters( 'woocommerce_order_item_name', esc_html( $item['name'] ), $item, false ),
                'qty' => (int) $item['qty'],
                'sku' => $product->get_sku()
            ];

        }

        return array($record);
    }

    /**
     * @param \WC_Abstract_Order $order
     *
     * @return int
     */
    public function pushRecordsForOrder(\WC_Abstract_Order $order){
        $records = $this->getRecordsForOrder($order);
        $totalRecordsCount = count($records);
        if($totalRecordsCount === 0) {
            return 0;
        }

        $this->getAlgoliaIndex()->addObjects($records);

        return $totalRecordsCount;
    }
}
