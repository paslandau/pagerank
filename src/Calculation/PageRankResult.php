<?php

namespace paslandau\PageRank\Calculation;
use paslandau\PageRank\Graph;

/**
 * The result of the PageRank calculation of a Graph.
 * @package paslandau\PageRank
 */
class PageRankResult {

    /**
     * @var Graph
     */
    private $graph;

    /**
     * @var PageRankNodeHistory[]
     */
    private $history;

    /**
     * @param Graph $graph
     * @param PageRankNodeHistory[] $history
     */
    function __construct(Graph $graph, array $history)
    {
        $this->graph = $graph;
        $this->history = $history;
    }

    /**
     * @return Graph
     */
    public function getGraph()
    {
        return $this->graph;
    }

    /**
     * @param null|int $roundIndex [optional]. Default: null. If null, the final result (last round) will be used to get the nodes. Otherwise, the round with index $roundIndex is chosen.
     * @return PageRankNode[]
     */
    public function getNodes($roundIndex = null)
    {
        $nodes = [];
        if($roundIndex === null){
            $history = end($this->history);
        }elseif(!array_key_exists($roundIndex,$this->history)) {
            throw new \InvalidArgumentException("Index '$roundIndex' does not exist.");
        }else{
            $history = $this->history[$roundIndex];
        }
        foreach($history->getEntries() as $key => $entry){
            $nodes[$key] = $entry->getNode();
        }
        return $nodes;
    }

    /**
     * @return PageRankNodeHistory[]
     */
    public function getHistory()
    {
        return $this->history;
    }

    /**
     * @return PageRankNodeHistory
     */
    public function getLastHistoryEntry(){
        $last = end($this->history);
        return $last;
    }
}