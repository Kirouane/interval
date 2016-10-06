<?php
declare(strict_types=1);
namespace Interval\Operation\Interval;
use Interval\Interval;
use Interval\Intervals;

/**
 * Class Exclusion
 * @package Interval\Operation\Interval
 */
class Exclusion
{
    /**
     * PHP magic function
     * @param Interval $first
     * @param Interval $second
     * @return array
     * @throws \UnexpectedValueException
     * @throws \RangeException
     */
    public function __invoke(Interval $first, Interval $second)
    {
        return $this->compute($first, $second);
    }

    /**
     * Excludes an interval from another one. Exp
     *
     *      |_________________|
     *
     *             -
     *                  |_________________|
     *
     *          =
     *      |___________|
     *
     * @param Interval $first
     * @param Interval $second
     * @return Intervals
     * @throws \UnexpectedValueException
     * @throws \RangeException
     */
    public function compute(Interval $first, Interval $second)
    {
        if ($first->getComparableEnd() <= $second->getComparableStart() || $first->getComparableStart() >= $second->getComparableEnd()) {
            return new Intervals([$first]);
        } elseif ($second->getComparableStart() <= $first->getComparableStart() && $second->getComparableEnd() >= $first->getComparableEnd()) {
            return new Intervals([]);
        } elseif ($second->getComparableStart() > $first->getComparableStart() && $second->getComparableEnd() < $first->getComparableEnd()) {
            return new Intervals([
                new Interval($first->getStart(), $second->getStart()),
                new Interval($second->getEnd(), $first->getEnd())
            ]);
        } elseif ($second->getComparableEnd() > $first->getComparableStart() && $second->getComparableEnd() < $first->getComparableEnd()) {
            return new Intervals([new Interval($second->getEnd(), $first->getEnd())]);
        } else {
            return new Intervals([new Interval($first->getStart(), $second->getStart())]);
        }
    }
}