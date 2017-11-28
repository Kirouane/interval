<?php
declare(strict_types=1);

namespace Interval\Rule\Interval;

use Interval\Interval;

/**
 * Class Neighborhood
 * @package Interval\Rule\Interval
 */
class NeighborhoodBefore
{
    /**
     * Returns true if the second interval is neighbor of the first one
     * @param Interval $first
     * @param Interval $second
     * @return bool
     */
    public function assert(Interval $first, Interval $second): bool
    {
        return $second->getStart()->equalTo($first->getEnd());
    }
}
