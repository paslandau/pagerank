<?php

namespace paslandau\PageRank\Import;


class ScreamingFrogCsvImporter extends CsvImporter{

    /**
     * Creates an importer specific to the output of Screaming Frog
     */
    function __construct()
    {
        parent::__construct(false,1,2,"utf-8",",",null,2);
    }
}