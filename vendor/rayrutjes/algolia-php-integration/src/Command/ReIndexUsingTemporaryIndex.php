<?php

/*
 * This file is part of AlgoliaIntegration library.
 * (c) Raymond Rutjes <raymond.rutjes@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace AlgoliaOrdersSearch\AlgoliaIntegration\Command;

use AlgoliaOrdersSearch\AlgoliaIntegration\Bus\Command;

final class ReIndexUsingTemporaryIndex implements Command
{
    /**
     * @var string
     */
    private $indexName;

    /**
     * @var int
     */
    private $recordsPerBatch;

    /**
     * @var bool
     */
    private $keepExistingSettings;

    /**
     * @param string $indexName
     * @param int    $recordsPerBatch
     * @param bool   $keepExistingSettings
     */
    public function __construct($indexName, $recordsPerBatch = 1000, $keepExistingSettings = false)
    {
        $this->indexName = (string) $indexName;
        $this->recordsPerBatch = (int) $recordsPerBatch;
        $this->keepExistingSettings = (bool) $keepExistingSettings;
    }

    /**
     * @return string
     */
    public function getIndexName()
    {
        return $this->indexName;
    }

    /**
     * @return int
     */
    public function getRecordsPerBatch()
    {
        return $this->recordsPerBatch;
    }

    /**
     * @return bool
     */
    public function getKeepExistingSettings()
    {
        return $this->keepExistingSettings;
    }
}
