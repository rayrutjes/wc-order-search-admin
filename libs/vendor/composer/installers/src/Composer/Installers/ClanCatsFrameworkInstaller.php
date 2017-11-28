<?php

namespace WC_Order_Search_Admin\Composer\Installers;

class ClanCatsFrameworkInstaller extends \WC_Order_Search_Admin\Composer\Installers\BaseInstaller
{
    protected $locations = array('ship' => 'CCF/orbit/{$name}/', 'theme' => 'CCF/app/themes/{$name}/');
}
