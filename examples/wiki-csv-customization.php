<?php

use paslandau\PageRank\Calculation\PageRank;
use paslandau\PageRank\Calculation\ResultFormatter;
use paslandau\PageRank\Import\CsvImporter;

require_once __DIR__."/bootstrap.php";

$csvImporter = new CsvImporter(false,0,1,"utf-8",",");
$pathToFile = __DIR__."/resources/wiki-input.csv";

$graph = $csvImporter->import($pathToFile);

$dampingFactor = 0.7;   // change damping factor to 0.7 instead of the default 0.85
$maxRounds = 100;       // keep the maximum number of calculation rounds
$maxDistance = 0.0001;  // and the the maximum distance before termination low for faster runtimes
$collapseLinks = false; // do not collapse links, e.g. multiple links from page A to page B will _not_ be seen as only 1 link
$pageRank = new PageRank($dampingFactor,$maxRounds,$maxDistance,$collapseLinks);

$result = $pageRank->calculatePagerank($graph);

$formatter = new ResultFormatter(4);
echo $formatter->toString($result);