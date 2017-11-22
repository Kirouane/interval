[![Travis](https://img.shields.io/travis/Kirouane/interval/master.svg)](http://travis-ci.org/Kirouane/interval)
[![Installs](https://img.shields.io/packagist/dt/Kirouane/interval.svg)](https://packagist.org/packages/Kirouane/interval/stats)
[![Packagist](https://img.shields.io/packagist/v/Kirouane/interval.svg)](https://packagist.org/packages/Kirouane/interval)

Interval
======

This library provides some tools to manipulate intervals. For instance, You can compute the union or intersection of two intervals.

Features
------

* It computes **basic operations** between two intervals: union, intersection and exclusion.
* It computes the exclusion between two **sets of intervals**.
* It handles several types of boundary (endpoints) : float, **\DateTime**, integer, and string. 
* It handles **infinity** boundaries.
* Ability to **combine** infinity with \DateTime and other types

Install
------

The fastest way to install Interval is to add it to your project using Composer

`composer require kirouane/interval`


Basic usage
---------

Let's assume an interval [20, 40].
We instantiate a new \Interval\Interval object .

```php
$interval = new \Interval\Interval(20, 40)// [20, 40];
```

or

```php
$interval = \Interval\Interval::create('[20,40]')// [20, 40];
```


We can do some operations like : 
* Intersection : 

```php
echo $interval->intersect(new \Interval\Interval(30, 60)); // [30, 40];
```

* Union : 

```php
echo $interval->union(new \Interval\Interval(30, 60)); // {[20, 60]};
```

or

```php
echo $interval->union(new \Interval\Interval(60, 100)); // {[20, 40], [60, 100]};
```

* Exclusion : 

```php
echo $interval->exclude(new \Interval\Interval(30, 60)); // {[20, 30]};
```

or

```php
echo $interval->exclude(new \Interval\Interval(30, 35)); // {[20, 30], [35, 40]};
```

We can compare two intervals as well: 
* Overlapping test : 

```php
echo $interval->overlaps(new \Interval\Interval(30, 60)); // true;
```

* Inclusion test : 

```php
echo $interval->includes(new \Interval\Interval(30, 60)); // false;
```
Use DateTimeInterface as endpoints
---------

```php
$interval = new \Interval\Interval(new \DateTime('2016-01-01'), new \DateTime('2016-01-10'))// [2016-01-01T00:00:00+01:00, 2016-01-10T00:00:00+01:00];
```

* Union : 

```php
echo $interval->union(new \Interval\Interval(new \DateTime('2016-01-10'), new \DateTime('2016-01-15'))); // {[2016-01-01T00:00:00+01:00, 2016-01-15T00:00:00+01:00]};
```

Use Infinity as endpoints
---------

```php
$interval = new \Interval\Interval(-INF, INF);// [-∞, +∞];
```

* Exclusion : 

```php
echo $interval->union(new \Interval\Interval(new \DateTime('2016-01-10'), new \DateTime('2016-01-15'))); // {[-∞, 2016-01-10T00:00:00+01:00], [2016-01-15T00:00:00+01:00, +∞]};
```

