<?php

/*
 * This file is part of Algolia Orders Search for WooCommerce library.
 * (c) Raymond Rutjes <raymond.rutjes@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

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
     * @var \cli\progress\Bar
     */
    private $progress;

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
     *
     * @param mixed $args
     * @param mixed $assoc_args
     */
    public function reIndex($args, $assoc_args)
    {
        WP_CLI::line(sprintf(__('About push the settings for index %s...', 'wc-orders-search-algolia'), $this->index->getName()));
        $this->index->pushSettings();
        WP_CLI::success(sprintf(__('Correctly pushed settings to the index "%s".', 'wc-orders-search-algolia'), $this->index->getName()));

        WP_CLI::line(__('About to push all orders to Algolia. Please be patient...', 'wc-orders-search-algolia'));

        $start = microtime(true);

        $perPage = $this->options->getOrdersToIndexPerBatchCount();

        $self = $this;

        $totalRecordsCount = $this->index->reIndex(false, $perPage, function ($records, $page, $totalPages) use ($self) {
            if (null === $self->progress) {
                $self->progress = WP_CLI\Utils\make_progress_bar(__('Indexing WooCommerce orders', 'wc-orders-search-algolia'), $totalPages);
            }
            $self->progress->tick();
        });

        if (null !== $this->progress) {
            $this->progress->finish();
        }

        $elapsed = microtime(true) - $start;

        WP_CLI::success(sprintf(__('%d orders indexed in %d seconds!', 'wc-orders-search-algolia'), $totalRecordsCount, $elapsed));
    }
}
