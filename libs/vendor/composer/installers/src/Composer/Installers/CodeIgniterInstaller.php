<?php

namespace WC_Order_Search_Admin\Composer\Installers;

class CodeIgniterInstaller extends \WC_Order_Search_Admin\Composer\Installers\BaseInstaller
{
    protected $locations = array('library' => 'application/libraries/{$name}/', 'third-party' => 'application/third_party/{$name}/', 'module' => 'application/modules/{$name}/');
}
