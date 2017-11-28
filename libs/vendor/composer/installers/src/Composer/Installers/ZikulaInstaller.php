<?php

namespace WC_Order_Search_Admin\Composer\Installers;

class ZikulaInstaller extends \WC_Order_Search_Admin\Composer\Installers\BaseInstaller
{
    protected $locations = array('module' => 'modules/{$vendor}-{$name}/', 'theme' => 'themes/{$vendor}-{$name}/');
}
