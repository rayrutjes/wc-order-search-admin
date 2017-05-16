<?php

/*
 * This file is part of AlgoliaIndex library.
 * (c) Raymond Rutjes for Algolia <raymond.rutjes@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace WC_Order_Search_Admin\Index\Tests;

use WC_Order_Search_Admin\Index\IndexSettings;
use WC_Order_Search_Admin\Index\SimpleIndex;

class SimpleIndexTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var IndexSettings
     */
    private $settings;

    /**
     * @var \PHPUnit_Framework_MockObject_MockBuilder
     */
    private $recordsProvider;

    /**
     * @var \PHPUnit_Framework_MockObject_MockBuilder
     */
    private $algoliaClient;

    /**
     * @var \PHPUnit_Framework_MockObject_MockBuilder
     */
    private $algoliaIndex;

    /**
     * @var SimpleIndex
     */
    private $index;

    protected function setUp()
    {
        $this->name = 'name';
        $this->settings = new IndexSettings(array());
        $this->recordsProvider = $this->getMockBuilder('WC_Order_Search_Admin\Index\RecordsProvider')->getMock();
        $this->algoliaClient = $this->getMockBuilder('WC_Order_Search_Admin\Client')->disableOriginalConstructor()->getMock();
        $this->algoliaIndex = $this->getMockBuilder('WC_Order_Search_Admin\Index')->disableOriginalConstructor()->getMock();

        $this->algoliaClient->method('initIndex')->with($this->name)->willReturn($this->algoliaIndex);

        $this->index = new SimpleIndex($this->name, $this->settings, $this->recordsProvider, $this->algoliaClient);
    }

    public function testCanRetrieveName()
    {
        $this->assertEquals($this->name, $this->index->getName());
    }

    public function testCanBeDeleted()
    {
        $this->algoliaClient->expects($this->once())
            ->method('deleteIndex')
            ->with($this->name);

        $this->index->delete();
    }

    public function testCanBeCleared()
    {
        $this->algoliaIndex->expects($this->once())->method('clearIndex');

        $this->index->clear();
    }
}
