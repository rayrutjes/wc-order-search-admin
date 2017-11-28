<?php

namespace WC_Order_Search_Admin\Composer\Installers\Test;

use Composer\Installers\CakePHPInstaller;
use Composer\Repository\RepositoryManager;
use Composer\Repository\InstalledArrayRepository;
use Composer\Package\Package;
use Composer\Package\RootPackage;
use Composer\Package\Link;
use Composer\Package\Version\VersionParser;
use Composer\Composer;
use Composer\Config;
class CakePHPInstallerTest extends \WC_Order_Search_Admin\Composer\Installers\Test\TestCase
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
        $this->composer->setConfig(new \WC_Order_Search_Admin\Composer\Config(false));
    }
    /**
     * testInflectPackageVars
     *
     * @return void
     */
    public function testInflectPackageVars()
    {
        $installer = new \WC_Order_Search_Admin\Composer\Installers\CakePHPInstaller($this->package, $this->composer);
        $result = $installer->inflectPackageVars(array('name' => 'CamelCased'));
        $this->assertEquals($result, array('name' => 'CamelCased'));
        $installer = new \WC_Order_Search_Admin\Composer\Installers\CakePHPInstaller($this->package, $this->composer);
        $result = $installer->inflectPackageVars(array('name' => 'with-dash'));
        $this->assertEquals($result, array('name' => 'WithDash'));
        $installer = new \WC_Order_Search_Admin\Composer\Installers\CakePHPInstaller($this->package, $this->composer);
        $result = $installer->inflectPackageVars(array('name' => 'with_underscore'));
        $this->assertEquals($result, array('name' => 'WithUnderscore'));
        $installer = new \WC_Order_Search_Admin\Composer\Installers\CakePHPInstaller($this->package, $this->composer);
        $result = $installer->inflectPackageVars(array('name' => 'cake/acl'));
        $this->assertEquals($result, array('name' => 'Cake/Acl'));
        $installer = new \WC_Order_Search_Admin\Composer\Installers\CakePHPInstaller($this->package, $this->composer);
        $result = $installer->inflectPackageVars(array('name' => 'cake/debug-kit'));
        $this->assertEquals($result, array('name' => 'Cake/DebugKit'));
    }
    /**
     * Test getLocations returning appropriate values based on CakePHP version
     *
     */
    public function testGetLocations()
    {
        $package = new \WC_Order_Search_Admin\Composer\Package\RootPackage('CamelCased', '1.0', '1.0');
        $composer = $this->composer;
        $rm = new \WC_Order_Search_Admin\Composer\Repository\RepositoryManager($this->getMock('Composer\\IO\\IOInterface'), $this->getMock('Composer\\Config'));
        $composer->setRepositoryManager($rm);
        $installer = new \WC_Order_Search_Admin\Composer\Installers\CakePHPInstaller($package, $composer);
        // 2.0 < cakephp < 3.0
        $this->setCakephpVersion($rm, '2.0.0');
        $result = $installer->getLocations();
        $this->assertContains('Plugin/', $result['plugin']);
        $this->setCakephpVersion($rm, '2.5.9');
        $result = $installer->getLocations();
        $this->assertContains('Plugin/', $result['plugin']);
        $this->setCakephpVersion($rm, '~2.5');
        $result = $installer->getLocations();
        $this->assertContains('Plugin/', $result['plugin']);
        // special handling for 2.x versions when 3.x is still in development
        $this->setCakephpVersion($rm, 'dev-master');
        $result = $installer->getLocations();
        $this->assertContains('Plugin/', $result['plugin']);
        $this->setCakephpVersion($rm, '>=2.5');
        $result = $installer->getLocations();
        $this->assertContains('Plugin/', $result['plugin']);
        // cakephp >= 3.0
        $this->setCakephpVersion($rm, '3.0.*-dev');
        $result = $installer->getLocations();
        $this->assertContains('vendor/{$vendor}/{$name}/', $result['plugin']);
        $this->setCakephpVersion($rm, '~8.8');
        $result = $installer->getLocations();
        $this->assertContains('vendor/{$vendor}/{$name}/', $result['plugin']);
    }
    protected function setCakephpVersion($rm, $version)
    {
        $parser = new \WC_Order_Search_Admin\Composer\Package\Version\VersionParser();
        list(, $version) = explode(' ', $parser->parseConstraints($version));
        $installed = new \WC_Order_Search_Admin\Composer\Repository\InstalledArrayRepository();
        $package = new \WC_Order_Search_Admin\Composer\Package\Package('cakephp/cakephp', $version, $version);
        $installed->addPackage($package);
        $rm->setLocalRepository($installed);
    }
}
