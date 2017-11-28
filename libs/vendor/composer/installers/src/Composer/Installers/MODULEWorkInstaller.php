<?php

namespace WC_Order_Search_Admin\Composer\Installers;

class MODULEWorkInstaller extends \WC_Order_Search_Admin\Composer\Installers\BaseInstaller
{
    protected $locations = array('module' => 'modules/{$name}/');
}
