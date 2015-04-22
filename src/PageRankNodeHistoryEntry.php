<?php

namespace paslandau\PageRank;


class PageRankNodeHistoryEntry {
    /**
     * @var PageRankNode
     */
    private $node;
    /**
     * @var float
     */
    private $oldPr;
    /**
     * @var float
     */
    private $newPr;

    /**
     * @param PageRankNode $node
     * @param float $oldPr
     * @param float $newPr
     */
    function __construct(PageRankNode $node, $oldPr, $newPr)
    {
        $this->node = $node;
        $this->oldPr = $oldPr;
        $this->newPr = $newPr;
    }

    /**
     * @return PageRankNode
     */
    public function getNode()
    {
        return $this->node;
    }

    /**
     * @return float
     */
    public function getOldPr()
    {
        return $this->oldPr;
    }

    /**
     * @return float
     */
    public function getNewPr()
    {
        return $this->newPr;
    }
}