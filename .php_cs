<?php
$header = <<<'EOF'
This file is part of WooCommerce Order Search Admin plugin for WordPress.
(c) Raymond Rutjes <raymond.rutjes@gmail.com>
This source file is subject to the GPLv2 license that is bundled
with this source code in the file LICENSE.
EOF;
return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules(array(
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'combine_consecutive_unsets' => true,
        'header_comment' => array('header' => $header),
        'array_syntax' => array('syntax' => 'long'),
        'no_useless_else' => true,
        'no_useless_return' => true,
        'ordered_class_elements' => true,
        'ordered_imports' => true,
        'phpdoc_add_missing_param_annotation' => true,
        'psr4' => true,
        'strict_comparison' => true,
        'strict_param' => true,
        'concat_space' => array('spacing' => 'one'),
        'binary_operator_spaces' => array('align_double_arrow' => true),
    ))
    ->setFinder(
        PhpCsFixer\Finder::create()
        ->in(__DIR__)
        ->exclude('libs')
        ->exclude('node_modules')
    )
;
