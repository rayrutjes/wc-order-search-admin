<?php

namespace WC_Order_Search_Admin\Composer\Installers\Test;

use Composer\Installers\PimcoreInstaller;
use Composer\Package\Package;
use Composer\Composer;
class PimcoreInstallerTest extends \WC_Order_Search_Admin\Composer\Installers\Test\TestCase
{
    private $composer;
    private $io;
    /**
     * setUp
     *
     * @return void
     */
    public function setUp()
    {
        $this->package = new \WC_Order_Search_Admin\Composer\Package\Package('CamelCased', '1.0', '1.0');
        $this->io = $this->getMock('Composer\\IO\\PackageInterface');
        $this->composer = new \WC_Order_Search_Admin\Composer\Composer();
    }
    /**
     * testInflectPackageVars
     *
     * @return void
     */
    public function testInflectPackageVars()
    {
        $installer = new \WC_Order_Search_Admin\Composer\Installers\PimcoreInstaller($this->package, $this->composer);
        $result = $installer->inflectPackageVars(array('name' => 'CamelCased'));
        $this->assertEquals($result, array('name' => 'CamelCased'));
        $installer = new \WC_Order_Search_Admin\Composer\Installers\PimcoreInstaller($this->package, $this->composer);
        $result = $installer->inflectPackageVars(array('name' => 'with-dash'));
        $this->assertEquals($result, array('name' => 'WithDash'));
        $installer = new \WC_Order_Search_Admin\Composer\Installers\PimcoreInstaller($this->package, $this->composer);
        $result = $installer->inflectPackageVars(array('name' => 'with_underscore'));
        $this->assertEquals($result, array('name' => 'WithUnderscore'));
    }
}
