<?php
ini_set('display_errors', 1);
require_once __DIR__ . '/vendor/autoload.php';


use Interval\Interval;

$interval = new Interval(20, 40);
$interval
    ->intersect(new Interval(30, 60))
    ->union(new Interval(40, 90))[0]
    ->exclude(new Interval(80, 90));
