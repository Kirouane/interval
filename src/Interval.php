<?php
declare(strict_types=1);
namespace Interval;
use Interval\Operation\Interval\Exclusion;
use Interval\Operation\Interval\Intersection;
use Interval\Operation\Interval\Union;
use Interval\Rule\Interval\Inclusion;
use Interval\Rule\Interval\Neighborhood;
use Interval\Rule\Interval\Overlapping;

/**
 * Interface IntervalInterface
 * @package Interval
 */
class Interval
{
    /**
     * @var Endpoint
     */
    private $start;

    /**
     * @var Endpoint
     */
    private $end;

    /**
     * Interval constructor.
     * @param $start
     * @param $end
     */
    public function __construct($start, $end)
    {
        $this->start = $start;
        $this->end   = $end;
        if (!$this->isConsistent()) {
            throw new \RangeException('Inconsistent Interval');
        }
    }

    /**
     * @return bool
     */
    private function isConsistent()
    {
        if ($this->start >= $this->end) {
            return false;
        }

        return true;
    }

    /**
     * @param Interval $interval
     * @return Intervals
     */
    public function union(Interval $interval) : Intervals
    {
        $operation = new Union();
        return $operation($this, $interval);
    }

    /**
     * @param Interval $interval
     * @return Intervals
     */
    public function exclude(Interval $interval) : Intervals
    {
        $operation = new Exclusion();
        return $operation($this, $interval);
    }

    /**
     * @param Interval $interval
     * @return Interval
     */
    public function intersect(Interval $interval)
    {
        $operation = new Intersection();
        return $operation($this, $interval);
    }

    /**
     * @param Interval $interval
     * @return bool
     */
    public function overlaps(Interval $interval) : bool
    {
        $asserter = new Overlapping();
        return $asserter->assert($this, $interval);
    }

    /**
     * @param Interval $interval
     * @return bool
     */
    public function includes(Interval $interval) : bool
    {
        $asserter = new Inclusion();
        return $asserter->assert($this, $interval);
    }

    /**
     * @param Interval $interval
     * @return bool
     */
    public function isNeighborOf(Interval $interval) : bool
    {
        $asserter = new Neighborhood();
        return $asserter->assert($this, $interval);
    }

    /**
     * @return Endpoint
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @return Endpoint
     */
    public function getEnd()
    {
        return $this->end;
    }
}