<?php

namespace paslandau\PageRank;


class PageRankNodeHistory {
    /**
     * @var int
     */
    private $id;
    /**
     * @var PageRankNodeHistoryEntry[]
     */
    private $entries;

    /**
     * @param int $id
     * @param PageRankNodeHistoryEntry[] $entries
     */
    function __construct($id, array $entries = null)
    {
        $this->id = $id;
        if($entries === null) {
            $entries = [];
        }
        $this->entries = $entries;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return array|PageRankNodeHistoryEntry[]
     */
    public function getEntries()
    {
        return $this->entries;
    }

    /**
     * @param PageRankNodeHistoryEntry $entry
     */
    public function addEntry(PageRankNodeHistoryEntry $entry){
        $this->entries[$entry->getNode()->getName()] = $entry;
    }

    public function toArray(){
        $arr = [];
        foreach($this->entries as $key => $val){
            $arr[$key] = ["node" => $val->getNode()->getName(), "oldPr" => $val->getOldPr(), "newPr" => $val->getNewPr()];
        }
        return $arr;
    }
}