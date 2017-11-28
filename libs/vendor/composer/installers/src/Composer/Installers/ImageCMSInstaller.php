<?php

namespace WC_Order_Search_Admin\Composer\Installers;

class ImageCMSInstaller extends \WC_Order_Search_Admin\Composer\Installers\BaseInstaller
{
    protected $locations = array('template' => 'templates/{$name}/', 'module' => 'application/modules/{$name}/', 'library' => 'application/libraries/{$name}/');
}
