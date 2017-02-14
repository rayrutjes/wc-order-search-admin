<?php
/**
 * Created by PhpStorm.
 * User: raymond
 * Date: 11/02/2017
 * Time: 18:43
 */

namespace AlgoliaOrdersSearch;

class Plugin
{
    /**
     * @var self
     */
    static private $instance;

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
        if(!$this->options->hasAlgoliaAccountSettings()) {
            return;
        }

		$algoliaClient = new Client($options->getAlgoliaAppId(), $options->getAlgoliaAdminApiKey());

        $integrationName = 'wc-orders-search';
        $ua = '; ' . $integrationName . ' integration (' . AOS_VERSION . ')'
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
    static public function initialize(Options $options) {
        if(null !== self::$instance) {
            throw new \LogicException('Plugin has already been initialized!');
        }

        self::$instance = new Plugin($options);

        return self::$instance;
    }

    /**
     * @return Plugin
     */
    public static function getInstance()
    {
        if(null === self::$instance) {
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
        if(null === $this->ordersIndex) {
            throw new \LogicException('Orders index has not be initialized.');
        }
        return $this->ordersIndex;
    }
}
