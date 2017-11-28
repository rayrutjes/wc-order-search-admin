<?php

namespace WC_Order_Search_Admin\Composer\Installers;

class LaravelInstaller extends \WC_Order_Search_Admin\Composer\Installers\BaseInstaller
{
    protected $locations = array('library' => 'libraries/{$name}/');
}
