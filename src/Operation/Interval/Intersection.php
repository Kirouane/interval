<?php
declare(strict_types=1);

namespace Interval\Operation\Interval;

use Interval\Interval;

/**
 * Class Intersection
 * @package Interval\Operation\Interval
 */
class Intersection
{
    /**
     * PHP magic function
     * @param Interval $first
     * @param Interval $second
     * @return Interval
     * @throws \UnexpectedValueException
     * @throws \RangeException
     */
    public function __invoke(Interval $first, Interval $second)
    {
        return $this->compute($first, $second);
    }

    /**
     * Compute the intersection of two intervals. Exp
     *
     *      |_________________|
     *
     *             ∩
     *                  |_________________|
     *
     *          =
     *                  |_____|
     *
     * @param Interval $first
     * @param Interval $second
     * @return Interval
     * @throws \UnexpectedValueException
     * @throws \RangeException
     */
    public function compute(Interval $first, Interval $second): ?Interval
    {
        if (!$first->overlaps($second)) {
            return null;
        }

        return new Interval(
            \max($first->getStart(), $second->getStart()),
            \min($first->getEnd(), $second->getEnd())
        );
    }
}
