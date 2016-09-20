<?php
declare(strict_types=1);
namespace Interval\Operation\Interval;
use Interval\Interval;
use Interval\Intervals;

/**
 * Interface IntervalInterface
 * @package Interval
 */
class Union
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
     * @return array [Interval]
     */
    public function compute(Interval $first, Interval $second) : Intervals
    {
        if ($second->getEnd() < $first->getStart()) {
            return new Intervals([new Interval($first->getStart(), $first->getEnd()), new Interval($second->getStart(), $second->getEnd())]);
        } elseif ($second->getEnd() >= $first->getStart() && $second->getStart() <= $first->getEnd()) {
            return new Intervals([new Interval(
                min($first->getStart(), $second->getStart()),
                max($first->getEnd(), $second->getEnd())
            )]);
        } elseif ($second->getStart() > $first->getEnd()) {
            return new Intervals([new Interval($first->getStart(), $first->getEnd()), new Interval($second->getStart(), $second->getEnd())]);
        } else {
            return new Intervals([]);
        }
    }
}