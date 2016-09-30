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
     * @return string Magic method used to print all intervals belong to this \Traversable
     */
    public function __toString()
    {
        $str  = '{';
        $str  .= implode(', ', $this->getArrayCopy());
        $str .= '}';
        return $str;
    }

}