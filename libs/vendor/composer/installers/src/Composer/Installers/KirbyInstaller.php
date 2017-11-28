<?php

namespace WC_Order_Search_Admin\Composer\Installers;

class KirbyInstaller extends \WC_Order_Search_Admin\Composer\Installers\BaseInstaller
{
    protected $locations = array('plugin' => 'site/plugins/{$name}/', 'field' => 'site/fields/{$name}/', 'tag' => 'site/tags/{$name}/');
}
