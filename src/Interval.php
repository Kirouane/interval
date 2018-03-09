<?php
declare(strict_types=1);

namespace Interval;

use Interval\Boundary\BoundaryAbstract;
use Interval\Boundary\DateTime;
use Interval\Boundary\Infinity;
use Interval\Boundary\Integer;
use Interval\Boundary\Real;

/**
 * Class Interval
 * @package Interval
 */
class Interval
{
    /**
     * @var \Interval\Di
     */
    private static $di;

    /**
     * @var BoundaryAbstract
     */
    private $start;

    /**
     * @var BoundaryAbstract
     */
    private $end;

    /**
     * Interval constructor.
     * @param mixed $start
     * @param mixed $end
     * @param bool $isLeftOpen
     * @param bool $isRightOpen
     * @throws \RangeException
     * @throws \InvalidArgumentException
     */
    public function __construct($start, $end, bool $isLeftOpen = false, bool $isRightOpen = false)
    {
        self::loadId();

        $this->start = $this->toBoundary($start, true, $isLeftOpen);
        $this->end   = $this->toBoundary($end, false, $isRightOpen);

        if (!$this->isConsistent()) {
            throw new \RangeException('Inconsistent Interval');
        }
    }

    /**
     * @param $value
     * @param bool $isLeft
     * @param bool $isOpen
     * @return BoundaryAbstract
     * @throws \InvalidArgumentException
     */
    private function toBoundary($value, bool $isLeft, bool $isOpen): Boundary\BoundaryAbstract
    {
        if ($value instanceof BoundaryAbstract) {
            return $value;
        }

        if (\is_int($value)) {
            return new Integer($value, $isLeft, $isOpen);
        }

        if (null === $value) {
            return new Infinity($isLeft ? -INF : INF, $isLeft, true);
        }

        if (\is_float($value) && \is_infinite($value)) {
            return new Infinity($value, $isLeft, true);
        }

        if (\is_float($value)) {
            return new Real($value, $isLeft, $isOpen);
        }

        if ($value instanceof \DateTimeInterface) {
            return new DateTime($value, $isLeft, $isOpen);
        }

        throw new \InvalidArgumentException('Unexpected $value type');
    }

    /**
     * Returns false if the interval is not consistent like endTime <= starTime
     * @return bool
     */
    private function isConsistent(): bool
    {
        return $this->getStart()->lessThanOrEqualTo($this->getEnd());
    }

    /**
     * @param string $name
     * @param Interval $interval
     * @return mixed
     */
    private function operate(string $name, Interval $interval)
    {
        return self::$di->get($name)($this, $interval);
    }

    /**
     * @param string $name
     * @param Interval $interval
     * @return mixed
     */
    private function assert(string $name, Interval $interval)
    {
        return self::$di->get($name)->assert($this, $interval);
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
        return $this->operate(Di::OPERATION_INTERVAL_UNION, $interval);
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
    public function intersect(Interval $interval): Interval
    {
        return $this->operate(Di::OPERATION_INTERVAL_INTERSECTION, $interval);
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
        return $this->operate(Di::OPERATION_INTERVAL_EXCLUSION, $interval);
    }

    /**
     * Checks whether or not this interval overlaps another one
     *
     * @param Interval $interval
     * @return bool
     */
    public function overlaps(Interval $interval) : bool
    {
        return $this->assert(Di::RULE_INTERVAL_OVERLAPPING, $interval);
    }

    /**
     * Checks whether or not this interval includes entirely another one
     *
     *    |_________________|
     *
     *             includes
     *        |_______|
     *
     *          =
     *      true
     *
     * @param Interval $interval
     * @return bool
     */
    public function includes(Interval $interval) : bool
    {
        return $this->assert(Di::RULE_INTERVAL_INCLUSION, $interval);
    }

    /**
     * Checks whether or not this interval is neighbor (before) of another one.
     * Exp :
     *
     *      |_________________|
     *                        |_________________|
     *
     * @param Interval $interval
     * @return bool
     */
    public function isNeighborBefore(Interval $interval) : bool
    {
        return $this->assert(Di::RULE_INTERVAL_NEIGHBORHOOD_BEFORE, $interval);
    }

    /**
     * Checks whether or not this interval is neighbor (after) of another one.
     * Exp :
     *
     *                        |_________________|
     *      |_________________|
     *
     * @param Interval $interval
     * @return bool
     */
    public function isNeighborAfter(Interval $interval) : bool
    {
        return $this->assert(Di::RULE_INTERVAL_NEIGHBORHOOD_AFTER, $interval);
    }

    /**
     *
     *      |__________________________|
     *      |_________________|
     *
     * @param Interval $interval
     * @return bool
     */
    public function starts(Interval $interval) : bool
    {
        return $this->assert(Di::RULE_INTERVAL_STARTING, $interval);
    }

    /**
     *      |__________________________|
     *               |_________________|
     *
     * @param Interval $interval
     * @return bool
     */
    public function ends(Interval $interval) : bool
    {
        return $this->assert(Di::RULE_INTERVAL_ENDING, $interval);
    }

    /**
     *      |__________________________|
     *      |__________________________|
     *
     *
     * @param Interval $interval
     * @return bool
     */
    public function equals(Interval $interval) : bool
    {
        return $this->assert(Di::RULE_INTERVAL_EQUALITY, $interval);
    }

    /**
     *
     *       |_______________|
     *                           |________|
     *
     *
     * @param Interval $interval
     * @return bool
     */
    public function isBefore(Interval $interval) : bool
    {
        return $this->assert(Di::RULE_INTERVAL_BEFORE, $interval);
    }

    /**
     *                 |_______________|
     *    |________|
     *
     * @param Interval $interval
     * @return bool
     */
    public function isAfter(Interval $interval) : bool
    {
        return $this->assert(Di::RULE_INTERVAL_AFTER, $interval);
    }

    /**
     *
     * Returns the start boundary
     * @return BoundaryAbstract
     */
    public function getStart(): BoundaryAbstract
    {
        return $this->start;
    }

    /**
     * Returns the end boundary
     * @return BoundaryAbstract
     */
    public function getEnd(): BoundaryAbstract
    {
        return $this->end;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getStart() . ', ' . $this->getEnd();
    }

    /**
     * Convert an boundary to comparable
     * @param mixed $boundary
     * @return mixed
     * @throws \UnexpectedValueException
     */
    public static function toComparable($boundary)
    {
        $isInternallyType = \is_numeric($boundary) || \is_bool($boundary) || \is_string($boundary);

        $comparable = null;
        if ($isInternallyType) {
            $comparable = $boundary;
        } elseif ($boundary instanceof \DateTimeInterface) {
            $comparable = $boundary->getTimestamp();
        } else {
            throw new \UnexpectedValueException('Unexpected boundary type');
        }

        return $comparable;
    }

    /**
     * Loads the service di
     * @return Di
     */
    private static function loadId(): Di
    {
        if (!self::$di) {
            self::$di = new Di();
        }

        return self::$di;
    }

    /**
     * Creates a new Interval from expression
     * Exp Interval::create('[10, 26[')
     * @param string $expression
     * @return Interval
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     * @throws \RangeException
     * @throws \ErrorException
     */
    public static function create(string $expression) : Interval
    {
        /** @var \Interval\Parser\IntervalParser $parser */
        $parser = self::loadId()->get(Di::PARSER_INTERVAL);
        return $parser->parse($expression);
    }

    /**
     * @param BoundaryAbstract $boundary
     * @return bool
     */
    public function contains(BoundaryAbstract $boundary): bool
    {
        return $this->getStart()->lessThanOrEqualTo($boundary) && $this->getEnd()->greaterThanOrEqualTo($boundary);
    }
}
