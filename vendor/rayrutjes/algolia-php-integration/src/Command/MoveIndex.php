<?php

/*
 * This file is part of AlgoliaIntegration library.
 * (c) Raymond Rutjes <raymond.rutjes@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace AlgoliaOrdersSearch\AlgoliaIntegration\Command;

use AlgoliaOrdersSearch\AlgoliaIntegration\Bus\Command;

final class MoveIndex implements Command
{
    /**
     * @var string
     */
    private $fromIndexName;

    /**
     * @var string
     */
    private $toIndexName;

    /**
     * @param string $fromIndexName
     * @param string $toIndexName
     */
    public function __construct($fromIndexName, $toIndexName)
    {
        $this->fromIndexName = (string) $fromIndexName;
        $this->toIndexName = (string) $toIndexName;
    }

    /**
     * @return string
     */
    public function getFromIndexName()
    {
        return $this->fromIndexName;
    }

    /**
     * @return string
     */
    public function getToIndexName()
    {
        return $this->toIndexName;
    }
}
