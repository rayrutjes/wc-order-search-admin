<?php

namespace AlgoliaOrdersSearch;

use RayRutjes\AlgoliaIntegration\Index\Index;
use RayRutjes\AlgoliaIntegration\Index\IndexSettings;
use RayRutjes\AlgoliaIntegration\Index\RecordsProvider;

class OrdersIndex implements Index, RecordsProvider {

    /**
     * @return string
     */
    public function getName()
    {
        return 'wc_orders';
    }

    /**
     * @return RecordsProvider
     */
    public function getRecordsProvider()
    {
        return $this;
    }

    /**
     * @return IndexSettings
     */
    public function getSettings()
    {
        return new IndexSettings([]);
    }

    /**
     * @return int
     */
    public function getTotalRecordsCount()
    {
        $results = $this->newQuery();

        return (int) $results->found_posts;
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

            $record = [
                'objectID' => $order->id,
                'id' => $order->id,
                'order_type' => $order->order_type,
                'post_status' => $order->post_status,
                'order_date' => $order->order_date,
            ];

            // Add user info.
            $user = $order->get_user();
            if($user) {
                $record['customer'] = [
                    'ID' => $user->ID,
                    'display_name' => $user->display_name,
                    'user_email' => $user->user_email,
                ];
            }

            // Add items.
            // $items = $order->get_items('string');
            $record['item_count'] = $order->get_item_count();
            $record['edit_order_url'] = 'post.php?post=' . $order->post->ID . '&action=edit';



            $records[] = $record;

        }

        var_dump($records);

        exit('test');
    }

    private function newQuery($args)
    {
        $defaultArgs = [
            'post_type'   => wc_get_order_types(),
            'post_status' => array_keys( wc_get_order_statuses() ),
        ];

        $args = array_merge($defaultArgs, $args);
        $query = new \WP_Query($args);

        return $query;
    }
}
