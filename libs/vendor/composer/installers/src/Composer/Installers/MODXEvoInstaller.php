<?php

namespace WC_Order_Search_Admin\Composer\Installers;

/**
 * An installer to handle MODX Evolution specifics when installing packages.
 */
class MODXEvoInstaller extends \WC_Order_Search_Admin\Composer\Installers\BaseInstaller
{
    protected $locations = array('snippet' => 'assets/snippets/{$name}/', 'plugin' => 'assets/plugins/{$name}/', 'module' => 'assets/modules/{$name}/', 'template' => 'assets/templates/{$name}/', 'lib' => 'assets/lib/{$name}/');
}
