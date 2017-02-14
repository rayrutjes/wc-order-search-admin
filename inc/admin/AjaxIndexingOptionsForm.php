<?php

namespace AlgoliaOrdersSearch\Admin;

use AlgoliaOrdersSearch\AlgoliaException;
use AlgoliaOrdersSearch\Plugin;

class AjaxIndexingOptionsForm
{
    /**
     * @var Plugin
     */
    private $plugin;

    /**
     * @param Plugin $plugin
     *
     * @internal param OrdersIndex $ordersIndex
     * @internal param Options $options
     */
    public function __construct(Plugin $plugin)
    {
        $this->plugin = $plugin;

        add_action('wp_ajax_aos_save_indexing_options', [$this, 'saveIndexingOptions']);
    }

    public function saveIndexingOptions()
    {
        if(!isset($_POST['orders_index_name']) || !isset($_POST['orders_per_batch'])) {
            wp_die('Hacker');
        }

        $options = $this->plugin->getOptions();

        try {
            $options->setOrdersIndexName($_POST['orders_index_name']);
        } catch (\InvalidArgumentException $exception) {
            wp_send_json_error(array(
                'message' => $exception->getMessage(),
            ));
        }

        if($options->hasAlgoliaAccountSettings()) {
            try {
                $this->plugin->getOrdersIndex()->moveTo($options->getOrdersIndexName());
            } catch (AlgoliaException $exception) {
                // Will fail if index doesn't exist which is OK.
            }
        }

        $options->setOrdersToIndexPerBatchCount($_POST['orders_per_batch']);

        $response = [
            'success' => true,
            'message' => 'Your indexing options have been saved.'
        ];

        wp_send_json($response);
    }


}
