<?php

/*
 * This file is part of Algolia Orders Search for WooCommerce library.
 * (c) Raymond Rutjes <raymond.rutjes@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace AlgoliaWooCommerceOrderSearchAdmin\Admin;

use AlgoliaWooCommerceOrderSearchAdmin\Options;

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

        wp_enqueue_style('aos_orders_search', plugin_dir_url(AOS_FILE) . 'assets/css/styles.css', array(), AOS_VERSION);

        wp_enqueue_script('aos_algolia', 'https://cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js', array(), false, true);
        wp_enqueue_script('aos_autocomplete', 'https://cdn.jsdelivr.net/autocomplete.js/0/autocomplete.min.js', array(), false, true);
        wp_enqueue_script('aos_orders_search', plugin_dir_url(AOS_FILE) . 'assets/js/orders-autocomplete.js', array('aos_algolia', 'aos_autocomplete', 'jquery'), AOS_VERSION, true);

        wp_localize_script('aos_orders_search', 'aosOptions', array(
            'appId' => $this->options->getAlgoliaAppId(),
            'searchApiKey' => $this->options->getAlgoliaSearchApiKey(),
            'ordersIndexName' => $this->options->getOrdersIndexName(),
            'poweredByImgUrl' => plugin_dir_url(AOS_FILE) . 'assets/images/search-by-algolia.svg',
            'debug' => defined('WP_DEBUG') && WP_DEBUG === true,
        ));
    }
}
