<?php

use paslandau\PageRank\Calculation\PageRank;
use paslandau\PageRank\Calculation\ResultFormatter;
use paslandau\PageRank\Edge;
use paslandau\PageRank\Graph;
use paslandau\PageRank\Node;

require_once __DIR__."/bootstrap.php";

// define the nodes
$a = new Node("a");
$b = new Node("b");
$c = new Node("c");
$d = new Node("d");
$e = new Node("e");
$f = new Node("f");
$x1 = new Node("x1");
$x2 = new Node("x2");
$x3 = new Node("x3");
$x4 = new Node("x4");
$x5 = new Node("x5");

$graph = new Graph();
// uncomment to get the results of the german example http://de.wikipedia.org/wiki/PageRank
// leave commented for the results of the english one http://en.wikipedia.org/wiki/PageRank
//$graph->addEdge(new Edge($a,$a));
//$graph->addEdge(new Edge($a,$b));
//$graph->addEdge(new Edge($a,$c));
//$graph->addEdge(new Edge($a,$d));
//$graph->addEdge(new Edge($a,$e));
//$graph->addEdge(new Edge($a,$f));
//$graph->addEdge(new Edge($a,$x1));
//$graph->addEdge(new Edge($a,$x2));
//$graph->addEdge(new Edge($a,$x3));
//$graph->addEdge(new Edge($a,$x4));
//$graph->addEdge(new Edge($a,$x5));

// define the links between the nodes
$graph->addEdge(new Edge($b, $c));

$graph->addEdge(new Edge($c, $b));

$graph->addEdge(new Edge($d, $a));
$graph->addEdge(new Edge($d, $b));

$graph->addEdge(new Edge($e, $b));
$graph->addEdge(new Edge($e, $d));
$graph->addEdge(new Edge($e, $f));

$graph->addEdge(new Edge($f, $b));
$graph->addEdge(new Edge($f, $e));

$graph->addEdge(new Edge($x1, $b));
$graph->addEdge(new Edge($x1, $e));
$graph->addEdge(new Edge($x2, $b));
$graph->addEdge(new Edge($x2, $e));
$graph->addEdge(new Edge($x3, $b));
$graph->addEdge(new Edge($x3, $e));
$graph->addEdge(new Edge($x4, $e));
$graph->addEdge(new Edge($x5, $e));

// calculate the PageRank
$pageRank = new PageRank();
$result = $pageRank->calculatePagerank($graph);

// print the result
$formatter = new ResultFormatter(4);
echo $formatter->toString($result);