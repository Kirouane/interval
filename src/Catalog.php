<?php
declare(strict_types=1);
namespace Interval;

/**
 * Class Catalog
 * @package Interval
 */
class Catalog
{
    const OPERATION_INTERVAL_EXCLUSION     = '\Interval\Operation\Interval\Exclusion';
    const OPERATION_INTERVAL_INTERSECTION  = '\Interval\Operation\Interval\Intersection';
    const OPERATION_INTERVAL_UNION         = '\Interval\Operation\Interval\Union';

    const OPERATION_INTERVALS_EXCLUSION    = '\Interval\Operation\Intervals\Exclusion';

    const RULE_INTERVAL_INCLUSION          = '\Interval\Rule\Interval\Inclusion';
    const RULE_INTERVAL_NEIGHBORHOOD       = '\Interval\Rule\Interval\Neighborhood';
    const RULE_INTERVAL_OVERLAPPING        = '\Interval\Rule\Interval\Overlapping';

    const PARSER                           = '\Interval\Parser';

    /**
     * @var array
     */
    private $services = [];

    /**
     * Instantiates and/or returns a service by its name
     * @param $name
     * @return mixed
     */
    public function get($name)
    {
        if (!isset($this->services[$name])) {
            $this->services[$name] = new $name();
        }

        return $this->services[$name];
    }

}