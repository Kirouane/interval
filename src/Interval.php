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
     * @throws \UnexpectedValueException
     * @throws \RangeException
     */
    public function __construct($start, $end)
    {
        self::loadCatalog();

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
    private function isConsistent(): bool
    {
        return $this->comparableStart < $this->comparableEnd;
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
    public function isNeighborOf(Interval $interval) : bool
    {
        return $this->assert(Catalog::RULE_INTERVAL_NEIGHBORHOOD, $interval);
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

        if (\is_numeric($start)) {
            $start = \is_infinite((float)$start) ? '-∞' : $start;
        }

        if (\is_numeric($end)) {
            $end = \is_infinite((float)$end) ? '+∞' : $end;
        }

        return '['  . $start . ', ' . $end  . ']';
    }

    /**
     * Convert an endpoint to comparable
     * @param mixed $endpoint
     * @return mixed
     * @throws \UnexpectedValueException
     */
    public static function toComparable($endpoint)
    {
        $isInternallyType = \is_numeric($endpoint) || \is_bool($endpoint) || \is_string($endpoint);

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
        /** @var \Interval\Parser\IntervalParser $asserter */
        $asserter = self::loadCatalog()->get(Catalog::PARSER_INTERVAL);
        return $asserter->parse($expression);
    }
}
