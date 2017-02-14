<?php

/*
 * This file is part of AlgoliaIntegration library.
 * (c) Raymond Rutjes <raymond.rutjes@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace RayRutjes\AlgoliaIntegration\Index;

interface RecordsProvider
{
    /**
     * @param int $perPage
     *
     * @return int
     */
    public function getTotalPagesCount($perPage);

    /**
     * @param int $page
     * @param int $perPage
     *
     * @return array
     */
    public function getRecords($page, $perPage);
}
