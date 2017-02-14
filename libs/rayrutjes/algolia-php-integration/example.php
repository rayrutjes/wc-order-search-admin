<?php

/*
 * This file is part of AlgoliaIntegration library.
 * (c) Raymond Rutjes <raymond.rutjes@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

require_once 'vendor/autoload.php';

// Bootstrap Algolia Client.
$client = new \AlgoliaSearch\Client('app_id', 'admin_api_key');

// Setup the pipes.
$environmentIndexNameInflector = new \RayRutjes\AlgoliaIntegration\Index\EnvironmentIndexNameInflector(new \RayRutjes\AlgoliaIntegration\Index\Environment('dev'));

$algoliaService = new \RayRutjes\AlgoliaIntegration\AlgoliaService($client, $environmentIndexNameInflector);
$dispatcher = new \RayRutjes\AlgoliaIntegration\Bus\AlgoliaServiceDispatcher($algoliaService);

// Manually dispatch some commands.
$dispatcher->dispatch(new RayRutjes\AlgoliaIntegration\Command\DeleteIndex('products'));
new \RayRutjes\AlgoliaIntegration\Command\Batch();

// ReIndexing

$reIndexInPlace = new \RayRutjes\AlgoliaIntegration\Index\ReIndexInPlace();
