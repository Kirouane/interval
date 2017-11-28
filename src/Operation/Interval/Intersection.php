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
     * @throws \InvalidArgumentException
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
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     * @throws \RangeException
     */
    public function compute(Interval $first, Interval $second): ?Interval
    {
        if ($first->isNeighborBefore($second)) {
            return new Interval(
                $first->getEnd(),
                $second->getStart()
            );
        }

        if ($first->isNeighborAfter($second)) {
            return new Interval(
                $second->getEnd(),
                $first->getStart()
            );
        }

        if (!$first->overlaps($second)) {
            return null;
        }

        return new Interval(
            $first->getStart()->greaterThan($second->getStart()) ? $first->getStart() : $second->getStart(),
            $first->getEnd()->lessThan($second->getEnd()) ? $first->getEnd() : $second->getEnd()
        );
    }
}
