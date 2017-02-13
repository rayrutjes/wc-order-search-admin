<?php

namespace AlgoliaOrdersSearch;

use WP_CLI;
use WP_CLI_Command;

class Commands extends WP_CLI_Command
{
    /**
     * @var OrdersIndex
     */
    private $index;

    /**
     * @var Options
     */
    private $options;

    /**
     * @param OrdersIndex $index
     * @param Options     $options
     */
    public function __construct(OrdersIndex $index, Options $options)
    {
        $this->index = $index;
        $this->options = $options;
    }

    /**
     * ReIndex all orders in Algolia.
     *
     * ## EXAMPLES
     *
     *     wp orders reIndex
     *
     * @when before_wp_load
     */
    public function reIndex($args, $assoc_args)
    {
        WP_CLI::line(sprintf('About push the settings for index %s...', $this->index->getName()));
        $this->index->pushSettings();
        WP_CLI::success(sprintf('Correctly pushed settings to the index "%s".', $this->index->getName()));

        WP_CLI::line('About to push all orders to Algolia. Please be patient...');

        $start = microtime(true);

        $perPage = $this->options->getOrdersToIndexPerBatchCount();
        $totalPages = $this->index->getTotalPagesCount($perPage);

        $progress = WP_CLI\Utils\make_progress_bar('Indexing orders', $totalPages);

        $totalRecordsCount = 0;
        for ($page = 1; $page <= $totalPages; ++$page) {
            $totalRecordsCount += $this->index->pushRecords($page, $perPage);
            $progress->tick();
        }
        $progress->finish();

        $elapsed = microtime(true) - $start;

        WP_CLI::success(sprintf('%d orders indexed in %d seconds!', $totalRecordsCount, $elapsed));
    }
}


