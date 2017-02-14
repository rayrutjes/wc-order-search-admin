<?php

namespace AlgoliaOrdersSearch\Admin;

use AlgoliaOrdersSearch\Options;
use AlgoliaOrdersSearch\OrdersIndex;

class AjaxAlgoliaAccountSettingsForm
{
    /**
     * @var Options
     */
    private $options;

    /**
     * @param OrdersIndex $ordersIndex
     * @param Options     $options
     */
    public function __construct(Options $options)
    {
        $this->options = $options;

        add_action('wp_ajax_aos_save_algolia_settings', [$this, 'saveAlgoliaAccountSettings']);
    }

    public function saveAlgoliaAccountSettings()
    {
        if(!isset($_POST['app_id']) || !isset($_POST['search_api_key']) || !isset($_POST['admin_api_key'])) {
            wp_die('Hacker');
        }

        try {
            $this->options->setAlgoliaAccountSettings($_POST['app_id'], $_POST['search_api_key'], $_POST['admin_api_key']);
        } catch (\InvalidArgumentException $exception) {
            wp_send_json_error(array(
                'message' => $exception->getMessage(),
            ));
        }

        $response = [
            'success' => true,
            'message' => 'Your Algolia account settings have been saved. Please refresh the page and hit the "re-index orders" button if you never did so already.'
        ];

        wp_send_json($response);
    }


}
