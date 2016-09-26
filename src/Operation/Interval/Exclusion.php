<?php
declare(strict_types=1);
namespace Interval\Operation\Interval;
use Interval\Interval;
use Interval\Intervals;

/**
 * Interface IntervalInterface
 * @package Interval
 */
class Exclusion
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
            return new Intervals([$first]);
        } elseif ($second->getStart() <= $first->getStart() && $second->getEnd() >= $first->getEnd()) {
            return new Intervals([]);
        } elseif ($second->getStart() > $first->getStart() && $second->getEnd() < $first->getEnd()) {
            return new Intervals([
                new Interval($first->getStart(), $second->getStart()),
                new Interval($second->getEnd(), $first->getEnd())
            ]);
        } elseif ($second->getEnd() > $first->getStart() && $second->getEnd() < $first->getEnd()) {
            return new Intervals([new Interval($second->getEnd(), $first->getEnd())]);
        } else {
            return new Intervals([new Interval($first->getStart(), $second->getStart())]);
        }
    }
}