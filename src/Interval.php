<?php
declare(strict_types=1);
namespace Interval;
use Interval\Operation\Interval\Intersection;
use Interval\Operation\Interval\Union;

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
     * @return array
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

    }

    /**
     * @param Interval $interval
     * @return bool
     */
    public function includes(Interval $interval) : bool
    {

    }

    /**
     * @param Interval $interval
     * @return bool
     */
    public function isNeighborOf(Interval $interval) : bool
    {

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