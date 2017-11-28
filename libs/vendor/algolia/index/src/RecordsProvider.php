<?php

/*
 * This file is part of AlgoliaIndex library.
 * (c) Raymond Rutjes for Algolia <raymond.rutjes@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace WC_Order_Search_Admin\Algolia\Index;

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
    /**
     * @param mixed $id
     *
     * @return array
     */
    public function getRecordsForId($id);
}
