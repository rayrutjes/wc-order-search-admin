<?php

/*
 * This file is part of AlgoliaIntegration library.
 * (c) Raymond Rutjes <raymond.rutjes@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace RayRutjes\AlgoliaIntegration\Index;

interface Repository
{
    /**
     * @param string $key
     *
     * @return Index
     */
    public function get($key);

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has($key);
}
