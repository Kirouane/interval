<?php
declare(strict_types=1);

namespace Interval;

/**
 * Class Intervals
 * @package Interval
 */
class Intervals extends \ArrayObject
{
    /**
     * @var \Interval\Catalog
     */
    private static $catalog;

    /**
     * @return string Magic method used to print all intervals belong to this \Traversable
     */
    public function __toString()
    {
        $str  = '{';
        $str .= \implode(', ', $this->getArrayCopy());
        $str .= '}';
        return $str;
    }

    /**
     * Intervals constructor.
     * @param array $input
     */
    public function __construct(array $input)
    {
        parent::__construct($input);
        self::loadCatalog();
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
     * Exp Intervals::create(['[10, 26]', '[11, 13]')
     * @param array|string $expressions
     * @return Intervals
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     * @throws \RangeException
     * @throws \ErrorException
     */
    public static function create(array $expressions) : Intervals
    {
        /** @var \Interval\Parser\IntervalsParser $parser */
        $parser = self::loadCatalog()->get(Catalog::PARSER_INTERVALS);
        return $parser->parse($expressions);
    }

    /**
     * @param callable $callable
     * @return Intervals
     */
    public function filter(callable $callable): Intervals
    {
        return new Intervals(\array_values(\array_filter($this->getArrayCopy(), $callable)));
    }

    /**
     * @param callable $callable
     * @return Intervals
     */
    public function map(callable $callable): Intervals
    {
        return new Intervals(\array_map($callable, $this->getArrayCopy()));
    }

    /**
     * @param callable $callable
     * @return Intervals
     */
    public function sort(callable $callable): Intervals
    {
        $arrayIntervals = $this->getArrayCopy();
        \usort($arrayIntervals, $callable);
        return new Intervals($arrayIntervals);
    }
}
