<?php

namespace paslandau\PageRank;


use League\Csv\Reader;
use League\Csv\Writer;
use paslandau\IOUtility\EncodingStreamFilter;
use paslandau\IOUtility\IOUtil;

class CsvIO {

    public function import($pathToFile, $sourceColumn = 1, $destinationColumn = 2, $encoding = "utf-8", $delimiter = ",", $enclosure = "\""){
        EncodingStreamFilter::register();
        $reader = Reader::createFromPath($pathToFile);
        if($encoding !== null) {
            $reader->prependStreamFilter(EncodingStreamFilter::getFilterWithParameters($encoding));
        }
        $reader->setDelimiter($delimiter);
        $reader->setEnclosure($enclosure);
        $urls = [];
        $graph = new Graph();
        foreach($reader as $line){
            if(!array_key_exists($sourceColumn,$line) || !array_key_exists($destinationColumn,$line)){
                continue;
            }
            if(!parse_url($line[$sourceColumn]) || !parse_url($line[$destinationColumn]) ){
                continue;
            }
            $urlFrom = trim($line[$sourceColumn]);
            if(!array_key_exists($urlFrom,$urls)){
                $urls[$urlFrom] = new Node($urlFrom);
            }
            $urlTo = trim($line[$destinationColumn]);
            if(!array_key_exists($urlTo,$urls)){
                $urls[$urlTo] = new Node($urlTo);
            }
            $edge = new Edge($urls[$urlFrom],$urls[$urlTo]);
            $graph->addEdge($edge);
        }
        return $graph;
    }

    public function export($pathToFile, array $round, $encoding = "utf-8", $delimiter = ",", $enclosure = "\""){
        IOUtil::writeCsvFile($pathToFile, $round, true, $encoding, $delimiter, $enclosure);
    }
}