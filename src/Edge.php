<?php

namespace paslandau\PageRank;


class Edge {
    /**
     * @var Node
     */
    private $from;
    /**
     * @var Node
     */
    private $to;

    /**
     * @param Node $from
     * @param Node $to
     */
    function __construct(Node $from, Node $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return Node
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return Node
     */
    public function getTo()
    {
        return $this->to;
    }
}