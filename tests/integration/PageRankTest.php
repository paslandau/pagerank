<?php

use paslandau\PageRank\Calculation\PageRank;
use paslandau\PageRank\Edge;
use paslandau\PageRank\Graph;
use paslandau\PageRank\Node;

class PageRankTest extends PHPUnit_Framework_TestCase
{

    /**
     * See example image at http://en.wikipedia.org/wiki/PageRank
     * >> http://en.wikipedia.org/wiki/PageRank#/media/File:PageRanks-Example.svg
     */
    public function test_ShouldYieldSameResultsAsTheEnWikiExample()
    {
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
        $graph->addEdge(new Edge($a, $a));
        $graph->addEdge(new Edge($a, $b));
        $graph->addEdge(new Edge($a, $c));
        $graph->addEdge(new Edge($a, $d));
        $graph->addEdge(new Edge($a, $e));
        $graph->addEdge(new Edge($a, $f));
        $graph->addEdge(new Edge($a, $x1));
        $graph->addEdge(new Edge($a, $x2));
        $graph->addEdge(new Edge($a, $x3));
        $graph->addEdge(new Edge($a, $x4));
        $graph->addEdge(new Edge($a, $x5));

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

        $damping = 0.85;
        $maxRounds = 1000;
        $maxDistance = 0.000001;
        $pageRank = new PageRank($damping, $maxRounds, $maxDistance);
        $result = $pageRank->calculatePagerank($graph);
        $history = $result->getHistory();
        $actual = [];
        foreach ($history as $key => $historyElement) {
            $actual[$key] = $historyElement->toArray();
            $round = $actual[$key];
            foreach ($round as $node => $nodeValue) {
                $actual[$key][$node]["oldPr"] = round($nodeValue["oldPr"], 3);
                $actual[$key][$node]["newPr"] = round($nodeValue["newPr"], 3);
            }
        }
        $expected = [92 => [
            "a" => ["node" => "a", "oldPr" => 0.033, "newPr" => 0.033],
            "b" => ["node" => "b", "oldPr" => 0.384, "newPr" => 0.384],
            "c" => ["node" => "c", "oldPr" => 0.343, "newPr" => 0.343],
            "d" => ["node" => "d", "oldPr" => 0.039, "newPr" => 0.039],
            "e" => ["node" => "e", "oldPr" => 0.081, "newPr" => 0.081],
            "f" => ["node" => "f", "oldPr" => 0.039, "newPr" => 0.039],
            "x1" => ["node" => "x1", "oldPr" => 0.016, "newPr" => 0.016],
            "x2" => ["node" => "x2", "oldPr" => 0.016, "newPr" => 0.016],
            "x3" => ["node" => "x3", "oldPr" => 0.016, "newPr" => 0.016],
            "x4" => ["node" => "x4", "oldPr" => 0.016, "newPr" => 0.016],
            "x5" => ["node" => "x5", "oldPr" => 0.016, "newPr" => 0.016],
        ]
        ];
        $this->assertEquals($actual, $expected);
    }

    /**
     * See example image at http://de.wikipedia.org/wiki/PageRank
     * >> http://de.wikipedia.org/wiki/PageRank#/media/File:PageRank-Beispiel.png
     * Difference to the englisch example: No normalization is used - that means PageRankNode "a" has no outlinks at all instead if links to any other node including itself.
     */
    public function test_ShouldYieldSameResultsAsTheDeWikiExample()
    {
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

        $damping = 0.85;
        $maxRounds = 1000;
        $maxDistance = 0.000001;
        $pageRank = new PageRank($damping, $maxRounds, $maxDistance);
        $result = $pageRank->calculatePagerank($graph);
        $history = $result->getHistory();
        $actual = [];
        foreach ($history as $key => $historyElement) {
            $actual[$key] = $historyElement->toArray();
            $round = $actual[$key];
            foreach ($round as $node => $nodeValue) {
                $actual[$key][$node]["oldPr"] = round($nodeValue["oldPr"], 4);
                $actual[$key][$node]["newPr"] = round($nodeValue["newPr"], 4);
            }
        }
        $expected = [92 => [
            "b" => ["node" => "b", "oldPr" => 0.3242, "newPr" => 0.3242],
            "c" => ["node" => "c", "oldPr" => 0.2892, "newPr" => 0.2892],
            "d" => ["node" => "d", "oldPr" => 0.033, "newPr" => 0.033],
            "a" => ["node" => "a", "oldPr" => 0.0276, "newPr" => 0.0276],
            "e" => ["node" => "e", "oldPr" => 0.0682, "newPr" => 0.0682],
            "f" => ["node" => "f", "oldPr" => 0.033, "newPr" => 0.033],
            "x1" => ["node" => "x1", "oldPr" => 0.0136, "newPr" => 0.0136],
            "x2" => ["node" => "x2", "oldPr" => 0.0136, "newPr" => 0.0136],
            "x3" => ["node" => "x3", "oldPr" => 0.0136, "newPr" => 0.0136],
            "x4" => ["node" => "x4", "oldPr" => 0.0136, "newPr" => 0.0136],
            "x5" => ["node" => "x5", "oldPr" => 0.0136, "newPr" => 0.0136],
        ]
        ];
        $this->assertEquals($actual, $expected);
    }
}
