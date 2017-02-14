<?php

namespace AlgoliaOrdersSearch\Admin;

use AlgoliaOrdersSearch\Options;
use AlgoliaOrdersSearch\OrdersIndex;

class AjaxReindex
{

    /**
     * @var OrdersIndex
     */
    private $ordersIndex;

    /**
     * @var Options
     */
    private $options;

    /**
     * @param OrdersIndex $ordersIndex
     * @param Options     $options
     */
    public function __construct(OrdersIndex $ordersIndex, Options $options)
    {
        $this->options = $options;

        add_action('wp_ajax_aos_reindex', [$this, 'reIndex']);
        $this->ordersIndex = $ordersIndex;
    }

    public function reIndex()
    {
        if (isset($_POST['page'])) {
            $page = (int)$_POST['page'];
        } else {
            $page = 1;
        }

        if ($page === 1) {
            $this->ordersIndex->pushSettings();
        }

        $perPage = $this->options->getOrdersToIndexPerBatchCount();
        $totalPages = $this->ordersIndex->getTotalPagesCount($this->options->getOrdersToIndexPerBatchCount());

        $recordsPushedCount = $this->ordersIndex->pushRecords($page, $perPage);

        $response = [
            'recordsPushedCount' => $recordsPushedCount,
            'totalPagesCount'    => $totalPages,
            'finished'           => $page >= $totalPages,
        ];

        wp_send_json($response);

        wp_die();
    }

}
