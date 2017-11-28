<?php

/*
 * This file is part of AlgoliaIndex library.
 * (c) Raymond Rutjes for Algolia <raymond.rutjes@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace WC_Order_Search_Admin\Algolia\Index;

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
