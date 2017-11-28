<?php

namespace WC_Order_Search_Admin\Composer\Installers;

class UserFrostingInstaller extends \WC_Order_Search_Admin\Composer\Installers\BaseInstaller
{
    protected $locations = array('sprinkle' => 'app/sprinkles/{$name}/');
}
