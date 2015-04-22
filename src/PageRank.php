<?php
namespace paslandau\PageRank;

use paslandau\TchiboProductSimilarity\Traits\LoggerTrait;

class PageRank
{
    use LoggerTrait;

    /** @var float|null  */
    private $dampingFactor;
    /** @var int|null  */
    private $maxRounds;
    /**
     * @var float|null
     */
    private $maxDistance;

    /**
     * @param null|float $dampingFactor [optional]. Default: 0.85.
     * @param null|int $maxRounds [optional]. Default: 1000.
     * @param null|float $maxDistance [optional]. Default: 0.000001.
     */
    function __construct($dampingFactor = null, $maxRounds = null, $maxDistance = null)
    {
        if($dampingFactor === null) {
            $dampingFactor = 0.85;
        }
        $this->dampingFactor = $dampingFactor;
        if($maxRounds === null) {
            $maxRounds = 1000;
        }
        $this->maxRounds = $maxRounds;
        if($maxDistance === null) {
            $maxDistance = 0.000001;
        }
        $this->maxDistance = $maxDistance;
    }

    /**
     * @param Graph $graph
     * @param bool $keepAllRoundData
     * @return PageRankResult
     */
    public function calculatePagerank(Graph $graph, $keepAllRoundData = false){
        $round = 0;
        $rounds = [];
        $ns = $graph->getNodes();
        $edges = $graph->getEdges();
        /** @var PageRankNode[] $nodes */
        $nodes = [];
        //transform to PageRankNodes
        foreach($edges as $edge){
            $prFromNode = $this->getPageRankNode($edge->getFrom(), $nodes);
            $prNodeTo = $this->getPageRankNode($edge->getTo(), $nodes);
            $prFromNode->addLinkTo($prNodeTo);
            $prNodeTo->addLinkFrom($prFromNode);
        }
        //transform disconnected Nodes to PageRankNodes
        foreach($ns as $node){
            $this->getPageRankNode($node, $nodes);
        }
        do{
            $distance = 0;
            $currentRound = new PageRankNodeHistory($round);
            $newPrCache = [];
            foreach($nodes as $key => $node){
                $node->setOldPr($node->getPr());
                $newPr = $node->calculatePageRank($this->dampingFactor, count($nodes));
                $curDistance = abs($node->getOldPr() - $newPr);
                if($curDistance > $distance){
                    $distance = $curDistance;
                }
                $entry = new PageRankNodeHistoryEntry($node,$node->getOldPr(),$newPr);
                $newPrCache[$key] = $newPr;
                $currentRound->addEntry($entry);
            }
            foreach($nodes as $key => $node){
                $newPr = $newPrCache[$key];
                $node->setPr($newPr);
            }
            if(!$keepAllRoundData){
                $rounds = [];
            }
            $rounds[$round] = $currentRound;
            $round++;
            $this->getLogger()->debug($round);
        }while($round < $this->maxRounds && ($distance == 0 ||$distance > $this->maxDistance));
        $result = new PageRankResult($graph, $nodes,$rounds);
        return $result;
    }

    /**
     * @param Node $node
     * @param PageRankNode[] $allNodes
     * @return PageRankNode
     */
    private function getPageRankNode(Node $node, array &$allNodes){
        if(!array_key_exists($node->getName(),$allNodes)){
            $prNode = new PageRankNode($node);
            $allNodes[$node->getName()] = $prNode;
        }
        return $allNodes[$node->getName()];
    }
}