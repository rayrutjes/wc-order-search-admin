<?php

/*
 * This file is part of AlgoliaIntegration library.
 * (c) Raymond Rutjes <raymond.rutjes@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

require_once 'vendor/autoload.php';

// Bootstrap Algolia Client.
$client = new \AlgoliaOrdersSearch\Client('app_id', 'admin_api_key');

// Setup the pipes.
$environmentIndexNameInflector = new \AlgoliaOrdersSearch\AlgoliaIntegration\Index\EnvironmentIndexNameInflector(new \AlgoliaOrdersSearch\AlgoliaIntegration\Index\Environment('dev'));

$algoliaService = new \AlgoliaOrdersSearch\AlgoliaIntegration\AlgoliaService($client, $environmentIndexNameInflector);
$dispatcher = new \AlgoliaOrdersSearch\AlgoliaIntegration\Bus\AlgoliaServiceDispatcher($algoliaService);

// Manually dispatch some commands.
$dispatcher->dispatch(new AlgoliaOrdersSearch\AlgoliaIntegration\Command\DeleteIndex('products'));
new \AlgoliaOrdersSearch\AlgoliaIntegration\Command\Batch();

// ReIndexing

$reIndexInPlace = new \AlgoliaOrdersSearch\AlgoliaIntegration\Index\ReIndexInPlace();
