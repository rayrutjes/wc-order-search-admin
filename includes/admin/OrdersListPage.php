<?php

/*
 * This file is part of WooCommerce Order Search Admin plugin for WordPress.
 * (c) Raymond Rutjes <raymond.rutjes@gmail.com>
 * This source file is subject to the GPLv2 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace WC_Order_Search_Admin\Admin;

use WC_Order_Search_Admin\Options;

class OrdersListPage
{
    /**
     * @var Options
     */
    private $options;

    /**
     * OrdersListPage constructor.
     *
     * @param Options $options
     */
    public function __construct(Options $options)
    {
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));

        $this->options = $options;
    }

    public function enqueue_scripts()
    {
        $screen = get_current_screen();
        if ($screen->id !== 'edit-shop_order') {
            return;
        }

        wp_enqueue_style('wc_osa_orders_search', plugin_dir_url(WC_OSA_FILE) . 'assets/css/styles.css', array(), WC_OSA_VERSION);

        wp_enqueue_script('wc_osa_algolia', 'https://cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js', array(), false, true);
        wp_enqueue_script('wc_osa_autocomplete', 'https://cdn.jsdelivr.net/autocomplete.js/0/autocomplete.min.js', array(), false, true);
        wp_enqueue_script('wc_osa_orders_search', plugin_dir_url(WC_OSA_FILE) . 'assets/js/orders-autocomplete.js', array('wc_osa_algolia', 'wc_osa_autocomplete', 'jquery'), WC_OSA_VERSION, true);

        wp_localize_script('wc_osa_orders_search', 'aosOptions', array(
            'appId'           => $this->options->getAlgoliaAppId(),
            'searchApiKey'    => $this->options->getAlgoliaSearchApiKey(),
            'ordersIndexName' => $this->options->getOrdersIndexName(),
            'debug'           => defined('WP_DEBUG') && WP_DEBUG === true,
        ));
    }
}
