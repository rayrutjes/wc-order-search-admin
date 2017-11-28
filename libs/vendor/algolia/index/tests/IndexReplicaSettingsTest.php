<?php

/*
 * This file is part of AlgoliaIndex library.
 * (c) Raymond Rutjes for Algolia <raymond.rutjes@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace WC_Order_Search_Admin\Algolia\Index\Tests;

use WC_Order_Search_Admin\Algolia\Index\IndexReplicaSettings;
class IndexReplicaSettingsTest extends \PHPUnit_Framework_TestCase
{
    public function testCanRetrieveItsName()
    {
        $settings = new \WC_Order_Search_Admin\Algolia\Index\IndexReplicaSettings('name', array());
        $this->assertEquals('name', $settings->getIndexName());
    }
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCanNotContainReplicasOption()
    {
        new \WC_Order_Search_Admin\Algolia\Index\IndexReplicaSettings('name', array('replicas' => array()));
    }
    public function testCanRetrieveOptionsAsAnArray()
    {
        $options = array('searchableAttributes' => array('name'), 'customRanking' => array('desc(timestamp)'));
        $settings = new \WC_Order_Search_Admin\Algolia\Index\IndexReplicaSettings('name', $options);
        $this->assertEquals($options, $settings->toArray());
    }
}
