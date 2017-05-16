<?php

/*
 * This file is part of WooCommerce Order Search Admin plugin for WordPress.
 * (c) Raymond Rutjes <raymond.rutjes@gmail.com>
 * This source file is subject to the GPLv2 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace AlgoliaWooCommerceOrderSearchAdmin\Admin;

use AlgoliaWooCommerceOrderSearchAdmin\AlgoliaException;
use AlgoliaWooCommerceOrderSearchAdmin\Options;
use AlgoliaWooCommerceOrderSearchAdmin\OrdersIndex;

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

        add_action('wp_ajax_wc_osa_reindex', array($this, 'reIndex'));
        $this->ordersIndex = $ordersIndex;
    }

    public function reIndex()
    {
        if (isset($_POST['page'])) {
            $page = (int) $_POST['page'];
        } else {
            $page = 1;
        }

        if ($page === 1) {
            try {
                $this->ordersIndex->clear();
                $this->ordersIndex->pushSettings();
            } catch (AlgoliaException $exception) {
                wp_send_json_error(array(
                    'message' => $exception->getMessage(),
                ));
            }
        }

        $perPage = $this->options->getOrdersToIndexPerBatchCount();
        $totalPages = $this->ordersIndex->getTotalPagesCount($this->options->getOrdersToIndexPerBatchCount());

        try {
            $recordsPushedCount = $this->ordersIndex->pushRecords($page, $perPage);
        } catch (AlgoliaException $exception) {
            wp_send_json_error(array(
                'message' => $exception->getMessage(),
            ));
        }

        $response = array(
            'recordsPushedCount' => $recordsPushedCount,
            'totalPagesCount'    => $totalPages,
            'finished'           => $page >= $totalPages,
        );

        wp_send_json($response);
    }
}
