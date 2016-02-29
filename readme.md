#pagerank
[![Build Status](https://travis-ci.org/paslandau/pagerank.svg?branch=master)](https://travis-ci.org/paslandau/pagerank)

Calculating the PageRank of nodes in a directed graph.

##Description
A PHP implementation of the PageRank algorithm described in [The PageRank Citation Ranking: Bringing Order to the Web](http://ilpubs.stanford.edu:8090/422/1/1999-66.pdf) (PDF).
The project was created to support [my](http://www.afs-akademie.org/akademie/referenten/#landau) session "[PageRank und TrustRank](http://www.afs-akademie.org/akademie/lehrinhalte/)" 
at the [AFS - Akademie für Fortbildung in SEO](http://www.afs-akademie.org/) and gives some basic examples as well as 
something to get interested participants started quickly.

The PageRank itself is an indicator of the importance of a node in a linked graph. The basic idea behind the algorithm is as follows:

- an edge ("link") between two nodes in a (directed) graph can be regarded as an endorsement from the originating node to the target node
- hence, the more nodes link to "me" the more important "I" am
- moreover, the more important "I" am, the more weight carries my endorsement

In this implementation an iterative approach is used to calculate the PageRank (PR) (source: [Wikipedia](http://en.wikipedia.org/wiki/PageRank#Iterative)):

[![PageRank formula](http://upload.wikimedia.org/math/2/a/9/2a9163d33fbc66c8bd74ebd7c64d0de3.png)](http://en.wikipedia.org/wiki/PageRank#Iterative)

where p_1, p_2, ..., p_N are the pages under consideration, d is a damping factor to avoid "rank sinks", M(p_i) is the set of pages that link to p_i, L(p_j) is the number of outbound links on page p_j, and N is the total number of pages.
The PR values change on every iteration step until a predefined threshold for the difference between old and new PR value is reached.

Probably the most important application of the PageRank algorithm is being a part of the ranking algorithm of (web) search engines. The PageRank is commonly known as
the most important piece that set Google apart from other search engines back in 1998. See [The Anatomy of a Large-Scale Hypertextual Web Search Engine](http://infolab.stanford.edu/~backrub/google.html)
for reference.

##Basic Usage

```php

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

// define the links between the nodes
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

// calculate the PageRank
$pageRank = new PageRank();
$result = $pageRank->calculatePagerank($graph);

// print the result
$formatter = new ResultFormatter(4);
echo $formatter->toString($result);

```

**Output**

```
93. Round
Node	OldPr	NewPr	Difference
b	    0.3242	0.3242	-0
c	    0.2892	0.2892	0
d	    0.033	0.033	0
a	    0.0276	0.0276	0
e	    0.0682	0.0682	0
f	    0.033	0.033	0
x1	    0.0136	0.0136	0
x2	    0.0136	0.0136	0
x3	    0.0136	0.0136	0
x4	    0.0136	0.0136	0
x5	    0.0136	0.0136	0
```
     
The `Graph` in the example corresponds to the following graphic, taken from the [Wikipedia article on PageRank](http://en.wikipedia.org/wiki/PageRank):

[![PageRank example graph](http://upload.wikimedia.org/wikipedia/commons/thumb/f/fb/PageRanks-Example.svg/758px-PageRanks-Example.svg.png)](http://en.wikipedia.org/wiki/PageRank#/media/File:PageRanks-Example.svg)

###Examples

See `example` folder.

##Requirements

- PHP >= 5.5

##Installation

The recommended way to install pagerank is through [Composer](http://getcomposer.org/).

    curl -sS https://getcomposer.org/installer | php

Next, update your project's composer.json file to include pagerank:

    {
        "repositories": [ { "type": "composer", "url": "http://packages.myseosolution.de/"} ],
        "minimum-stability": "dev",
        "require": {
             "paslandau/pagerank": "dev-master"
        }
        "config": {
            "secure-http": false
        }
    }

_**Caution:** You need to explicitly set `"secure-http": false` in order to access http://packages.myseosolution.de/ as repository. 
This change is required because composer changed the default setting for `secure-http` to true at [the end of february 2016](https://github.com/composer/composer/commit/cb59cf0c85e5b4a4a4d5c6e00f827ac830b54c70#diff-c26d84d5bc3eed1fec6a015a8fc0e0a7L55)._


After installing, you need to require Composer's autoloader:

```php
require 'vendor/autoload.php';
```

#General workflow and customization options

Firstly, we need to define a set of connected `Node`s that form a `Graph`. Usually, that data will be obtained from a web crawl that saves the 
outgoing links of each crawled web page e.g. as CSV file. But for now let's assume we're building the `Graph` manually from scratch:

```php
// define two Nodes
$a = new Node("a");
$b = new Node("b");

// create the Graph object
$graph = new Graph();

// connect the two nodes by linking ("creating an Edge) from $a to $b
$graph->addEdge(new Edge($a, $b));
```

In this scenario, `Node` `$a` links to `Node` `$b`. Next, we need an instance of the `PageRank` class to perform the PageRank calculation on the `Graph` object:

```php
// create the PageRank object
// providing only null values yields the default settings (see doc comments of the constructor in the PageRank class)
$dampingFactor = null;
$maxRounds = null;
$maxDistance = null;
$collapseLinks = null;
$pageRank = new PageRank($dampingFactor,$maxRounds,$maxDistance,$collapseLinks);

// calculate the PageRank
$keepAllRoundData = null;
$result = $pageRank->calculatePagerank($graph, $keepAllRoundData);
```

The different customization options will be explained subsequently. 

`PageRank::calculatePagerank()` returns an object of type `PageRankResult` that holds a reference to the 
original `Graph` object as well as an array of `PageRankNode`s. A `PageRankNode` has a reference to the original `Node` object as well as a value for it's current PageRank and
it's PageRank in the former round of calculation. Why is that?

This implementation calculates the PageRank iteratively. In each iteration, the PageRank values will vary - heavily in the beginning, less and less towards the end. 
This becomes clear once you realize that the PageRank of page B depends on the PageRank of page A (assuming page A links to page B) *but* the PageRank of page A itself relies on
other pages linking to A. In fact it's not uncommon that one of _those pages linking to A_ will be linked to from page B - creating a nice little circular interdepency between those pages.
To solve this problem, we'll fixate the _current_ PageRank of each node during an interation step and use those fixated values as base for the calculation. Once we finished the calculation 
for all nodes, we can set those "new" values as the _current_ PageRank and start the next iteration. The difference between "old" and "new" PageRank will decrease over time (i.e. number of iterations steps)
and the calculation terminates once a certain threshold is reached. So basically:

```php
    /* Pseudocode. Well, kinda.. */
    
    $round = 1;
    do{
        $newPrs = [];
        // first, calculate the PR for all nodes
        foreach($nodes as $key => $node){
            $newPr = $node->calculatePagerank(); // get linking nodes and their current PR values and calculate the PR
            $newPrs[$key] = $newPr; // cache the new PR
        }
        // second
        foreach($nodes as $key => $node){
            $node->setOldPr($node->getCurrentPr()); // set current PR as "old"
            $node->setCurrentPr($newPrs[$key]); // get newly calculated PR and set as "current"
        } 
        // yey, next round
        $round++;
    }while($difference > $threshold);
```

To access the final PageRank values of each original node, use the `PageRankResult::getNodes()` method like so:

```php
    $pagerankNodes = $result->getNodes();
    foreach($pagerankNodes as $node){
        echo $node->getName()." has a final PageRank of ".$node->getNewPr()."\n";
    }
```

##Customization options
There is a number of options to calibrate the PageRank calculation.

###Damping factor
The damping factor is a value between 0 and 1 and is used to avoid the accumulation of PageRank in so called "rank sinks". Such a rank sink emerges when two nodes link to each other in a circular manner. 
In the original paper, a value of `0.85` is proposed.

```php
// set on object instantiation...
$dampingFactor = 0.55;
$pageRank = new PageRank($dampingFactor);

// or via setter
$pageRank->setDampingFactor($dampingFactor);
```

###Limit runtime
Usually the PageRank calculation terminates as soon as the difference between old and new PageRank values of two subsequent iterations exceeds a certain threshold.
But you can also choose to let the calculation run for a fixed number of iterations. Whatever limit is reached first terminates the calculation.

```php
// set on object instantiation...
$maxRounds = 100;
$maxDistance = 0.00001;
$pageRank = new PageRank(null,$maxRounds,$maxDistance);

// or via setter
$pageRank->setMaxRounds($maxRounds);
$pageRank->setMaxDistance($maxDistance);
```

###Collapse links
To my current knowledge there is no clear statement on how to handle multiple identical links (e.g. web page A has three outgoing links to web page B).
Since usually the term *set* is used when talking about the incoming links, I tend to think that mutiple identical links should be considered as only one link.
But one might also argue (from a web perspektive) that multiple links to the same page increase the likelyhood of at least one of those links to get followed.

So I decided to leave that decision to the user by providing a `$collapseLinks` flag. If `true`, multiple links to the same `Node` will only count as 1. Otherwise (on `false`; the default), 
multiple links to the same page are not treated differently than "ordinary" links.

```php
// set on object instantiation...
$collapseLinks = true;
$pageRank = new PageRank(null,null,null,$collapseLinks);

// or via setter
$pageRank->setCollapseLinks($collapseLinks);
```

##Keeping historic calculation data
As mentioned in the introduction, this project was created with an educational purpose in mind: Give the audience a better understanding of the concept of PageRank 
and the way it is calculated iteratively. So it was important to me to visualize the changing PageRank values after each iteration. To capture those temporary results,
the `PageRank::calculatePagerank()` method takes the second argument `$keepAllRoundData`. If `$keepAllRoundData` is `true` (default is `false`), the `PageRankResult` will
have an array of `PageRankHistory` objects (one for each round; otherwise only the final round is stored).

Each `PageRankHistory` object will have an array of `PageRankHistoryEntry`s, whereas each `PageRankHistoryEntry` has a reference to the corresponding `PageRankNode` as well as the old and new PageRank values
of that iteration. Example:

```php
// create the PageRank object
$pageRank = new PageRank();

// calculate the PageRank
$keepAllRoundData = true; // keep history
$result = $pageRank->calculatePagerank($graph, $keepAllRoundData);

// get the history
$history = $result->getHistory();

//iterate over all histories
foreach($history as $h){
    //iterate over each entry 
    echo "Round $h->getId()."\n";
    foreach($h->getEntries() as $entry){
        echo "Node {$entry->getNode()->getName()} had an old PageRank (before the calculation in this iteration) of {$entry->getOldPr()} and {$entry->getNewPr()} afterwards."\n";
    }
}
```

##Import link data from CSV
It is cumbersome to define a graph manually. Using a CSV format (one column contains the originating node, another one the target node) feels more natural. There are two
importers ready to go: `CsvImporter` - a generic importer for CSV files and `ScreamingFrogCsvImporter` - a subclass of `CsvImporter` that is adjusted to the output format of the 
desktop based crawling software [ScreamingFrog SEO Spider Tool](http://www.screamingfrog.co.uk/seo-spider/).

**Example generic CSV**
```
linkFrom,linkTo
example.com, example.com/foo
example.com, example.com/bar
```

**Usage**
```php
$hasHeader = true;
$sourceColumn = "linkFrom"; 
$destinationColumn = "linkTo";
$encoding = "utf-8";
$delimiter = ",";
$csvImporter = new CsvImporter($hasHeader,$sourceColumn,$destinationColumn,$encoding,$delimiter);
$pathToFile = "...";

$graph = $csvImporter->import($pathToFile);
```

**Example Screaming Frog CSV**
```
"All Inlinks"
"Type","Source","Destination","Alt Text","Anchor","Status Code","Status","Follow"
"HREF","example.com","example.com/foo","","Home","200","OK","true"
"HREF","example.com","example.com/bar","","Home","200","OK","true"
```

**Usage**
```php
$csvImporter = new ScreamingFrogCsvImporter();
$pathToFile = "...";

$graph = $csvImporter->import($pathToFile);
```

#Similar projects
I could not find a dedicated PHP project of a PageRank implemenation, but the algorithm is part of some other repos like:

- [DieguiNho's Playground](https://github.com/dfserrano/playground) (see [Pagerank.php](https://github.com/dfserrano/playground/blob/master/snaphp/algorithms/ranking/Pagerank.php))
- [tcz/hadoop-pagerank-php](https://github.com/tcz/hadoop-pagerank-php)
- [TextRank](https://github.com/crodas/textrank-old) (see [Pagerank.php](https://github.com/crodas/textrank-old/blob/master/lib/PageRank.php))
 
#Frequently searched questions and phrases around PageRank
- How does the PageRank algorithm work?
- Open source implementation of the PageRank algorithm
- Calculating the PageRank with PHP
- iterative PageRank implementation in PHP
- example of a PageRank calculation