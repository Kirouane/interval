Interval
======

This library provides some tools to manipulate intervals. For instance, You can compute the union or intersection of two intervals. 


Install
------

The fastest way to install Interval is to add it to your project using Composer

`php composer.phar require nassimkirouane/interval`


Usage
------------
```php
use Interval\Interval;

$interval = new Interval(20, 40);
$interval
    ->intersect(new Interval(30, 60))
    ->union(new Interval(40, 90))[0]
    ->exclude(new Interval(80, 90));
```