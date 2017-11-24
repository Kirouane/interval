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
     * PHP magic function
     * @param Interval $first
     * @param Interval $second
     * @return Intervals
     * @throws \UnexpectedValueException
     * @throws \RangeException
     */
    public function __invoke(Interval $first, Interval $second)
    {
        return $this->compute($first, $second);
    }

    /**
     * Compute the union between two intervals. Exp :
     *
     *      |_________________|
     *
     *             ∪
     *                  |_________________|
     *
     *          =
     *      |_____________________________|
     *
     * @param Interval $first
     * @param Interval $second
     * @return Intervals
     * @throws \UnexpectedValueException
     * @throws \RangeException
     */
    public function compute(Interval $first, Interval $second) : Intervals
    {
        if ($second->getEnd() < $first->getStart()) {
            return new Intervals([new Interval($first->getStart(), $first->getEnd()), new Interval($second->getStart(), $second->getEnd())]);
        }

        if ($second->getEnd() >= $first->getStart() && $second->getStart() <= $first->getEnd()) {
            return new Intervals([new Interval(
                \min($first->getStart(), $second->getStart()),
                \max($first->getEnd(), $second->getEnd())
            )]);
        }

        return new Intervals([new Interval($first->getStart(), $first->getEnd()), new Interval($second->getStart(), $second->getEnd())]);
    }
}
