<?php

namespace WC_Order_Search_Admin\Composer\Installers;

class ReIndexInstaller extends \WC_Order_Search_Admin\Composer\Installers\BaseInstaller
{
    protected $locations = array('theme' => 'themes/{$name}/', 'plugin' => 'plugins/{$name}/');
}
