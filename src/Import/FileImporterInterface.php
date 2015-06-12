<?php

namespace paslandau\PageRank\Import;


use paslandau\PageRank\Graph;

interface FileImporterInterface {

    /**
     * Imports the file specified by $pathToFile and creates a Graph from its content.
     * @param string $pathToFile
     * @return Graph
     */
    public function import($pathToFile);
}