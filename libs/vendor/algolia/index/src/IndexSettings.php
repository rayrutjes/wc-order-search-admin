<?php

/*
 * This file is part of AlgoliaIndex library.
 * (c) Raymond Rutjes for Algolia <raymond.rutjes@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace WC_Order_Search_Admin\Algolia\Index;

final class IndexSettings
{
    /**
     * @var array
     */
    private $options;
    /**
     * @var IndexReplicaSettings[]
     */
    private $replicaSettings = array();
    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
        if (isset($options['replicas'])) {
            $replicas = $options['replicas'];
            if (!is_array($replicas)) {
                throw new \InvalidArgumentException('Replica settings should be an array.');
            }
            $this->setReplicaSettings($replicas);
        }
    }
    /**
     * @return array
     */
    public function toArray()
    {
        return $this->options;
    }
    /**
     * @return IndexReplicaSettings[]
     */
    public function getReplicaSettings()
    {
        return $this->replicaSettings;
    }
    /**
     * @param array $replicas
     */
    private function setReplicaSettings(array $replicas)
    {
        $replicaSettings = array();
        $indexNames = array();
        foreach ($replicas as $indexName => $replica) {
            if ($replica instanceof IndexReplicaSettings) {
                // Nothing to do
            } elseif (is_string($replica)) {
                $replica = new \WC_Order_Search_Admin\Algolia\Index\IndexReplicaSettings($replica);
            } elseif (!is_string($indexName)) {
                throw new \InvalidArgumentException('The key for each replica should be the index name.');
            } elseif (!is_array($replica)) {
                throw new \InvalidArgumentException('The replica settings should be an array or an instance of IndexReplicaSettings');
            } else {
                $replica = new \WC_Order_Search_Admin\Algolia\Index\IndexReplicaSettings($indexName, $replica);
            }
            $replicaSettings[] = $replica;
            $indexNames[] = $replica->getIndexName();
        }
        $this->replicaSettings = $replicaSettings;
        $this->options['replicas'] = $indexNames;
    }
}
