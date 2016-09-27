<?php
declare(strict_types=1);
namespace Interval\Rule\Interval;
use Interval\Interval;


/**
 * Class Inclusion
 * @package Interval\Rule\Interval
 */
class Neighborhood
{
    /**
     * Returns true if the second interval is neighbor of the first one
     * @param Interval $first
     * @param Interval $second
     * @return bool
     */
    public function assert(Interval $first, Interval $second)
    {
        return $first->getStart() === $second->getEnd() || $second->getStart() === $first->getEnd();
    }
}