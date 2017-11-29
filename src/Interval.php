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
     * @var \Interval\Catalog
     */
    private static $catalog;

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
     * @throws \UnexpectedValueException
     * @throws \RangeException
     * @throws \InvalidArgumentException
     */
    public function __construct($start, $end)
    {
        self::loadCatalog();

        $this->start = $this->toBoundary($start, true);
        $this->end   = $this->toBoundary($end, false);

        if (!$this->isConsistent()) {
            throw new \RangeException('Inconsistent Interval');
        }
    }

    /**
     * @param $value
     * @param $isLeft
     * @return BoundaryAbstract
     * @throws \InvalidArgumentException
     */
    private function toBoundary($value, $isLeft): Boundary\BoundaryAbstract
    {
        if ($value instanceof BoundaryAbstract) {
            return $value;
        }

        if (\is_int($value)) {
            return new Integer($value, $isLeft);
        }

        if (\is_float($value) && \is_infinite($value)) {
            return new Infinity($value, $isLeft);
        }

        if (\is_float($value)) {
            return new Real($value, $isLeft);
        }

        if ($value instanceof \DateTimeInterface) {
            return new DateTime($value, $isLeft);
        }

        throw new \InvalidArgumentException('Unexpected $value type');
    }

    /**
     * Returns false if the interval is not consistent like endTime <= starTime
     * @return bool
     */
    private function isConsistent(): bool
    {
        return $this->getStart()->getValue() <= $this->getEnd()->getValue();
    }

    /**
     * @param string $name
     * @param Interval $interval
     * @return mixed
     */
    private function operate(string $name, Interval $interval)
    {
        return self::$catalog->get($name)($this, $interval);
    }

    /**
     * @param string $name
     * @param Interval $interval
     * @return mixed
     */
    private function assert(string $name, Interval $interval)
    {
        return self::$catalog->get($name)->assert($this, $interval);
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
        return $this->operate(Catalog::OPERATION_INTERVAL_UNION, $interval);
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
        return $this->operate(Catalog::OPERATION_INTERVAL_INTERSECTION, $interval);
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
        return $this->operate(Catalog::OPERATION_INTERVAL_EXCLUSION, $interval);
    }

    /**
     * Checks whether or not this interval overlaps another one
     *
     * @param Interval $interval
     * @return bool
     */
    public function overlaps(Interval $interval) : bool
    {
        return $this->assert(Catalog::RULE_INTERVAL_OVERLAPPING, $interval);
    }

    /**
     * Checks whether or not this interval includes entirely another one
     *
     * @param Interval $interval
     * @return bool
     */
    public function includes(Interval $interval) : bool
    {
        return $this->assert(Catalog::RULE_INTERVAL_INCLUSION, $interval);
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
    public function isNeighborBefore(Interval $interval) : bool
    {
        return $this->assert(Catalog::RULE_INTERVAL_NEIGHBORHOOD_BEFORE, $interval);
    }

    /**
     * @param Interval $interval
     * @return bool
     */
    public function isNeighborAfter(Interval $interval) : bool
    {
        return $this->assert(Catalog::RULE_INTERVAL_NEIGHBORHOOD_AFTER, $interval);
    }

    /**
     * @param Interval $interval
     * @return bool
     */
    public function starts(Interval $interval) : bool
    {
        return $this->assert(Catalog::RULE_INTERVAL_STARTING, $interval);
    }

    /**
     * @param Interval $interval
     * @return bool
     */
    public function ends(Interval $interval) : bool
    {
        return $this->assert(Catalog::RULE_INTERVAL_ENDING, $interval);
    }

    /**
     * @param Interval $interval
     * @return bool
     */
    public function equals(Interval $interval) : bool
    {
        return $this->assert(Catalog::RULE_INTERVAL_EQUALITY, $interval);
    }

    /**
     * @param Interval $interval
     * @return bool
     */
    public function isBefore(Interval $interval) : bool
    {
        return $this->assert(Catalog::RULE_INTERVAL_BEFORE, $interval);
    }

    /**
     * @param Interval $interval
     * @return bool
     */
    public function isAfter(Interval $interval) : bool
    {
        return $this->assert(Catalog::RULE_INTERVAL_AFTER, $interval);
    }

    /**
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
     * Loads the service catalog
     * @return Catalog
     */
    private static function loadCatalog(): Catalog
    {
        if (!self::$catalog) {
            self::$catalog = new Catalog();
        }

        return self::$catalog;
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
        $parser = self::loadCatalog()->get(Catalog::PARSER_INTERVAL);
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
