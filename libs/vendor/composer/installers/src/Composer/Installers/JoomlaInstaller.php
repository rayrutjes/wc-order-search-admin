<?php

namespace WC_Order_Search_Admin\Composer\Installers;

class JoomlaInstaller extends \WC_Order_Search_Admin\Composer\Installers\BaseInstaller
{
    protected $locations = array('component' => 'components/{$name}/', 'module' => 'modules/{$name}/', 'template' => 'templates/{$name}/', 'plugin' => 'plugins/{$name}/', 'library' => 'libraries/{$name}/');
}
