<?php

namespace WC_Order_Search_Admin\Composer\Installers;

/**
 * Class DolibarrInstaller
 *
 * @package Composer\Installers
 * @author  Raphaël Doursenaud <rdoursenaud@gpcsolutions.fr>
 */
class DolibarrInstaller extends \WC_Order_Search_Admin\Composer\Installers\BaseInstaller
{
    //TODO: Add support for scripts and themes
    protected $locations = array('module' => 'htdocs/custom/{$name}/');
}
