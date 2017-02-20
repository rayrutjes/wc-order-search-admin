<?php
$header = <<<'EOF'
This file is part of AlgoliaIndex library.
(c) Raymond Rutjes for Algolia <raymond.rutjes@gmail.com>
This source file is subject to the MIT license that is bundled
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
    ))
    ->setFinder(
        PhpCsFixer\Finder::create()->in(__DIR__)
    )
;
