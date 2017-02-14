<?php

/*
 * This file is part of AlgoliaIntegration library.
 * (c) Raymond Rutjes <raymond.rutjes@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace AlgoliaOrdersSearch\Admin;

use AlgoliaOrdersSearch\Options;

class OptionsPage
{
    /**
     * @var Options
     */
    private $options;

    /**
     * @param Options $options
     */
    public function __construct(Options $options)
    {
        add_action('admin_menu', array($this, 'register_page_in_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));

        $this->options = $options;
    }

    public function enqueue_scripts()
    {
        $screen = get_current_screen();
        if ($screen->id !== 'settings_page_aos_options') {
            return;
        }

        wp_enqueue_script('aos_orders_search', plugin_dir_url(__FILE__).'../../assets/js/reindex-orders-button.js', array('jquery'), false, true);
        wp_enqueue_script('aos_ajax_forms', plugin_dir_url(__FILE__).'../../assets/js/ajax-forms.js', array('jquery'), false, true);

        /*wp_localize_script( 'aos_orders_search', 'aosOptions', array(
            'appId' => $this->options->getAlgoliaAppId(),
            'searchApiKey' => $this->options->getAlgoliaSearchApiKey(),
            'ordersIndexName' => $this->options->getOrdersIndexName(),
        ) );*/
    }

    public function register_page_in_menu()
    {
        add_options_page('WooCommerce Orders Search - Settings', 'WooCommerce Orders Search', 'manage_options', 'aos_options', array($this, 'render_page'));
    }

    public function render_page()
    {
        // check user capabilities
        if (!current_user_can('manage_options')) {
            return;
        }

        include 'views/options.php';
    }
}
