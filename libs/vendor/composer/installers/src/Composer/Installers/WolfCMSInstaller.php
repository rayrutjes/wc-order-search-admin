<?php

namespace WC_Order_Search_Admin\Composer\Installers;

class WolfCMSInstaller extends \WC_Order_Search_Admin\Composer\Installers\BaseInstaller
{
    protected $locations = array('plugin' => 'wolf/plugins/{$name}/');
}
