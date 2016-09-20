<?php
ini_set('display_errors', 1);
require_once __DIR__ . '/vendor/autoload.php';

$i = new \Interval\Interval(1, 2);
var_dump($i->union(new \Interval\Interval(2, 3)));