<?php

use Interval\Interval;
use Interval\Intervals;

ini_set('display_errors', 1);
require_once __DIR__ . '/../vendor/autoload.php';

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
        return $first->getStart() <=> $second->getStart();
    })
    ->map(function (Interval $interval) {
        return new Interval(
            $interval->getStart() ** 2,
            $interval->getEnd() ** 2
        );
    })
    ->filter(function(Interval $interval) {
        return $interval->getEnd() > 170;
    }); ' . "\n\n"  . '// ' .$result . ';
```' . "\n\n";
