<?php

use Interval\Interval;
use Interval\Intervals;

ini_set('display_errors', 1);
require_once __DIR__ . '/../vendor/autoload.php';

echo <<<text
[![Travis](https://img.shields.io/travis/Kirouane/interval/master.svg)](http://travis-ci.org/Kirouane/interval)
[![Coverage Status](https://coveralls.io/repos/github/Kirouane/interval/badge.svg)](https://coveralls.io/github/Kirouane/interval?branch=develop)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/783c18637e574894bc6a37e1c5c75e93)](https://www.codacy.com/app/Kirouane/interval?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Kirouane/interval&amp;utm_campaign=Badge_Grade)
[![Total Downloads](https://poser.pugx.org/kirouane/interval/downloads)](https://packagist.org/packages/kirouane/interval)
[![Latest Stable Version](https://poser.pugx.org/kirouane/interval/v/stable)](https://packagist.org/packages/kirouane/interval)

Interval
======

This library provides some tools to manipulate intervals. For instance, You can compute the union or intersection of two intervals.

Use cases
------
* Availabilities calculation.
* Scheduling/calendar/planning.
* Mathematics interval computation with open/closed boundaries
* etc

Features
------

* It computes some operations between two **intervals**: union, intersection and exclusion.
* It computes some operations between two **sets of intervals**: exclusion for now.
* It handles several types of boundaries : float, **\DateTime** and integer. 
* It handles **infinity** type as boundary.
* Ability to **combine** infinity with \DateTime and other types.
* filter, sort, map.
* Immutability.
* Chain operations.

Quality
-------

* Code coverage [![Coverage Status](https://coveralls.io/repos/github/Kirouane/interval/badge.svg)](https://coveralls.io/github/Kirouane/interval?branch=develop)
* Mutation test : Code coverage more than **90%**
* Takes care of **performance** and **memory usage**
* PSR1/PSR2, Code Smell


Install
------

`composer require kirouane/interval`


text;

echo "\n\n";

echo "Basic usage
---------\n\n";

echo "Let's assume an interval [20, 40].\n";
echo "We instantiate a new Interval object .\n\n";
$interval = Interval::create('[20,40]');
echo '```php
$interval = new Interval(20, 40);// ' . $interval . ';
```' . "\n\n";
echo "or\n\n";

echo '```php
$interval = Interval::create(\'[20,40]\');// ' . $interval . ';
```' . "\n";
echo "\n";
echo "\nWe can do some operations like : ";
echo "\n";
echo "* Intersection : \n\n";

echo '```php
echo $interval->intersect(new Interval(30, 60)); // ' . $interval->intersect(Interval::create('[30,60]')) . ';
```' . "\n";

echo "\n";
echo "* Union : \n\n";
echo '```php
echo $interval->union(new Interval(30, 60)); // ' . $interval->union(new Interval(30, 60)) . ';
```' . "\n\n";
echo "or\n\n";
echo '```php
echo $interval->union(new Interval(60, 100)); // ' . $interval->union(new Interval(60, 100)) . ';
```' . "\n";

echo "\n";
echo "* Exclusion : \n\n";
echo '```php
echo $interval->exclude(new Interval(30, 60)); // ' . $interval->exclude(new Interval(30, 60)) . ';
```' . "\n\n";
echo "or\n\n";
echo '```php
echo $interval->exclude(new Interval(30, 35)); // ' . $interval->exclude(new Interval(30, 35)) . ';
```' . "\n";
echo "\n";
echo 'We can compare two intervals as well: ';
echo "\n";
echo "* Overlapping test : \n\n";

echo '```php
echo $interval->overlaps(new Interval(30, 60)); // ' . ($interval->overlaps(new Interval(30, 60)) ? 'true' : 'false') . ';
```' . "\n";
echo "\n";
echo "* Inclusion test : \n\n";

echo '```php
echo $interval->includes(new Interval(30, 60)); // ' . ($interval->includes(new Interval(30, 60)) ? 'true' : 'false') . ';
```' . "\n";

echo "Use DateTimeInterface as boundary
---------\n\n";
$interval = new Interval(new \DateTime('2016-01-01'), new \DateTime('2016-01-10'));
echo '```php
$interval = new Interval(new \DateTime(\'2016-01-01\'), new \DateTime(\'2016-01-10\'));' . "\n" . '// ' . $interval . ';
```' . "\n";
echo "\n";
echo "* Union : \n\n";
echo '```php
echo $interval->union(Interval::create(\'[2016-01-10, 2016-01-15]\')); ' . "\n"  . '// ' . $interval->union(Interval::create('[2016-01-10, 2016-01-15]')) . ';
```' . "\n\n";

echo "Use Infinity as boundary
---------\n\n";
$interval = new Interval(-INF, INF);
echo '```php
$interval = new Interval(-INF, INF);// ' . $interval . ';
```' . "\n";
echo "\n";
echo "* Exclusion : \n\n";
echo '```php
echo $interval->exclude(Interval::create(\'[2016-01-10, 2016-01-15]\')); ' . "\n"  . '// ' . $interval->exclude(Interval::create('[2016-01-10, 2016-01-15]')) . ';
```' . "\n\n";

echo "Operations on sets (arrays) of intervals
---------\n\n";
$intervals = Intervals::create(['[0,5]', '[8,12]']);
echo '```php
$intervals = Intervals::create([\'[0,5]\', \'[8,12]\']);// ' . $intervals . ';
```' . "\n";
echo "\n";
echo "* Exclusion : \n\n";
echo '```php
echo $intervals->exclude(Intervals::create([\'[3,10]\'])); // ' . $intervals->exclude(Intervals::create(['[3,10]'])) . ';
```' . "\n\n";

echo "Chaining
---------\n\n";

$result = Interval
    ::create('[10, 20]')
    ->intersect(new Interval(11, 30))
    ->union(new Interval(15, INF))
    ->exclude(Intervals::create(['[18, 20]', '[25, 30]', '[32, 35]', '[12, 13]']))
    ->sort(function (Interval $first, Interval $second) {
        return $first->getStart()->getValue() <=> $second->getStart()->getValue();
    })
    ->map(function (Interval $interval) {
        return new Interval(
            $interval->getStart()->getValue() ** 2,
            $interval->getEnd()->getValue() ** 2
        );
    })
    ->filter(function (Interval $interval) {
        return $interval->getEnd()->getValue() > 170;
    });

echo '```php

$result = Interval
    ::create(\'[10, 20]\')
    ->intersect(new Interval(11, 30))
    ->union(new Interval(15, INF))
    ->exclude(Intervals::create([\'[18, 20]\', \'[25, 30]\', \'[32, 35]\', \'[12, 13]\']))
    ->sort(function (Interval $first, Interval $second) {
        return $first->getStart()->getValue() <=> $second->getStart()->getValue();
    })
    ->map(function (Interval $interval) {
        return new Interval(
            $interval->getStart()->getValue() ** 2,
            $interval->getEnd()->getValue() ** 2
        );
    })
    ->filter(function (Interval $interval) {
        return $interval->getEnd()->getValue() > 170;
    }); ' . "\n\n"  . '// ' .$result . ';
    
echo $result;    
```' . "\n\n";

echo 'Advanced usage
---------

You can create intervals with **open** boundaries : 

';

$result = Intervals
    ::create([']10, +INF]'])
    ->exclude(Intervals::create([']18, 20]', ']25, 30[', '[32, 35]', ']12, 13]']));

echo '```php

$result = Intervals
    ::create([\']10, +INF]\'])
    ->exclude(Intervals::create([\']18, 20]\', \']25, 30[\', \'[32, 35]\', \']12, 13]\']));

// ' . $result . "\n\n" . '
```' . "\n\n";

echo '
Contributing
----------------------

You are very welcomed to contribute to this Library!

* Clone
`git clone https://github.com/Kirouane/interval.git`

* Test
`vendor/bin/phpunit`   

* Build
`vendor/bin/grumphp run`

';
