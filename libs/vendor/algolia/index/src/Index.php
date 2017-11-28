<?php

/*
 * This file is part of AlgoliaIndex library.
 * (c) Raymond Rutjes for Algolia <raymond.rutjes@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace WC_Order_Search_Admin\Algolia\Index;

use WC_Order_Search_Admin\AlgoliaSearch\AlgoliaException;
use WC_Order_Search_Admin\AlgoliaSearch\Client;
abstract class Index
{
    /**
     * @return string
     */
    public abstract function getName();
    /**
     * Delete the index.
     */
    public function delete()
    {
        $this->getAlgoliaClient()->deleteIndex($this->getName());
    }
    /**
     * Remove all the records from the index.
     */
    public function clear()
    {
        $this->getAlgoliaIndex()->clearIndex();
    }
    /**
     * @param int           $page
     * @param int           $perPage
     * @param callable|null $batchCallback
     *
     * @return int the number of records pushed
     */
    public function pushRecords($page, $perPage, $batchCallback = null)
    {
        $recordsProvider = $this->getRecordsProvider();
        $totalPagesCount = $recordsProvider->getTotalPagesCount($perPage);
        $records = $recordsProvider->getRecords($page, $perPage);
        if (count($records) > 0) {
            $this->getAlgoliaIndex()->addObjects($records);
        }
        if (is_callable($batchCallback)) {
            call_user_func($batchCallback, $records, $page, $totalPagesCount);
        }
        return count($records);
    }
    /**
     * @param mixed $id
     *
     * @return int
     */
    public function pushRecordsForId($id)
    {
        $records = $this->getRecordsProvider()->getRecordsForId($id);
        if (count($records) > 0) {
            $this->getAlgoliaIndex()->addObjects($records);
        }
        return count($records);
    }
    /**
     * @param int           $perPage
     * @param callable|null $batchCallback
     *
     * @return int
     */
    public function pushAllRecords($perPage, $batchCallback = null)
    {
        $recordsProvider = $this->getRecordsProvider();
        $totalPages = $recordsProvider->getTotalPagesCount($perPage);
        $totalRecordsCount = 0;
        for ($page = 1; $page <= $totalPages; ++$page) {
            $totalRecordsCount += $this->pushRecords($page, $perPage, $batchCallback);
        }
        return $totalRecordsCount;
    }
    /**
     * @param array $recordIds
     *
     * @return int
     */
    public function deleteRecordsByIds(array $recordIds)
    {
        if (empty($recordIds)) {
            return 0;
        }
        $this->getAlgoliaIndex()->deleteObjects($recordIds);
        return count($recordIds);
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
    /**
     * @param bool          $clearExistingRecords
     * @param int           $perPage
     * @param callable|null $batchCallback
     *
     * @return int
     */
    public function reIndex($clearExistingRecords = true, $perPage = 500, $batchCallback = null)
    {
        if ((bool) $clearExistingRecords === true) {
            $this->clear();
        }
        return $this->pushAllRecords($perPage, $batchCallback);
    }
    /**
     * @param bool          $keepSettings
     * @param int           $perPage
     * @param callable|null $batchCallback
     *
     * @return int
     */
    public function reIndexUsingTemporaryIndex($keepSettings = false, $perPage = 500, $batchCallback = null)
    {
        $temporaryIndex = new \WC_Order_Search_Admin\Algolia\Index\SimpleIndex($this->getName() . '_tmp_' . time(), $this->getSettings(), $this->getRecordsProvider(), $this->getAlgoliaClient());
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
        $totalRecordsCount = $temporaryIndex->pushAllRecords((int) $perPage, $batchCallback);
        $temporaryIndex->moveTo($this->getName());
        if ($keptSettings === false) {
            $this->pushReplicaSettings();
        } else {
            $this->pushSettings();
        }
        return $totalRecordsCount;
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
    protected abstract function getSettings();
    /**
     * @return RecordsProvider
     */
    protected abstract function getRecordsProvider();
    /**
     * @return Client
     */
    protected abstract function getAlgoliaClient();
    /**
     * @return \AlgoliaSearch\Index
     */
    protected function getAlgoliaIndex()
    {
        return $this->getAlgoliaClient()->initIndex($this->getName());
    }
}
