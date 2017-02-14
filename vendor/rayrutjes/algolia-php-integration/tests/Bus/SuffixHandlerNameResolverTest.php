<?php

/*
 * This file is part of AlgoliaIntegration library.
 * (c) Raymond Rutjes <raymond.rutjes@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace AlgoliaOrdersSearch\AlgoliaIntegration\Tests\Bus;

use AlgoliaOrdersSearch\AlgoliaIntegration\Bus\SuffixHandlerNameResolver;
use AlgoliaOrdersSearch\AlgoliaIntegration\Command\ClearIndex;

class SuffixHandlerNameResolverTest extends \PHPUnit_Framework_TestCase
{
    public function testCanHandleCommand()
    {
        $command = new ClearIndex('name');

        $nameResolver = new SuffixHandlerNameResolver();
        $actual = $nameResolver->resolve($command);
        $expected = 'AlgoliaOrdersSearch\AlgoliaIntegration\Command\ClearIndexHandler';

        $this->assertEquals($expected, $actual);
    }
}
