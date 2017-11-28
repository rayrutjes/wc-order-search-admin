<?php

namespace WC_Order_Search_Admin\Composer\Installers;

class WordPressInstaller extends \WC_Order_Search_Admin\Composer\Installers\BaseInstaller
{
    protected $locations = array('plugin' => 'wp-content/plugins/{$name}/', 'theme' => 'wp-content/themes/{$name}/', 'muplugin' => 'wp-content/mu-plugins/{$name}/');
}
