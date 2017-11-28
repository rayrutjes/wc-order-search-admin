<?php

namespace WC_Order_Search_Admin\Composer\Installers;

class Concrete5Installer extends \WC_Order_Search_Admin\Composer\Installers\BaseInstaller
{
    protected $locations = array('core' => 'concrete/', 'block' => 'application/blocks/{$name}/', 'package' => 'packages/{$name}/', 'theme' => 'application/themes/{$name}/', 'update' => 'updates/{$name}/');
}
