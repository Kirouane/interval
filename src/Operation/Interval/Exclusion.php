<?php
declare(strict_types=1);
namespace Interval\Operation\Interval;

use Interval\Interval;
use Interval\Intervals;
use RangeException;
use UnexpectedValueException;

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
     * @return Intervals
     * @throws UnexpectedValueException
     * @throws RangeException
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
     * @throws UnexpectedValueException
     * @throws RangeException
     */
    public function compute(Interval $first, Interval $second): Intervals
    {
        if ($first->getComparableEnd() <= $second->getComparableStart() || $first->getComparableStart() >= $second->getComparableEnd()) {
            return new Intervals([$first]);
        }

        if ($second->getComparableStart() <= $first->getComparableStart() && $second->getComparableEnd() >= $first->getComparableEnd()) {
            return new Intervals([]);
        }

        if ($second->getComparableEnd() < $first->getComparableEnd()) {
            if ($second->getComparableStart() > $first->getComparableStart()) {
                return new Intervals([
                    new Interval($first->getStart(), $second->getStart()),
                    new Interval($second->getEnd(), $first->getEnd())
                ]);
            }

            if ($second->getComparableEnd() > $first->getComparableStart()) {
                return new Intervals([new Interval($second->getEnd(), $first->getEnd())]);
            }
        }

        return new Intervals([new Interval($first->getStart(), $second->getStart())]);
    }
}
