<?php

/*
 * This file is part of Composer.
 *
 * (c) Nils Adermann <naderman@naderman.de>
 *     Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WC_Order_Search_Admin\Composer\Installers\Test;

use Composer\Package\Version\VersionParser;
use Composer\Package\Package;
use Composer\Package\AliasPackage;
use Composer\Package\LinkConstraint\VersionConstraint;
use Composer\Util\Filesystem;
abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    private static $parser;
    protected static function getVersionParser()
    {
        if (!self::$parser) {
            self::$parser = new \WC_Order_Search_Admin\Composer\Package\Version\VersionParser();
        }
        return self::$parser;
    }
    protected function getVersionConstraint($operator, $version)
    {
        return new \WC_Order_Search_Admin\Composer\Package\LinkConstraint\VersionConstraint($operator, self::getVersionParser()->normalize($version));
    }
    protected function getPackage($name, $version)
    {
        $normVersion = self::getVersionParser()->normalize($version);
        return new \WC_Order_Search_Admin\Composer\Package\Package($name, $normVersion, $version);
    }
    protected function getAliasPackage($package, $version)
    {
        $normVersion = self::getVersionParser()->normalize($version);
        return new \WC_Order_Search_Admin\Composer\Package\AliasPackage($package, $normVersion, $version);
    }
    protected function ensureDirectoryExistsAndClear($directory)
    {
        $fs = new \WC_Order_Search_Admin\Composer\Util\Filesystem();
        if (is_dir($directory)) {
            $fs->removeDirectory($directory);
        }
        mkdir($directory, 0777, true);
    }
}
