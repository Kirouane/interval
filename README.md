[![Travis](https://img.shields.io/travis/Kirouane/interval/master.svg)](http://travis-ci.org/Kirouane/interval)
[![Coverage Status](https://coveralls.io/repos/github/Kirouane/interval/badge.svg)](https://coveralls.io/github/Kirouane/interval)
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


Basic usage
---------

Let's assume an interval [20, 40].
We instantiate a new \Interval\Interval object .

/var/www/interval/src/Parser/IntervalParser.php:135:
bool(false)
