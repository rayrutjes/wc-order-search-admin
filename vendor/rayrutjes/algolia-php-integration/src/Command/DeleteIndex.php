<?php

/*
 * This file is part of AlgoliaIntegration library.
 * (c) Raymond Rutjes <raymond.rutjes@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace AlgoliaOrdersSearch\AlgoliaIntegration\Command;

use AlgoliaOrdersSearch\AlgoliaIntegration\Bus\Command;

final class DeleteIndex implements Command
{
    /**
     * @var string
     */
    private $indexName;

    /**
     * @param string $indexName
     */
    public function __construct($indexName)
    {
        $this->indexName = (string) $indexName;
    }

    /**
     * @return string
     */
    public function getIndexName()
    {
        return $this->indexName;
    }
}
