<?php

namespace WC_Order_Search_Admin\Composer\Installers;

class MagentoInstaller extends \WC_Order_Search_Admin\Composer\Installers\BaseInstaller
{
    protected $locations = array('theme' => 'app/design/frontend/{$name}/', 'skin' => 'skin/frontend/default/{$name}/', 'library' => 'lib/{$name}/');
}
