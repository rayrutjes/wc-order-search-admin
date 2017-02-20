<?php

/*
 * This file is part of AlgoliaIndex library.
 * (c) Raymond Rutjes for Algolia <raymond.rutjes@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace AlgoliaOrdersSearch\Index;

use AlgoliaOrdersSearch\Client;

final class SimpleIndex extends Index
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
    public function __construct($name, IndexSettings $settings, RecordsProvider $recordsProvider, Client $client)
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
