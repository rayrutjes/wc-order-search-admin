<?php
/**
 * Created by PhpStorm.
 * User: raymond
 * Date: 11/02/2017
 * Time: 18:43
 */

namespace AlgoliaOrdersSearch;

use AlgoliaSearch\Client;
use RayRutjes\AlgoliaIntegration\Command\PushRecordsToIndex;
use RayRutjes\AlgoliaIntegration\Command\PushRecordsToIndexHandler;
use RayRutjes\AlgoliaIntegration\Command\ReIndexUsingTemporaryNameHandler;
use RayRutjes\AlgoliaIntegration\Command\UpdateIndexSettingsHandler;
use RayRutjes\AlgoliaIntegration\Index\InMemoryIndexManager;
use RayRutjes\AlgoliaIntegration\Index\NoopIndexNameInflector;
use RayRutjes\AlgoliaIntegration\Index\TemporaryIndexNameInflector;

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
        $this->options = $options;
		$algoliaClient = new Client($options->getAlgoliaAppId(), $options->getAlgoliaAdminApiKey());
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
        return $this->ordersIndex;
    }
}
