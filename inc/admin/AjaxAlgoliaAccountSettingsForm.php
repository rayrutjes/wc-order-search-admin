<?php

/*
 * This file is part of AlgoliaIntegration library.
 * (c) Raymond Rutjes <raymond.rutjes@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

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

        add_action('wp_ajax_aos_save_algolia_settings', array($this, 'saveAlgoliaAccountSettings'));
    }

    public function saveAlgoliaAccountSettings()
    {
        if (!isset($_POST['app_id']) || !isset($_POST['search_api_key']) || !isset($_POST['admin_api_key'])) {
            wp_die('Hacker');
        }

        try {
            $this->options->setAlgoliaAccountSettings($_POST['app_id'], $_POST['search_api_key'], $_POST['admin_api_key']);
        } catch (\InvalidArgumentException $exception) {
            wp_send_json_error(array(
                'message' => $exception->getMessage(),
            ));
        }

        $response = array(
            'success' => true,
            'message' => 'Your Algolia account settings have been saved. You can now hit the "re-index orders" button.',
        );

        wp_send_json($response);
    }
}
