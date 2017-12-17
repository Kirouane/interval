<?php
declare(strict_types=1);

namespace Interval;

use Interval\Parser\IntervalParser;
use Interval\Parser\IntervalsParser;

/**
 * Class Di
 * @package Interval
 */
class Di
{
    const OPERATION_INTERVAL_EXCLUSION     = Operation\Interval\Exclusion::class;
    const OPERATION_INTERVAL_INTERSECTION  = Operation\Interval\Intersection::class;
    const OPERATION_INTERVAL_UNION         = Operation\Interval\Union::class;

    const OPERATION_INTERVALS_EXCLUSION    = Operation\Intervals\Exclusion::class;

    const RULE_INTERVAL_INCLUSION                 = Rule\Interval\Inclusion::class;
    const RULE_INTERVAL_NEIGHBORHOOD_BEFORE       = Rule\Interval\NeighborhoodBefore::class;
    const RULE_INTERVAL_NEIGHBORHOOD_AFTER        = Rule\Interval\NeighborhoodAfter::class;
    const RULE_INTERVAL_OVERLAPPING               = Rule\Interval\Overlapping::class;
    const RULE_INTERVAL_STARTING                  = Rule\Interval\Starting::class;
    const RULE_INTERVAL_ENDING                    = Rule\Interval\Ending::class;
    const RULE_INTERVAL_BEFORE                    = Rule\Interval\Before::class;
    const RULE_INTERVAL_AFTER                     = Rule\Interval\After::class;
    const RULE_INTERVAL_EQUALITY                  = Rule\Interval\Equality::class;

    const PARSER_INTERVAL                   = IntervalParser::class;
    const PARSER_INTERVALS                  = IntervalsParser::class;

    const DI = [
        self::PARSER_INTERVALS => [
            self::PARSER_INTERVAL,
        ],
    ];

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
        if (isset($this->services[$name])) {
            return $this->services[$name];
        }

        if (isset(self::DI[$name])) {
            /** @var array $argServicesName */
            $argServicesName = self::DI[$name];
            $args            = [];
            foreach ($argServicesName as $argServiceName) {
                $args[] = $this->get($argServiceName);
            }
            return $this->services[$name] = new $name(...$args);
        }

        return $this->services[$name] = new $name();
    }
}
