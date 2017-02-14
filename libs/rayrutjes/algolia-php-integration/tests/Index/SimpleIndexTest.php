<?php

/*
 * This file is part of AlgoliaIntegration library.
 * (c) Raymond Rutjes <raymond.rutjes@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace RayRutjes\AlgoliaIntegration\Tests\Index;

use RayRutjes\AlgoliaIntegration\Index\IndexSettings;
use RayRutjes\AlgoliaIntegration\Index\SimpleIndex;

class SimpleIndexTest extends \PHPUnit_Framework_TestCase
{
    private $name;

    private $settings;

    private $recordsProvider;

    private $algoliaClient;

    /**
     * @var SimpleIndex
     */
    private $index;

    protected function setUp()
    {
        $this->name = 'name';
        $this->settings = new IndexSettings(array());
        $this->recordsProvider = $this->getMockBuilder('RayRutjes\AlgoliaIntegration\Index\RecordsProvider')->getMock();
        $this->algoliaClient = $this->getMockBuilder('AlgoliaSearch\Client')->getMock();

        $this->index = new SimpleIndex($this->name, $this->settings, $this->recordsProvider, $this->algoliaClient);
    }

    public function testCanRetrieveName()
    {
        $this->assertEquals($this->name, $this->index->getName());
    }
}
