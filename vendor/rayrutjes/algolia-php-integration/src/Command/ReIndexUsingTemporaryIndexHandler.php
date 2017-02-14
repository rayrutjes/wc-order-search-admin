<?php

/*
 * This file is part of AlgoliaIntegration library.
 * (c) Raymond Rutjes <raymond.rutjes@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace AlgoliaOrdersSearch\AlgoliaIntegration\Command;

use AlgoliaOrdersSearch\AlgoliaIntegration\Bus\Command;
use AlgoliaOrdersSearch\AlgoliaIntegration\Bus\Handler;
use AlgoliaOrdersSearch\AlgoliaIntegration\Index\Repository;

final class ReIndexUsingTemporaryIndexHandler implements Handler
{
    /**
     * @var Repository
     */
    private $indexRepository;

    /**
     * @param Repository $indexRepository
     */
    public function __construct(Repository $indexRepository)
    {
        $this->indexRepository = $indexRepository;
    }

    /**
     * @param Command $command
     */
    public function handle(Command $command)
    {
        if (!$command instanceof ReIndexUsingTemporaryName) {
            throw new \RuntimeException(sprintf('Unsupported command %s.', get_class($command)));
        }

        $index = $this->indexRepository->get($command->getIndexName());
        $index->reIndexUsingTemporaryIndex($command->getKeepExistingSettings(), $command->getRecordsPerBatch());
    }
}
