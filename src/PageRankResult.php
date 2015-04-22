<?php

namespace paslandau\PageRank;


class PageRankResult {

    /**
     * @var Graph
     */
    private $graph;

    /**
     * @var PageRankNode[]
     */
    private $nodes;

    /**
     * @var PageRankNodeHistory[]
     */
    private $history;

    /**
     * @param Graph $graph
     * @param PageRankNode[] $nodes
     * @param PageRankNodeHistory[] $history
     */
    function __construct(Graph $graph, array $nodes, array $history)
    {
        $this->graph = $graph;
        $this->nodes = $nodes;
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
     * @return PageRankNode[]
     */
    public function getNodes()
    {
        return $this->nodes;
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