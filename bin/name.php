<?php

/*
 * This file is part of Algolia Integration library.
 * (c) Raymond Rutjes for Algolia <raymond.rutjes@algolia.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

$randomName = str_replace('.', '', uniqid('UniqueComposerAutoloaderInit', true));

$autoloaderContent = file_get_contents('./libs/autoload.php');

$start = strpos($autoloaderContent, 'ComposerAutoloaderInit');
$end = strpos($autoloaderContent, '::');

$className = substr($autoloaderContent, $start, $end - $start);

$autoloaderContent = str_replace($className, $randomName, $autoloaderContent);
file_put_contents('./libs/autoload.php', $autoloaderContent);

$realAutoloaderContent = file_get_contents('./libs/composer/autoload_real.php');
$realAutoloaderContent = str_replace($className, $randomName, $realAutoloaderContent);
file_put_contents('./libs/composer/autoload_real.php', $realAutoloaderContent);
