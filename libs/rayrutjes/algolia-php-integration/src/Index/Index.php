<?php

/*
 * This file is part of AlgoliaIntegration library.
 * (c) Raymond Rutjes <raymond.rutjes@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace RayRutjes\AlgoliaIntegration\Index;

use AlgoliaSearch\Client;

abstract class Index
{
    /**
     * @return string
     */
    abstract public function getName();

    /**
     * Delete the index.
     */
    public function delete()
    {
        $this->getAlgoliaIndex()->deleteIndex($this->getName());
    }

    /**
     * Remove all the records from the index.
     */
    public function clear()
    {
        $this->getAlgoliaIndex()->clearIndex();
    }

    /**
     * @param int $page
     * @param int $perPage
     */
    public function pushRecords($page, $perPage)
    {
        $records = $this->getRecordsProvider()->getRecords($page, $perPage);
        if (count($records) > 0) {
            $this->getAlgoliaIndex()->addObjects($records);
        }

        return count($records);
    }

    /**
     * @param int $perPage
     */
    public function pushAllRecords($perPage)
    {
        $recordsProvider = $this->getRecordsProvider();
        $totalPages = $recordsProvider->getTotalPagesCount();
        for ($page = 1; $page <= $totalPages; ++$page) {
            $records = $recordsProvider->getRecords($page, $perPage);
            $this->getAlgoliaIndex()->addObjects($records);
        }
    }

    /**
     * @param string $newName
     */
    public function moveTo($newName)
    {
        $this->getAlgoliaClient()->moveIndex($this->getName(), (string) $newName);
    }

    /**
     * @param bool $withoutReplicas
     */
    public function pushSettings($withoutReplicas = false)
    {
        $settings = $this->getSettings()->toArray();
        if ((bool) $withoutReplicas === true) {
            unset($settings['replicas']);
        }
        $this->getAlgoliaIndex()->setSettings($settings);
    }

    public function pushReplicaSettings()
    {
        $settings = $this->getSettings()->toArray();
        if (!isset($settings['replicas'])) {
            throw new \RuntimeException('No replicas found in settings');
        }
        $this->getAlgoliaIndex()->setSettings(array('replicas' => $settings['replicas']));

        foreach ($this->getSettings()->getReplicaSettings() as $settings) {
            $replicaIndexName = $settings->getIndexName();
            $this->getAlgoliaClient()->initIndex($replicaIndexName)->setSettings($settings->toArray());
        }
    }

    /**
     * Copies settings form an existing index to this one.
     * Replicas are removed from the settings.
     * Will raise an exception if the index does not exist.
     *
     * @param string $indexName
     */
    public function copySettingsFrom($indexName)
    {
        $settings = $this->getAlgoliaClient()->initIndex((string) $indexName)->getSettings();
        if (isset($settings['replicas'])) {
            unset($settings['replicas']);
        }

        $index = $this->getAlgoliaIndex();
        $index->setSettings($settings);
    }

    public function reIndex($clearExistingRecords = true, $perPage = 500)
    {
        if ((bool) $clearExistingRecords === true) {
            $this->clear();
        }

        $this->pushAllRecords();
    }

    public function reIndexUsingTemporaryIndex($keepSettings = false, $perPage = 500)
    {
        $temporaryIndex = new SimpleIndex($this->getName().'_tmp_'.time(), $this->getSettings(), $this->getRecordsProvider(), $this->getAlgoliaClient());

        $keptSettings = false;
        if ($keepSettings) {
            try {
                $temporaryIndex->copySettingsFrom($this->getName());
                $temporaryIndex->copySynonymsFrom($this->getName());
                $keptSettings = true;
            } catch (AlgoliaException $exception) {
                // Will fail if the index does not exist yet.
                // Which is perfectly fine.
            }
        }

        if ($keptSettings === false) {
            $temporaryIndex->pushSettings(true);
        }

        $temporaryIndex->pushAllRecords((int) $perPage);
        $temporaryIndex->moveTo($this->getName());
        if ($keptSettings === false) {
            $this->pushReplicaSettings();
        } else {
            $this->pushSettings();
        }
    }

    /**
     * Will raise an exception if the index does not exist.
     *
     * @param string $indexName
     */
    public function copySynonymsFrom($indexName)
    {
        $index = $this->getAlgoliaIndex();
        $page = 0;
        do {
            $results = $this->getAlgoliaClient()->initIndex((string) $indexName)->searchSynonyms('', array(), $page);
            $synonyms = array();
            foreach ($results['hits'] as $synonym) {
                unset($synonym['_highlightResult']);
                $synonyms[] = $synonym;
            }
            if (count($synonyms) === 0) {
                break;
            }
            $index->batchSynonyms($synonyms);
            ++$page;
        } while (true);
    }

    /**
     * @return IndexSettings
     */
    abstract protected function getSettings();

    /**
     * @return RecordsProvider
     */
    abstract protected function getRecordsProvider();

    /**
     * @return Client
     */
    abstract protected function getAlgoliaClient();

    /**
     * @return \AlgoliaSearch\Index
     */
    protected function getAlgoliaIndex()
    {
        return $this->getAlgoliaClient()->initIndex($this->getName());
    }
}
