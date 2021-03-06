<?php

use paslandau\PageRank\Calculation\PageRank;
use paslandau\PageRank\Calculation\ResultFormatter;
use paslandau\PageRank\Import\CsvImporter;

require_once __DIR__."/bootstrap.php";

$csvImporter = new CsvImporter(false,0,1,"utf-8",",");
$pathToFile = __DIR__."/resources/wiki-input.csv";

$graph = $csvImporter->import($pathToFile);

$pageRank = new PageRank();

/**
 * Keep PageRank info for each round of calculation
 */
$keepHistory = true;
$result = $pageRank->calculatePagerank($graph, $keepHistory);

$formatter = new ResultFormatter(4);
echo $formatter->toString($result);