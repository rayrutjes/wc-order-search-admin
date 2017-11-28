<?php

namespace WC_Order_Search_Admin\Composer\Installers;

class RedaxoInstaller extends \WC_Order_Search_Admin\Composer\Installers\BaseInstaller
{
    protected $locations = array('addon' => 'redaxo/include/addons/{$name}/', 'bestyle-plugin' => 'redaxo/include/addons/be_style/plugins/{$name}/');
}
