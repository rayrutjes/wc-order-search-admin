<?php

namespace WC_Order_Search_Admin\Composer\Installers;

class WHMCSInstaller extends \WC_Order_Search_Admin\Composer\Installers\BaseInstaller
{
    protected $locations = array('gateway' => 'modules/gateways/{$name}/');
}
