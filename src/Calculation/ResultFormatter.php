<?php

namespace paslandau\PageRank\Calculation;


class ResultFormatter {

    /**
     * @var int
     */
    private $precision;

    /**
 * @var boolean
 */
    private $showOldPr;

    /**
     * @var boolean
     */
    private $showNewPr;

    /**
     * @var boolean
     */
    private $showDifference;

    /**
     * @var boolean
     */
    private $showLogScale;

    /**
     * @param int $precision [optional]. Default: 4. Number of decimals
     * @param bool $showOldPr [optional]. Default: true.
     * @param bool $showNewPr [optional]. Default: true.
     * @param bool $showDifference [optional]. Default: true.
     * @param bool $showLogScale [optional]. Default: false.
     */
    function __construct($precision = 4, $showOldPr = true, $showNewPr = true, $showDifference = true, $showLogScale = false)
    {
        $this->precision = $precision;
        $this->showOldPr = $showOldPr;
        $this->showNewPr = $showNewPr;
        $this->showDifference = $showDifference;
        $this->showLogScale = $showLogScale;
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
            $buf[] = implode("\t",$this->getHeaders());
            $rows = $this->getVals($historyElement);
            foreach($rows as $row){
                $buf[] = implode("\t",$row);
            }
            $buf[] = "\n";
        }
        $str = implode("\n", $buf);
        return $str;
    }

    /**
     * @return string[]
     */
    private function getHeaders(){
        $vals = ["Node"];
        if($this->showOldPr) {
            $vals[] = "OldPr";
        }
        if($this->showNewPr) {
            $vals[] = "NewPr";
        }
        if($this->showDifference) {
            $vals[] = "Difference";
        }
        if($this->showLogScale) {
            $vals[] = "LogScale";
        }
        return $vals;
    }

    /**
     * @param PageRankNodeHistory $historyElement
     * @return string[]
     */
    private function getVals(PageRankNodeHistory $historyElement){
        $logValues = $historyElement->getLogarithmicDistribution();
        $all = [];
        foreach ($historyElement->getEntries() as $key => $entry) {
            $vals = [];
            $vals[] = $entry->getNode()->getName();
            if($this->showOldPr) {
                $vals[] = round($entry->getOldPr(), $this->precision);
            }
            if($this->showNewPr) {
                $vals[] = round($entry->getNewPr(), $this->precision);
            }
            if($this->showDifference) {
                $vals[] = round($entry->getOldPr() - $entry->getNewPr(), $this->precision);
            }
            if($this->showLogScale) {
                $vals[] = round($logValues[$key], $this->precision);
            }
            $all[] = $vals;
        }
        return $all;
    }

    /**
     * Formats the $result as an HTML table.
     *
     * @param PageRankResult $result
     * @param string $cssClass [optional]. "": null. If not null, the rendered HTML looks like <table cellspacing='2' cellpadding='5' border='1' class="$cssClass">;
     * @return string
     */
    public function toHtmlTable(PageRankResult $result, $cssClass = null){
        if($cssClass !== null){
            $cssClass = " class='".htmlentities($cssClass)."'";
        }else{
            $cssClass = "";
        }
        $history = $result->getHistory();
        $buf = [];
        foreach ($history as $id => $historyElement) {
            $buf[] = "<table cellspacing='2' cellpadding='5' border='1'{$cssClass}>";
            $buf[] = "<tr><th>".implode("</th><th>",$this->getHeaders())."</th></tr>\n";
            $rows = $this->getVals($historyElement);
            foreach($rows as $row){
                $buf[] = "<tr><td>".implode("</td><td>",$row)."</td></tr>\n";
            }
            $buf[] = "</table>\n";
        }
        $str = implode("\n", $buf);
        return $str;
    }
}