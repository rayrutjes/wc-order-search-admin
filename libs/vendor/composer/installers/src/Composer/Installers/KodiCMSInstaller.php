<?php

namespace WC_Order_Search_Admin\Composer\Installers;

class KodiCMSInstaller extends \WC_Order_Search_Admin\Composer\Installers\BaseInstaller
{
    protected $locations = array('plugin' => 'cms/plugins/{$name}/', 'media' => 'cms/media/vendor/{$name}/');
}
