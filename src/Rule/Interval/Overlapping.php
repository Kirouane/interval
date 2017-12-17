<?php
declare(strict_types=1);

namespace Interval\Rule\Interval;

use Interval\Interval;

/**
 * Class Overlapping
 * @package Interval\Rule\Interval
 */
class Overlapping
{
    /**
     * Returns true if the second interval overlaps the first one
     * @param Interval $first
     * @param Interval $second
     * @return bool
     */
    public function assert(Interval $first, Interval $second): bool
    {
        return
            $first->getEnd() > $second->getStart() &&
            $first->getStart() < $second->getEnd();
    }
}
