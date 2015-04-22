<?php

namespace paslandau\PageRank;

class Node{
    /**
     * @var string
     */
    private $name;

    /**
     * @param string $name
     */
    public function __construct($name){
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}