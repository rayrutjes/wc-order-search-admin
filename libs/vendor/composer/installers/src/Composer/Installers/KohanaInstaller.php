<?php

namespace WC_Order_Search_Admin\Composer\Installers;

class KohanaInstaller extends \WC_Order_Search_Admin\Composer\Installers\BaseInstaller
{
    protected $locations = array('module' => 'modules/{$name}/');
}
