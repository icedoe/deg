HTML Table builder
===================

Installation
----------------------
Package needs to be installed within a working Anax-MVC installation.

This package is posted on Packagist as deg/table.

To install using composer, add this to your composer.json.

```
{
    "require": {
        "deg/table": "dev-master"
    }
}
```

Install with commands:

```
composer validate
composer install --no-dev
```

If all's well, you'll find the package in your vendor directory.

Usage
------------
Begin by copy-pasting content of folders webroot and view to the corresponding Anax-MVC folders

Point browser to webroot/tableTest.php where you'll find a walkthrough of available functions.

Order of function calls is quite inflexible, and most attempts to alter the one suggested in the walkthrough will haunt and annoy you with cruel and unusual exceptions.

[![Build Status](https://travis-ci.org/icedoe/deg.svg?branch=test)](https://travis-ci.org/icedoe/deg)[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/icedoe/deg/badges/quality-score.png?b=test)](https://scrutinizer-ci.com/g/icedoe/deg/?branch=test)[![Code Coverage](https://scrutinizer-ci.com/g/icedoe/deg/badges/coverage.png?b=test)](https://scrutinizer-ci.com/g/icedoe/deg/?branch=test)
