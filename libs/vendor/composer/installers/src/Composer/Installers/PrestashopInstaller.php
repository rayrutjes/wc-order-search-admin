<?php

namespace WC_Order_Search_Admin\Composer\Installers;

class PrestashopInstaller extends \WC_Order_Search_Admin\Composer\Installers\BaseInstaller
{
    protected $locations = array('module' => 'modules/{$name}/', 'theme' => 'themes/{$name}/');
}
