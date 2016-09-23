<?php
declare(strict_types=1);
namespace Interval\Operation\Interval;
use Interval\Interval;
use Interval\Intervals;

/**
 * Interface IntervalInterface
 * @package Interval
 */
class Intersection
{
    /**
     * @param Interval $first
     * @param Interval $second
     * @return array
     */
    public function __invoke(Interval $first, Interval $second)
    {
        return $this->compute($first, $second);
    }

    /**
     * @param Interval $first
     * @param Interval $second ->getStart()
     *
     * @return null
     */
    public function compute(Interval $first, Interval $second)
    {
        if ($first->getEnd() <= $second->getStart() || $first->getStart() >= $second->getEnd()) {
            return null;
        } else {
            return new Interval(
                max ($first->getStart(), $second->getStart()),
                min ($first->getEnd(), $second->getEnd())
            );
        }
    }
}