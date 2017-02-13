<?php
/**
 * Created by PhpStorm.
 * User: raymond
 * Date: 13/02/2017
 * Time: 22:50
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
        add_action('save_post', [$this, 'pushOrderRecords'], 10, 2);
        add_action('before_delete_post', [$this, 'deleteOrderRecords']);
        add_action('wp_trash_post', [$this, 'deleteOrderRecords']);
    }

    /**
     * @param int $orderId
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
