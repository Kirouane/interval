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
 * Class Interval
 * @package Interval
 */
class Interval
{
    /**
     * @var mixed
     */
    private $start;

    /**
     * @var mixed
     */
    private $end;

    /**
     * Interval constructor.
     * @param mixed $start
     * @param mixed $end
     * @throws \RangeException
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
     * Returns false if the interval is not consistent like endTime <= starTime
     * @return bool
     */
    private function isConsistent()
    {
        if (!$this->sameType($this->start, $this->end)) {
            return false;
        }
        return $this->start < $this->end;
    }

    /**
     * @param $start
     * @param $end
     * @return bool
     */
    private function sameType($start, $end)
    {
        return self::sameInternallyType($start, $end) && self::sameObjectInstanceName($start, $end);
    }

    /**
     *
     * @param $start
     * @param $end
     * @return bool
     */
    private static function sameObjectInstanceName($start, $end)
    {
        if (!is_object($start) && !is_object($end)) {
            return true;
        }

        if (!is_object($start) && is_object($end)) {
            return false;
        }


        if (is_object($start) && !is_object($end)) {
            return false;
        }

        return get_class($start) === get_class($end);
    }

    /**
     * @param $start
     * @param $end
     * @return bool
     */
    private static function sameInternallyType($start, $end)
    {
        return gettype($start) === gettype($end);
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
     * @param Interval $interval
     * @return Intervals
     */
    public function union(Interval $interval) : Intervals
    {
        $operation = new Union();
        return $operation($this, $interval);
    }

    /**
     * Excludes this interval from another one. Exp
     *
     *      |_________________|
     *
     *             -
     *                  |_________________|
     *
     *          =
     *      |___________|
     *
     * @param Interval $interval
     * @return Intervals
     */
    public function exclude(Interval $interval) : Intervals
    {
        $operation = new Exclusion();
        return $operation($this, $interval);
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
     * @param Interval $interval
     * @return Interval
     */
    public function intersect(Interval $interval)
    {
        $operation = new Intersection();
        return $operation($this, $interval);
    }

    /**
     * Checks whether or not this interval overlaps another one
     *
     * @param Interval $interval
     * @return bool
     */
    public function overlaps(Interval $interval) : bool
    {
        $asserter = new Overlapping();
        return $asserter->assert($this, $interval);
    }

    /**
     * Checks whether or not this interval includes entirely another one
     *
     * @param Interval $interval
     * @return bool
     */
    public function includes(Interval $interval) : bool
    {
        $asserter = new Inclusion();
        return $asserter->assert($this, $interval);
    }

    /**
     * Checks whether or not this interval is neighbor of another one. Exp :
     *
     *      |_________________|
     *                        |_________________|
     *
     *                OR
     *
     *                        |_________________|
     *      |_________________|
     *
     * @param Interval $interval
     * @return bool
     */
    public function isNeighborOf(Interval $interval) : bool
    {
        $asserter = new Neighborhood();
        return $asserter->assert($this, $interval);
    }

    /**
     * Returns the start endpoint
     * @return mixed
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Returns the end endpoint
     * @return mixed
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $start = ($this->start instanceof \DateTimeInterface) ? $this->start->format(\DateTime::RFC3339) : $this->start;
        $end = ($this->end instanceof \DateTimeInterface) ? $this->end->format(\DateTime::RFC3339) : $this->end;
        return '[' . $start . ', ' . $end  . ']';
    }
}