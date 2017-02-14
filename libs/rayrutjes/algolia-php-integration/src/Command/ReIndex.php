<?php

/*
 * This file is part of AlgoliaIntegration library.
 * (c) Raymond Rutjes <raymond.rutjes@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace RayRutjes\AlgoliaIntegration\Command;

use RayRutjes\AlgoliaIntegration\Bus\Command;

final class ReIndex implements Command
{
    /**
     * @var string
     */
    private $indexName;

    /**
     * @var bool
     */
    private $clearExistingRecords;

    /**
     * @var int
     */
    private $recordsPerBatch;

    /**
     * @param string $indexName
     * @param bool   $clearExistingRecords
     * @param int    $recordsPerBatch
     */
    public function __construct($indexName, $clearExistingRecords = true, $recordsPerBatch = 1000)
    {
        $this->indexName = (string) $indexName;
        $this->clearExistingRecords = (bool) $clearExistingRecords;
        $this->recordsPerBatch = (int) $recordsPerBatch;
    }

    /**
     * @return string
     */
    public function getIndexName()
    {
        return $this->indexName;
    }

    /**
     * @return bool
     */
    public function getClearExistingRecords()
    {
        return $this->clearExistingRecords;
    }

    /**
     * @return int
     */
    public function getRecordsPerBatch()
    {
        return $this->recordsPerBatch;
    }
}
