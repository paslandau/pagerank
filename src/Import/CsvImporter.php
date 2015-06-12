<?php

namespace paslandau\PageRank\Import;

use paslandau\IOUtility\IOUtil;
use paslandau\PageRank\Edge;
use paslandau\PageRank\Graph;
use paslandau\PageRank\LoggerTrait;
use paslandau\PageRank\Node;

class CsvImporter implements FileImporterInterface{

    /**
     * @var bool|null
     */
    protected $hasHeader;

    /**
     * @var int
     */
    protected $sourceColumn;

    /**
     * @var int
     */
    protected $destinationColumn;

    /**
     * @var null|string
     */
    protected $encoding;

    /**
     * @var null|string
     */
    protected $delimiter;

    /**
     * @var null|string
     */
    protected $enclosure;

    /**
     * @var null|int
     */
    protected $offset;

    /**
     * @param null|bool $hasHeader [optional]. Default: null(false). Use true if the first line should be used as headline. On false, each line will have numeric keys starting at 0.
     * @param int $sourceColumn [optional]. Default: 0. The name of the column that contains the websites/URLs where a link originates. (If A links to B, this column contains A)
     * @param int $destinationColumn [optional]. Default: 1. The name of the column that contains the websites/URLs that are linkted to. (If A links to B, this column contains B)
     * @param null|string $encoding [optional]. Default: null(utf-8). Encoding of the file.
     * @param null|string $delimiter [optional]. Default: null(,). Delimiter used to seperate fields.
     * @param null|string $enclosure [optional]. Default: null("). Enclosure for fiels that contain special characters like the $delimiter or a new line.
     * @param null|int $offset [optional]. Default: null(0). Numeric offset (line number) where the csv file should start.
     */
    function __construct($hasHeader = null, $sourceColumn = null, $destinationColumn = null, $encoding = null, $delimiter = null, $enclosure = null, $offset = null)
    {
        if($sourceColumn === null){
            $sourceColumn = 0;
        }
        if($destinationColumn === null){
            $destinationColumn = 1;
        }
        $this->hasHeader = $hasHeader;
        $this->sourceColumn = $sourceColumn;
        $this->destinationColumn = $destinationColumn;
        $this->encoding = $encoding;
        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
        $this->offset = $offset;
    }


    /**
     * Imports the CSV file specified by $pathToFile
     * @param string $pathToFile
     * @return Graph
     */
    public function import($pathToFile){
        $lines = IOUtil::readCsvFile($pathToFile,$this->hasHeader,$this->encoding,$this->delimiter,$this->enclosure,null,null,$this->offset);
        $urls = [];
        $graph = new Graph();
        foreach($lines as $nr => $line){
            if(!array_key_exists($this->sourceColumn,$line) || !array_key_exists($this->destinationColumn,$line)){
                continue;
            }
            if(!parse_url($line[$this->sourceColumn]) || !parse_url($line[$this->destinationColumn]) ){
                continue;
            }
            $urlFrom = trim($line[$this->sourceColumn]);
            if(!array_key_exists($urlFrom,$urls)){
                $urls[$urlFrom] = new Node($urlFrom);
            }
            $urlTo = trim($line[$this->destinationColumn]);
            if(!array_key_exists($urlTo,$urls)){
                $urls[$urlTo] = new Node($urlTo);
            }
            $edge = new Edge($urls[$urlFrom],$urls[$urlTo]);
            $graph->addEdge($edge);
        }
        return $graph;
    }
}