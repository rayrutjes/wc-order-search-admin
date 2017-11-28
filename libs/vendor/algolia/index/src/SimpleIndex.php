<?php

/*
 * This file is part of AlgoliaIndex library.
 * (c) Raymond Rutjes for Algolia <raymond.rutjes@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace WC_Order_Search_Admin\Algolia\Index;

use WC_Order_Search_Admin\AlgoliaSearch\Client;
final class SimpleIndex extends \WC_Order_Search_Admin\Algolia\Index\Index
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var IndexSettings
     */
    private $settings;
    /**
     * @var RecordsProvider
     */
    private $recordsProvider;
    /**
     * @var Client
     */
    private $client;
    /**
     * @param string          $name
     * @param IndexSettings   $settings
     * @param RecordsProvider $recordsProvider
     * @param Client          $client
     */
    public function __construct($name, \WC_Order_Search_Admin\Algolia\Index\IndexSettings $settings, \WC_Order_Search_Admin\Algolia\Index\RecordsProvider $recordsProvider, \WC_Order_Search_Admin\AlgoliaSearch\Client $client)
    {
        $this->name = (string) $name;
        $this->settings = $settings;
        $this->recordsProvider = $recordsProvider;
        $this->client = $client;
    }
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * @return IndexSettings
     */
    protected function getSettings()
    {
        return $this->settings;
    }
    /**
     * @return RecordsProvider
     */
    protected function getRecordsProvider()
    {
        return $this->recordsProvider;
    }
    /**
     * @return Client
     */
    protected function getAlgoliaClient()
    {
        return $this->client;
    }
}
