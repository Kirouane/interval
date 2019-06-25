[![Travis](https://img.shields.io/travis/Kirouane/interval/master.svg)](http://travis-ci.org/Kirouane/interval)
[![Coverage Status](https://coveralls.io/repos/github/Kirouane/interval/badge.svg)](https://coveralls.io/github/Kirouane/interval?branch=develop)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/783c18637e574894bc6a37e1c5c75e93)](https://www.codacy.com/app/Kirouane/interval?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Kirouane/interval&amp;utm_campaign=Badge_Grade)
[![Total Downloads](https://poser.pugx.org/kirouane/interval/downloads)](https://packagist.org/packages/kirouane/interval)
[![Latest Stable Version](https://poser.pugx.org/kirouane/interval/v/stable)](https://packagist.org/packages/kirouane/interval)

Interval
======

This library provides some tools to handle intervals. For instance, you can compute the union or intersection of two intervals.

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



Basic usage
---------

Let's assume an interval [20, 40].
We instantiate a new Interval object .

```php
$interval = new Interval(20, 40);// [20, 40];
```

or

```php
$interval = Interval::create('[20,40]');// [20, 40];
```


We can do some operations like : 
* Intersection : 

```php
echo $interval->intersect(new Interval(30, 60)); // [30, 40];
```

* Union : 

```php
echo $interval->union(new Interval(30, 60)); // {[20, 60]};
```

or

```php
echo $interval->union(new Interval(60, 100)); // {[20, 40], [60, 100]};
```

* Exclusion : 

```php
echo $interval->exclude(new Interval(30, 60)); // {[20, 30[};
```

or

```php
echo $interval->exclude(new Interval(30, 35)); // {[20, 30[, ]35, 40]};
```

We can compare two intervals as well: 
* Overlapping test : 

```php
echo $interval->overlaps(new Interval(30, 60)); // true;
```

* Inclusion test : 

```php
echo $interval->includes(new Interval(30, 60)); // false;
```
Use DateTimeInterface as boundary
---------

```php
$interval = new Interval(new \DateTime('2016-01-01'), new \DateTime('2016-01-10'));
// [2016-01-01T00:00:00+01:00, 2016-01-10T00:00:00+01:00];
```

* Union : 

```php
echo $interval->union(Interval::create('[2016-01-10, 2016-01-15]')); 
// {[2016-01-01T00:00:00+01:00, 2016-01-15T00:00:00+01:00]};
```

Use Infinity as boundary
---------

```php
$interval = new Interval(-INF, INF);// ]-∞, +∞[;
```

* Exclusion : 

```php
echo $interval->exclude(Interval::create('[2016-01-10, 2016-01-15]')); 
// {]-∞, 2016-01-10T00:00:00+01:00[, ]2016-01-15T00:00:00+01:00, +∞[};
```

Operations on sets (arrays) of intervals
---------

```php
$intervals = Intervals::create(['[0,5]', '[8,12]']);// {[0, 5], [8, 12]};
```

* Exclusion : 

```php
echo $intervals->exclude(Intervals::create(['[3,10]'])); // {[0, 3[, ]10, 12]};
```

Chaining
---------

```php

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

// {[169, 324], [400, 625], [900, 1024], [1225, +∞[};
    
echo $result;    
```

Advanced usage
---------

You can create intervals with **open** boundaries : 

```php

$result = Intervals
    ::create([']10, +INF['])
    ->exclude(Intervals::create([']18, 20]', ']25, 30[', '[32, 35]', ']12, 13]']));

// {]10, 12], ]13, 18], ]20, 25], [30, 32[, ]35, +∞[}


```



Contributing
----------------------

You are very welcomed to contribute to this Library!

* Clone
`git clone https://github.com/Kirouane/interval.git`

* Install
`composer install`

* Test
`vendor/bin/phpunit`   

* Build
`vendor/bin/grumphp run`

