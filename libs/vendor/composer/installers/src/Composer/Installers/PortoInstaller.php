<?php

namespace WC_Order_Search_Admin\Composer\Installers;

class PortoInstaller extends \WC_Order_Search_Admin\Composer\Installers\BaseInstaller
{
    protected $locations = array('container' => 'app/Containers/{$name}/');
}
