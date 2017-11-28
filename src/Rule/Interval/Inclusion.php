<?php
declare(strict_types=1);

namespace Interval\Rule\Interval;

use Interval\Interval;

/**
 * Class Inclusion
 * @package Interval\Rule\Interval
 */
class Inclusion
{
    /**
     * Returns true if the second interval is entirely included in the first one
     * @param Interval $first
     * @param Interval $second
     * @return bool
     */
    public function assert(Interval $first, Interval $second): bool
    {
        return
            $second->getStart()->greaterThanOrEqualTo($first->getStart()) &&
            $second->getEnd()->lessThanOrEqualTo($first->getEnd());
    }
}
