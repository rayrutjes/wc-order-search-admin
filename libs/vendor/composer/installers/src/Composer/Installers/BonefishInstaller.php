<?php

namespace WC_Order_Search_Admin\Composer\Installers;

class BonefishInstaller extends \WC_Order_Search_Admin\Composer\Installers\BaseInstaller
{
    protected $locations = array('package' => 'Packages/{$vendor}/{$name}/');
}
