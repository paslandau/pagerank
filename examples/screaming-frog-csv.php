<?php

use paslandau\IOUtility\IOUtil;
use paslandau\PageRank\Calculation\PageRank;
use paslandau\PageRank\Calculation\ResultFormatter;
use paslandau\PageRank\Import\CsvImporter;
use paslandau\PageRank\Import\ScreamingFrogCsvImporter;

require_once __DIR__."/bootstrap.php";

$csvImporter = new ScreamingFrogCsvImporter();
$pathToFile = __DIR__."/resources/screaming-frog.csv";

$graph = $csvImporter->import($pathToFile);

$pageRank = new PageRank();
$result = $pageRank->calculatePagerank($graph);

$formatter = new ResultFormatter(4);
echo $formatter->toString($result);

//export result to CSV
$pathToExportFolder = __DIR__."/export";
IOUtil::createDirectoryIfNotExists($pathToExportFolder);
$pathToExportFile = IOUtil::combinePaths($pathToExportFolder,"screaming-frog-result.csv");

$finalResult = $result->getLastHistoryEntry();
$rows = $finalResult->toArray();
IOUtil::writeCsvFile($pathToExportFile,$rows,true,"utf-8",",");
echo "Exported the result of the PageRank calculation to $pathToExportFile.";