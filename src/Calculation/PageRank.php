<?php
namespace paslandau\PageRank\Calculation;

use paslandau\PageRank\Graph;
use paslandau\PageRank\Logging\LoggerTrait;
use paslandau\PageRank\Node;

class PageRank
{
    use LoggerTrait;

    /** @var float  */
    private $dampingFactor;

    /** @var int  */
    private $maxRounds;
    /**
     * @var float
     */
    private $maxDistance;

    /**
     * @var bool
     */
    private $collapseLinks;

    /**
     * //todo add option to handle dangling links
     * //todo add option to handle nofollowed links
     * @param float $dampingFactor [optional]. Default: 0.85. Damping factor to prevent rank sinks.
     * @param int $maxRounds [optional]. Default: 1000. Maximum number of rounds to use for the calculation. Prevents long runtimes due to a very low value for $maxDistance.
     * @param float $maxDistance [optional]. Default: 0.000001. The maximum distance (absolute difference) between the old and the new PageRank of each Node before the calculation is stopped.
     * @param bool $collapseLinks [optional]. Default: false. If true multiple links from the same originating node to the same target node are only counted as one link.
     */
    function __construct($dampingFactor = null, $maxRounds = null, $maxDistance = null, $collapseLinks = null)
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
        if($collapseLinks === null) {
            $collapseLinks = false;
        }
        $this->collapseLinks = $collapseLinks;
    }

    /**
     * Calculate the PageRank for all nodes in $graph.
     * @param Graph $graph
     * @param bool $keepAllRoundData [optional]. Default: false. If true, the values for every round of calculation is kept. Great for understanding what's going on during the calculation.
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
        //transform (remaining) disconnected Nodes (that are not part of an Edge) to PageRankNodes
        foreach($ns as $node){
            $this->getPageRankNode($node, $nodes);
        }
        $this->getLogger()->debug("Got ".count($nodes)." nodes in total");
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
            $this->getLogger()->debug("Calculating round $round. Max rounds: {$this->maxRounds}. Last max distance: {$distance}. Required max distance: {$this->maxDistance}");
        }while($round < $this->maxRounds && ($distance == 0 ||$distance > $this->maxDistance));
        $result = new PageRankResult($graph, $rounds);
        return $result;
    }

    /**
     * @param Node $node
     * @param PageRankNode[] $allNodes
     * @return PageRankNode
     */
    private function getPageRankNode(Node $node, array &$allNodes){
        if(!array_key_exists($node->getName(),$allNodes)){
            $prNode = new PageRankNode($node, $this->collapseLinks);
            $allNodes[$node->getName()] = $prNode;
        }
        return $allNodes[$node->getName()];
    }

    /**
     * @return float
     */
    public function getDampingFactor()
    {
        return $this->dampingFactor;
    }

    /**
     * @param float $dampingFactor
     */
    public function setDampingFactor($dampingFactor)
    {
        $this->dampingFactor = $dampingFactor;
    }

    /**
     * @return int
     */
    public function getMaxRounds()
    {
        return $this->maxRounds;
    }

    /**
     * @param int $maxRounds
     */
    public function setMaxRounds($maxRounds)
    {
        $this->maxRounds = $maxRounds;
    }

    /**
     * @return float
     */
    public function getMaxDistance()
    {
        return $this->maxDistance;
    }

    /**
     * @param float $maxDistance
     */
    public function setMaxDistance($maxDistance)
    {
        $this->maxDistance = $maxDistance;
    }

    /**
     * @return boolean
     */
    public function isCollapseLinks()
    {
        return $this->collapseLinks;
    }

    /**
     * @param boolean $collapseLinks
     */
    public function setCollapseLinks($collapseLinks)
    {
        $this->collapseLinks = $collapseLinks;
    }
}