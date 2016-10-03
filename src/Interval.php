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
     * @var mixed
     */
    private $comparableStart;

    /**
     * @var mixed
     */
    private $comparableEnd;

    /**
     * Interval constructor.
     * @param mixed $start
     * @param mixed $end
     * @throws \RangeException
     * @throws \UnexpectedValueException
     */
    public function __construct($start, $end)
    {
        $this->start = $start;
        $this->end   = $end;

        $this->comparableStart = self::toComparable($this->start);
        $this->comparableEnd   = self::toComparable($this->end);

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
        return $this->comparableStart < $this->comparableEnd;
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
     * @return mixed
     */
    public function getComparableStart()
    {
        return $this->comparableStart;
    }

    /**
     * @return mixed
     */
    public function getComparableEnd()
    {
        return $this->comparableEnd;
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

    /**
     * Convert an endpoint to comparable
     * @param mixed $endpoint
     * @return mixed
     * @throws \UnexpectedValueException
     */
    public static function toComparable($endpoint)
    {
        $isInternallyType = is_numeric($endpoint) || is_bool($endpoint) || is_string($endpoint);

        $comparable = null;
        if ($isInternallyType) {
            $comparable = $endpoint;
        } elseif ($endpoint instanceof \DateTimeInterface) {
            $comparable = $endpoint->getTimestamp();
        } else {
            throw new \UnexpectedValueException('Unexpected endpoint type');
        }

        return $comparable;
    }
}