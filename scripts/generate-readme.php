<?php

use Interval\Interval;
use Interval\Intervals;

ini_set('display_errors', 1);
require_once __DIR__ . '/../vendor/autoload.php';

echo <<<text
[![Travis](https://img.shields.io/travis/Kirouane/interval/master.svg)](http://travis-ci.org/Kirouane/interval)
[![Coverage Status](https://coveralls.io/repos/github/Kirouane/interval/badge.svg)](https://coveralls.io/github/Kirouane/interval)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/783c18637e574894bc6a37e1c5c75e93)](https://www.codacy.com/app/Kirouane/interval?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Kirouane/interval&amp;utm_campaign=Badge_Grade)
[![Installs](https://img.shields.io/packagist/dt/Kirouane/interval.svg)](https://packagist.org/packages/Kirouane/interval/stats)
[![Packagist](https://img.shields.io/packagist/v/Kirouane/interval.svg)](https://packagist.org/packages/Kirouane/interval)

Interval
======

This library provides some tools to manipulate intervals. For instance, You can compute the union or intersection of two intervals.

Features
------

* It computes some operations between two **intervals**: union, intersection and exclusion.
* It computes some operations between two **sets of intervals**: exclusion for now.
* It handles several types of boundary (endpoints) : float, **\DateTime**, integer, and string. 
* It handles **infinity** boundaries.
* Ability to **combine** infinity with \DateTime and other types.
* Immutability.
* Chaining operations.

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
$interval = new Interval(20, 40)// ' . $interval . ';
```' . "\n\n";
echo "or\n\n";

echo '```php
$interval = Interval::create(\'[20,40]\')// ' . $interval . ';
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

echo "Use DateTimeInterface as endpoints
---------\n\n";
$interval = new Interval(new \DateTime('2016-01-01'), new \DateTime('2016-01-10'));
echo '```php
$interval = new Interval(new \DateTime(\'2016-01-01\'), new \DateTime(\'2016-01-10\'))// ' . $interval . ';
```' . "\n";
echo "\n";
echo "* Union : \n\n";
echo '```php
echo $interval->union(Interval::create(\'[2016-01-10, 2016-01-15]\')); // ' . $interval->union(Interval::create('[2016-01-10, 2016-01-15]')) . ';
```' . "\n\n";

echo "Use Infinity as endpoints
---------\n\n";
$interval = new Interval(-INF, INF);
echo '```php
$interval = new Interval(-INF, INF);// ' . $interval . ';
```' . "\n";
echo "\n";
echo "* Exclusion : \n\n";
echo '```php
echo $interval->exclude(Interval::create(\'[2016-01-10, 2016-01-15]\')); // ' . $interval->exclude(Interval::create('[2016-01-10, 2016-01-15]')) . ';
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
