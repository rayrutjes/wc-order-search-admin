<?php

namespace WC_Order_Search_Admin\Composer\Installers;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
class Plugin implements \WC_Order_Search_Admin\Composer\Plugin\PluginInterface
{
    public function activate(\WC_Order_Search_Admin\Composer\Composer $composer, \WC_Order_Search_Admin\Composer\IO\IOInterface $io)
    {
        $installer = new \WC_Order_Search_Admin\Composer\Installers\Installer($io, $composer);
        $composer->getInstallationManager()->addInstaller($installer);
    }
}
