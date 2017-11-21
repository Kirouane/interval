<?php
declare(strict_types=1);
namespace Interval;

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
     * @var mixed
     */
    private $start;

    /**
     * @var bool
     */
    private $startIncluded;

    /**
     * @var mixed
     */
    private $end;

    /**
     * @var bool
     */
    private $endIncluded;

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
     * @param bool $startIncluded
     * @param bool $endIncluded
     * @throws \RangeException
     * @throws \UnexpectedValueException
     */
    public function __construct($start, $end, bool $startIncluded = true, bool $endIncluded = true)
    {
        self::loadCatalog();

        $this->start = $start;
        $this->startIncluded = $startIncluded;
        $this->endIncluded   = $endIncluded;
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
        if ($this->comparableStart === $this->comparableEnd) {
            return $this->startIncluded && $this->endIncluded;
        } else {
            return $this->comparableStart < $this->comparableEnd;
        }
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
        /** @var \Interval\Operation\Interval\Union $operation */
        $operation = self::$catalog->get(Catalog::OPERATION_INTERVAL_UNION);
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
        /** @var \Interval\Operation\Interval\Exclusion $operation */
        $operation = self::$catalog->get(Catalog::OPERATION_INTERVAL_EXCLUSION);
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
        /** @var \Interval\Operation\Interval\Intersection $operation */
        $operation = self::$catalog->get(Catalog::OPERATION_INTERVAL_INTERSECTION);
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
        /** @var \Interval\Rule\Interval\Overlapping $asserter */
        $asserter = self::$catalog->get(Catalog::RULE_INTERVAL_OVERLAPPING);
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
        /** @var \Interval\Rule\Interval\Inclusion $asserter */
        $asserter = self::$catalog->get(Catalog::RULE_INTERVAL_INCLUSION);
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
        /** @var \Interval\Rule\Interval\Neighborhood $asserter */
        $asserter = self::$catalog->get(Catalog::RULE_INTERVAL_NEIGHBORHOOD);
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

        if (is_numeric($start)) {
            $start = is_infinite((float)$start) ? '-∞' : $start;
        }

        if (is_numeric($end)) {
            $end = is_infinite((float)$end) ? '+∞' : $end;
        }

        $startIncluded = $this->startIncluded ? '[' : ']';
        $endIncluded = $this->endIncluded ? ']' : '[';

        return $startIncluded . $start . ', ' . $end  . $endIncluded;
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

    /**
     * Loads the service catalog
     * @return Catalog
     */
    private static function loadCatalog()
    {
        if (!self::$catalog) {
            self::$catalog = new Catalog();
        }

        return self::$catalog;
    }

    /**
     * @return boolean
     */
    public function isStartIncluded()
    {
        return $this->startIncluded;
    }

    /**
     * @return boolean
     */
    public function isEndIncluded()
    {
        return $this->endIncluded;
    }

    /**
     * Creates a new Interval from expression
     * Exp Interval::create('[10, 26[')
     * @param string $expression
     * @return Interval
     * @throws \UnexpectedValueException
     * @throws \RangeException
     * @throws \ErrorException
     */
    public static function create(string $expression) : Interval
    {
        /** @var \Interval\Parser $asserter */
        $asserter = self::loadCatalog()->get(Catalog::PARSER);
        return $asserter->parse($expression);
    }
}
