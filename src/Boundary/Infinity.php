<?php
declare(strict_types=1);

namespace Interval\Boundary;

/**
 * Class Infinity
 * @package Interval\Boundary
 */
class Infinity extends BoundaryAbstract
{
    /**
     * @var int
     */
    private $comparable;

    /**
     * @param BoundaryAbstract $comparable
     * @return int
     */
    public function compare(BoundaryAbstract $comparable) : int
    {
        return $this->toComparable() <=> $comparable->toComparable();
    }

    /**
     * @return float
     */
    public function toComparable(): float
    {
        if ($this->comparable) {
            return $this->comparable;
        }

        return $this->comparable = $this->getValue();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $string = ($this->getValue() < 0 ? '-' : '+') . '∞';
        return $this->applyBoundarySymbol($string);
    }
}
