<?php

namespace WC_Order_Search_Admin\AlgoliaSearch\Iterators;

use WC_Order_Search_Admin\AlgoliaSearch\Index;
class RuleIterator extends \WC_Order_Search_Admin\AlgoliaSearch\Iterators\AlgoliaIterator
{
    public function __construct(\WC_Order_Search_Admin\AlgoliaSearch\Index $index, $hitsPerPage = 500)
    {
        parent::__construct($index, $hitsPerPage);
    }
    /**
     * The export method is using search internally, this method
     * is used to clean the results, like remove the highlight
     *
     * @param array $hit
     * @return array formatted synonym array
     */
    protected function formatHit(array $hit)
    {
        unset($hit['_highlightResult']);
        return $hit;
    }
    /**
     * Call Algolia' API to get new result batch
     */
    protected function fetchCurrentPageResults()
    {
        $this->response = $this->index->searchRules(array('hitsPerPage' => $this->hitsPerPage, 'page' => $this->getCurrentPage()));
    }
}
