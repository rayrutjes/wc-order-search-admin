<?php

namespace AlgoliaOrdersSearch\Admin;


use AlgoliaOrdersSearch\Options;

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
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

        $this->options = $options;
    }

    public function enqueue_scripts()
    {
        $screen = get_current_screen();
        if($screen->id !== 'edit-shop_order') {
            return;
        }

        wp_enqueue_style( 'aos_orders_search', plugin_dir_url( __FILE__ ) . '../../assets/css/styles.css' );

        wp_enqueue_script( 'aos_algolia', 'https://cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js', array(), false, true );
        wp_enqueue_script( 'aos_autocomplete', 'https://cdn.jsdelivr.net/autocomplete.js/0/autocomplete.min.js', array(), false, true );
        wp_enqueue_script( 'aos_orders_search', plugin_dir_url( __FILE__ ) . '../../assets/js/orders-autocomplete.js', array('aos_algolia', 'aos_autocomplete', 'jquery'), false, true );

        wp_localize_script( 'aos_orders_search', 'aosOptions', array(
            'appId' => $this->options->getAlgoliaAppId(),
            'searchApiKey' => $this->options->getAlgoliaSearchApiKey(),
            'ordersIndexName' => $this->options->getOrdersIndexName(),
        ) );
    }
}
