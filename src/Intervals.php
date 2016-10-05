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
        $str  .= implode(', ', $this->getArrayCopy());
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
     * Excludes this interval from another one. Exp
     *
     *      |________________________________________________________________________________|
     *
     *             -
     *                  |_________________|                   |_________________|
     *
     *          =
     *      |___________|                 |___________________|                  |____________|
     *
     * @param Intervals $intervals
     * @return Intervals
     */
    public function exclude(Intervals $intervals) : Intervals
    {
        /** @var \Interval\Operation\Intervals\Exclusion $operation */
        $operation = self::$catalog->get(Catalog::OPERATION_INTERVALS_EXCLUSION);
        return $operation($this, $intervals);
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

}