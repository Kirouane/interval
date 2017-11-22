<?php
declare(strict_types=1);
namespace Interval;

/**
 * Class Catalog
 * @package Interval
 */
class Catalog
{
    const OPERATION_INTERVAL_EXCLUSION     = Operation\Interval\Exclusion::class;
    const OPERATION_INTERVAL_INTERSECTION  = Operation\Interval\Intersection::class;
    const OPERATION_INTERVAL_UNION         = Operation\Interval\Union::class;

    const OPERATION_INTERVALS_EXCLUSION    = Operation\Intervals\Exclusion::class;

    const RULE_INTERVAL_INCLUSION          = Rule\Interval\Inclusion::class;
    const RULE_INTERVAL_NEIGHBORHOOD       = Rule\Interval\Neighborhood::class;
    const RULE_INTERVAL_OVERLAPPING        = Rule\Interval\Overlapping::class;

    const PARSER                           = Parser::class;

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
