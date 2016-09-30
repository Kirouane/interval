<?php
ini_set('display_errors', 1);
require_once __DIR__ . '/vendor/autoload.php';


echo "Basic usage
---------\n\n";

echo "Let's assume an interval [20, 40].\n";
echo "We instantiate a new \\Interval\\Interval object .\n\n";
$interval = new \Interval\Interval(20, 40);
echo '```php
$interval = new \Interval\Interval(20, 40)// ' . $interval . ';
```' . "\n";
echo "\n";
echo "\nWe can make some operations like : ";
echo "\n";
echo "* Intersection : \n\n";

echo '```php
echo $interval->intersect(new \Interval\Interval(30, 60)); // ' . $interval->intersect(new \Interval\Interval(30, 60)) . ';
```' . "\n";

echo "\n";
echo "* Union : \n\n";
echo '```php
echo $interval->union(new \Interval\Interval(30, 60)); // ' . $interval->union(new \Interval\Interval(30, 60)) . ';
```' . "\n\n";
echo "or\n\n";
echo '```php
echo $interval->union(new \Interval\Interval(60, 100)); // ' . $interval->union(new \Interval\Interval(60, 100)) . ';
```' . "\n";

echo "\n";
echo "* Exclusion : \n\n";
echo '```php
echo $interval->exclude(new \Interval\Interval(30, 60)); // ' . $interval->exclude(new \Interval\Interval(30, 60)) . ';
```' . "\n\n";
echo "or\n\n";
echo '```php
echo $interval->exclude(new \Interval\Interval(30, 35)); // ' . $interval->exclude(new \Interval\Interval(30, 35)) . ';
```' . "\n";
echo "\n";
echo 'We can also make comparisons like : ';
echo "* Overlap test : \n\n";

echo '```php
echo $interval->overlaps(new \Interval\Interval(30, 60)); // ' . ($interval->overlaps(new \Interval\Interval(30, 60)) ? 'true' : 'false') . ';
```' . "\n";
echo "\n";
echo "* Inclusion test : \n\n";

echo '```php
echo $interval->includes(new \Interval\Interval(30, 60)); // ' . ($interval->includes(new \Interval\Interval(30, 60)) ? 'true' : 'false') . ';
```' . "\n";

echo "Use DateTimeInterface as endpoints
---------\n\n";
$interval = new \Interval\Interval(new \DateTime('2016-01-01'), new \DateTime('2016-01-10'));
echo '```php
$interval = new \Interval\Interval(new \DateTime(\'2016-01-01\'), new \DateTime(\'2016-01-10\'))// ' . $interval . ';
```' . "\n";
echo "\n";
echo "* Union : \n\n";
echo '```php
echo $interval->union(new \Interval\Interval(new \DateTime(\'2016-01-10\'), new \DateTime(\'2016-01-15\'))); // ' . $interval->union(new \Interval\Interval(new \DateTime('2016-01-10'), new \DateTime('2016-01-15'))) . ';
```' . "\n\n";
