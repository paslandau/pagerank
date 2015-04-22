<?php

namespace paslandau\PageRank;


class ResultFormatter {

    /**
     * @var int
     */
    private $precision;

    /**
     * @param int $precision
     */
    function __construct($precision)
    {
        $this->precision = $precision;
    }

    /**
     * @param PageRankResult $result
     * @return string
     */
    public function toString(PageRankResult $result)
    {
        $buf = [];
        $history = $result->getHistory();
        foreach ($history as $id => $historyElement) {
            $buf[] = ($id + 1) . ". Round";
            $buf[] = "Node\tOld PR\tNew PR\tDistance";
            foreach ($historyElement->getEntries() as $entry) {
                $oldPr = round($entry->getOldPr(), $this->precision);
                $newPr = round($entry->getNewPr(), $this->precision);
                $distance = round($entry->getOldPr() - $entry->getNewPr(), $this->precision);
                $buf[] = "{$entry->getNode()->getName()}\t\t{$oldPr}\t{$newPr}\t{$distance}";
            }
            $buf[] = "\n";
        }
        $str = implode("\n", $buf);
        return $str;
    }
}