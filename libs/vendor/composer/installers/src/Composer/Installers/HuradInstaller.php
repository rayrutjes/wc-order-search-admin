<?php

namespace WC_Order_Search_Admin\Composer\Installers;

class HuradInstaller extends \WC_Order_Search_Admin\Composer\Installers\BaseInstaller
{
    protected $locations = array('plugin' => 'plugins/{$name}/', 'theme' => 'plugins/{$name}/');
    /**
     * Format package name to CamelCase
     */
    public function inflectPackageVars($vars)
    {
        $nameParts = explode('/', $vars['name']);
        foreach ($nameParts as &$value) {
            $value = strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $value));
            $value = str_replace(array('-', '_'), ' ', $value);
            $value = str_replace(' ', '', ucwords($value));
        }
        $vars['name'] = implode('/', $nameParts);
        return $vars;
    }
}
