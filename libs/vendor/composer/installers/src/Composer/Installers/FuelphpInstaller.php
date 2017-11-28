<?php

namespace WC_Order_Search_Admin\Composer\Installers;

class FuelphpInstaller extends \WC_Order_Search_Admin\Composer\Installers\BaseInstaller
{
    protected $locations = array('component' => 'components/{$name}/');
}
