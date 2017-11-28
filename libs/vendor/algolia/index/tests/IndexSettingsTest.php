<?php

/*
 * This file is part of AlgoliaIndex library.
 * (c) Raymond Rutjes for Algolia <raymond.rutjes@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace WC_Order_Search_Admin\Algolia\Index\Tests;

use WC_Order_Search_Admin\Algolia\Index\IndexReplicaSettings;
use WC_Order_Search_Admin\Algolia\Index\IndexSettings;
class IndexSettingsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testShouldEnsureReplicasOptionIsAnArray()
    {
        new \WC_Order_Search_Admin\Algolia\Index\IndexSettings(array('replicas' => true));
    }
    public function testCanRetrieveReplicaSettings()
    {
        $replicaSettings1 = array('customRanking' => array('asc(name)'));
        $replicaSettings2 = array('customRanking' => array('desc(name)'));
        $settings = new \WC_Order_Search_Admin\Algolia\Index\IndexSettings(array('replicas' => array('products_asc' => $replicaSettings1, 'products_desc' => $replicaSettings2)));
        $replicaSettings = $settings->getReplicaSettings();
        $this->assertCount(2, $replicaSettings);
        $this->assertInstanceOf('Algolia\\Index\\IndexReplicaSettings', $replicaSettings[0]);
        $this->assertEquals('products_asc', $replicaSettings[0]->getIndexName());
        $this->assertEquals($replicaSettings1, $replicaSettings[0]->toArray());
        $this->assertInstanceOf('Algolia\\Index\\IndexReplicaSettings', $replicaSettings[1]);
        $this->assertEquals('products_desc', $replicaSettings[1]->getIndexName());
        $this->assertEquals($replicaSettings2, $replicaSettings[1]->toArray());
    }
    public function testShouldKeepOnlyReplicaIndexNamesInArray()
    {
        $replicaSettings1 = array('customRanking' => array('asc(name)'));
        $replicaSettings2 = array('customRanking' => array('desc(name)'));
        $settings = new \WC_Order_Search_Admin\Algolia\Index\IndexSettings(array('replicas' => array('products_asc' => $replicaSettings1, 'products_desc' => $replicaSettings2)));
        $expected = array('replicas' => array('products_asc', 'products_desc'));
        $this->assertEquals($expected, $settings->toArray());
    }
    public function testReplicasCanBeJustStrings()
    {
        $replicaSettings1 = array('customRanking' => array('asc(name)'));
        $settings = new \WC_Order_Search_Admin\Algolia\Index\IndexSettings(array('replicas' => array('products_asc' => $replicaSettings1, 'products_desc')));
        $expected = array('replicas' => array('products_asc', 'products_desc'));
        $this->assertEquals($expected, $settings->toArray());
        $replicaSettings = $settings->getReplicaSettings();
        $this->assertCount(2, $replicaSettings);
        $this->assertInstanceOf('Algolia\\Index\\IndexReplicaSettings', $replicaSettings[1]);
        $this->assertEquals('products_desc', $replicaSettings[1]->getIndexName());
        $this->assertEquals(array(), $replicaSettings[1]->toArray());
    }
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testShouldNotAcceptReplicaSettingsWithIndexName()
    {
        $replicaSettings1 = array('customRanking' => array('asc(name)'));
        $settings = new \WC_Order_Search_Admin\Algolia\Index\IndexSettings(array('replicas' => array($replicaSettings1)));
    }
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testShouldNotAcceptReplicaSettingsAsNonArrayValue()
    {
        $settings = new \WC_Order_Search_Admin\Algolia\Index\IndexSettings(array('replicas' => array('products_asc' => true)));
    }
    public function testShouldAcceptIndexReplicaSettingsInstances()
    {
        $replicaSettings = new \WC_Order_Search_Admin\Algolia\Index\IndexReplicaSettings('test');
        $settings = new \WC_Order_Search_Admin\Algolia\Index\IndexSettings(array('replicas' => array($replicaSettings)));
        $results = $settings->getReplicaSettings();
        $this->assertSame($replicaSettings, $results[0]);
    }
}
