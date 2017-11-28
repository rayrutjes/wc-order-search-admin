<?php

namespace WC_Order_Search_Admin\Composer\Installers\Test;

use Composer\Installers\MayaInstaller;
use Composer\Package\Package;
use Composer\Composer;
class MayaInstallerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MayaInstaller
     */
    private $installer;
    public function setUp()
    {
        $this->installer = new \WC_Order_Search_Admin\Composer\Installers\MayaInstaller(new \WC_Order_Search_Admin\Composer\Package\Package('NyanCat', '4.2', '4.2'), new \WC_Order_Search_Admin\Composer\Composer());
    }
    /**
     * @dataProvider packageNameInflectionProvider
     */
    public function testInflectPackageVars($type, $name, $expected)
    {
        $this->assertEquals(array('name' => $expected, 'type' => $type), $this->installer->inflectPackageVars(array('name' => $name, 'type' => $type)));
    }
    public function packageNameInflectionProvider()
    {
        return array(
            // Should keep module name StudlyCase
            array('maya-module', 'user-profile', 'UserProfile'),
            array('maya-module', 'maya-module', 'Maya'),
            array('maya-module', 'blog', 'Blog'),
            // tests that exactly one '-module' is cut off
            array('maya-module', 'some-module-module', 'SomeModule'),
        );
    }
}
