#pagerank
[![Build Status](https://travis-ci.org/paslandau/pagerank.svg?branch=master)](https://travis-ci.org/paslandau/pagerank)

Calculating the PageRank of nodes in a linked graph

##Description
[todo]

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
    }

After installing, you need to require Composer's autoloader:
```php
require 'vendor/autoload.php';
```