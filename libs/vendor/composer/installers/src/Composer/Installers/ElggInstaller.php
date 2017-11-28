<?php

namespace WC_Order_Search_Admin\Composer\Installers;

class ElggInstaller extends \WC_Order_Search_Admin\Composer\Installers\BaseInstaller
{
    protected $locations = array('plugin' => 'mod/{$name}/');
}
