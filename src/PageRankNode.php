<?php

namespace paslandau\PageRank;

class PageRankNode{
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
    private $pr;
    /**
     * @var PageRankNode[]
     */
    private $inlinks;
    /**
     * @var PageRankNode[]
     */
    private $outlinks;

    /**
     * @var bool
     */
    private $collapseLinksToSamePageRankNode;

    /**
     * @param Node $node
     * @param null|bool $collapse [optional]. Default: false.
     */
    public function __construct(Node $node, $collapse = null){
        $this->node = $node;
        if($collapse === null) {
            $collapse = false;
        }
        $this->collapseLinksToSamePageRankNode = $collapse;
        $this->pr = 1;
        $this->oldPr = 1;
        $this->outlinks = [];
        $this->inlinks = [];
    }

    public function addLinkTo(PageRankNode $n){
        if($this->collapseLinksToSamePageRankNode) {
            $this->outlinks[$n->getName()] = $n;
        }else{
            $this->outlinks[] = $n;
        }
    }

    public function addLinkFrom(PageRankNode $n){
        if($this->collapseLinksToSamePageRankNode) {
            $this->inlinks[$n->getName()] = $n;
        }else{
            $this->inlinks[] = $n;
        }
    }

    public function calculatePageRank($dampingFactor, $totalPages){
        $inLinkSum = 0;
        foreach($this->inlinks as $node){
            $inLinkSum += ($node->getPr()/count($node->getOutlinks()));
        }
        $newPr = ( (1 - $dampingFactor) / $totalPages ) + $dampingFactor * $inLinkSum;
        return $newPr;
    }

    public function __toString(){
        $dec = 4;
        $old = round($this->oldPr,$dec);
        $cur = round($this->pr,$dec);
        $diff = round($this->oldPr - $this->pr,$dec);
        return "{$this->getName()}\t$old\t$cur\t($diff)";
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->node->getName();
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
    public function getPr()
    {
        return $this->pr;
    }

    /**
     * @return PageRankNode[]
     */
    public function getInlinks()
    {
        return $this->inlinks;
    }

    /**
     * @return PageRankNode[]
     */
    public function getOutlinks()
    {
        return $this->outlinks;
    }

    /**
     * @param float $oldPr
     */
    public function setOldPr($oldPr)
    {
        $this->oldPr = $oldPr;
    }

    /**
     * @param float $pr
     */
    public function setPr($pr)
    {
        $this->pr = $pr;
    }
}