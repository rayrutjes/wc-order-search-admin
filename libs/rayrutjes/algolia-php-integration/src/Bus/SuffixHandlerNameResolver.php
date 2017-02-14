<?php

/*
 * This file is part of AlgoliaIntegration library.
 * (c) Raymond Rutjes <raymond.rutjes@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace RayRutjes\AlgoliaIntegration\Bus;

final class SuffixHandlerNameResolver implements HandlerNameResolver
{
    /**
     * @param Command $command
     *
     * @return string
     */
    public function resolve(Command $command)
    {
        return get_class($command).'Handler';
    }
}
