<?php

namespace paslandau\PageRank\Calculation;

/**
 * History information for a round in a PageRank calculation.
 * The PageRankNodeHistory stores an array of PageRankNodeHistoryEntry s
 * @package paslandau\PageRank
 */
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
     * Sorts the entries by newPr
     * @param string $order [ASC or DESC]
     */
    public function sortEntries($order = "DESC"){
        if($order === "DESC") {
            $sortFn = function (PageRankNodeHistoryEntry $a, PageRankNodeHistoryEntry $b) {
                return $a->getNewPr() < $b->getNewPr();
            };
        }else{
            $sortFn = function (PageRankNodeHistoryEntry $a, PageRankNodeHistoryEntry $b) {
                return $a->getNewPr() > $b->getNewPr();
            };
        }
        uasort($this->entries,$sortFn);
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

    /**
     * @return array [
     *  ["node" => $val->getNode()->getName(), "oldPr" => $val->getOldPr(), "newPr" => $val->getNewPr()]
     * ]
     */
    public function toArray(){
        $arr = [];
        foreach($this->entries as $key => $val){
            $arr[$key] = ["node" => $val->getNode()->getName(), "oldPr" => $val->getOldPr(), "newPr" => $val->getNewPr()];
        }
        return $arr;
    }

    /**
     * Scales the PR values logarithmically to fit between 0 an 10;
     * @return float[]
     */
    public function getLogarithmicDistribution(){
//        http://math.stackexchange.com/a/354879
        $max = 0;
        $min = null;
        foreach($this->entries as $entry){
            if($entry->getNewPr() > $max){
                $max = $entry->getNewPr();
            }
            if($min === null || $min > $entry->getNewPr()){
                $min = $entry->getNewPr();
            }
        }
        $maxScaled = 10;
        $a = 1;
        if($min > 0){
            $a = $max/$min;
        }
        $resFn = function($i) use ($a){
            $log = $a*log(1+$i);
            $res = $log/($a*(1+$log));
            return $res;
        };
        $resMax = $resFn($max);
        $result = [];
        foreach($this->entries as $key => $entry){
            $i = $entry->getNewPr();
            $res = $resFn($i);
            $scaled = $res/$resMax * $maxScaled;
//            $rounded = round($scaled,2);
            $result[$key] = $scaled;
        }
        return $result;
    }
}