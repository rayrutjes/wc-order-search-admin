<?php

/*
 * This file is part of AlgoliaIndex library.
 * (c) Raymond Rutjes <raymond.rutjes@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace WC_Order_Search_Admin;

class Plugin
{
    /**
     * @var self
     */
    private static $instance;

    /**
     * @var Options
     */
    private $options;

    /**
     * @param Options $options
     */
    private function __construct(Options $options)
    {
        global $wp_version;

        $this->options = $options;
        if (!$this->options->hasAlgoliaAccountSettings()) {
            add_action('admin_notices', array($this, 'configureAlgoliaSettingsNotice'));

            return;
        }

        $algoliaClient = new Client($options->getAlgoliaAppId(), $options->getAlgoliaAdminApiKey());

        $integrationName = 'wc-order-search-admin';
        $ua = '; ' . $integrationName . ' integration (' . WC_OSA_VERSION . ')'
            . '; PHP (' . phpversion() . ')'
            . '; Wordpress (' . $wp_version . ')';

        Version::$custom_value = $ua;

        $this->ordersIndex = new OrdersIndex($options->getOrdersIndexName(), $algoliaClient);
        new OrderChangeListener($this->ordersIndex);
    }

    /**
     * @param Options $options
     *
     * @return Plugin
     */
    public static function initialize(Options $options)
    {
        if (null !== self::$instance) {
            throw new \LogicException('Plugin has already been initialized!');
        }

        self::$instance = new self($options);

        return self::$instance;
    }

    /**
     * @return Plugin
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            throw new \LogicException('Plugin::initialize must be called first!');
        }

        return self::$instance;
    }

    /**
     * @return Options
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return OrdersIndex
     */
    public function getOrdersIndex()
    {
        if (null === $this->ordersIndex) {
            throw new \LogicException('Orders index has not be initialized.');
        }

        return $this->ordersIndex;
    }

    public function configureAlgoliaSettingsNotice()
    {
        $screen = get_current_screen();
        if ($screen->id === 'settings_page_wc_osa_options') {
            return;
        } ?>
        <div class="notice notice-success">
            <p><?php esc_html_e('You are one step away from being able to have fast and relevant search powered by Algolia for finding WooCommerce orders.', 'wc-order-search-admin'); ?></p>
            <p><a href="options-general.php?page=wc_osa_options" class="button button-primary"><?php esc_html_e('Setup now', 'wc-order-search-admin'); ?></a></p>
        </div>
        <?php

    }
}
