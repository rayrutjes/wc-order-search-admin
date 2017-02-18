<?php

/*
 * This file is part of Algolia Orders Search for WooCommerce library.
 * (c) Raymond Rutjes <raymond.rutjes@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace AlgoliaOrdersSearch;

class OrderChangeListener
{
    /**
     * @var OrdersIndex
     */
    private $ordersIndex;

    /**
     * @param OrdersIndex $ordersIndex
     */
    public function __construct(OrdersIndex $ordersIndex)
    {
        $this->ordersIndex = $ordersIndex;
        add_action('save_post', array($this, 'pushOrderRecords'), 10, 2);
        add_action('before_delete_post', array($this, 'deleteOrderRecords'));
        add_action('wp_trash_post', array($this, 'deleteOrderRecords'));
    }

    /**
     * @param int   $orderId
     * @param mixed $postId
     * @param mixed $post
     */
    public function pushOrderRecords($postId, $post)
    {
        if ('shop_order' !== $post->post_type
            || 'auto-draft' === $post->post_status
            || 'trash' === $post->post_status
        ) {
            return;
        }

        $order = wc_get_order($postId);
        $this->ordersIndex->pushRecordsForOrder($order);
    }

    public function deleteOrderRecords($postId)
    {
        $post = get_post($postId);

        if ($post->post_type === 'shop_order') {
            $this->ordersIndex->deleteRecordsByOrderId($post->ID);
        }
    }
}
