<?php

namespace AlgoliaOrdersSearch\Admin;

class OptionsPage {

    /**
     * OptionsPage constructor.
     */
    public function __construct()
    {
        add_action( 'admin_menu', array($this, 'register_page_in_menu') );

    }

    public function register_page_in_menu()
    {
        add_options_page('My Options', 'WooCommerce orders search', 'manage_options', 'aos_options', array($this, 'render_page'));
    }

    public function render_page()
    {
        echo 'Todo.';
    }
}
