<?php

namespace paslandau\PageRank;


class Graph {
    /**
     * @var Node[]
     */
    private $nodes;

    /**
     * @var Edge[]
     */
    private $edges;

    /**
     * @param Edge $edge
     */
    public function addEdge(Edge $edge){
        $this->edges[] = $edge;
        $this->addNode($edge->getFrom());
        $this->addNode($edge->getTo());
    }

    /**
     * @param Node $node
     */
    public function addNode(Node $node){
        $this->nodes[$node->getName()] = $node;
    }

    /**
     * @return Node[]
     */
    public function getNodes()
    {
        return $this->nodes;
    }

    /**
     * @return Edge[]
     */
    public function getEdges()
    {
        return $this->edges;
    }

}