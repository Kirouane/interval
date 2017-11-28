<?php
declare(strict_types=1);

namespace Interval\Boundary;

/**
 * Class Integer
 * @package Interval\Boundary
 */
class Integer extends BoundaryAbstract
{
    /**
     * @var int
     */
    private $comparable;

    /**
     * @param BoundaryAbstract|Integer $comparable
     * @return int
     */
    public function compare(BoundaryAbstract $comparable): int
    {
        return $this->toComparable() <=> $comparable->toComparable();
    }

    /**
     * @return int|mixed
     */
    public function toComparable()
    {
        if ($this->comparable) {
            return $this->comparable;
        }

        if ($this->isClosed()) {
            return $this->comparable = $this->getValue();
        }

        $sign = $this->isLeft() ? 1 : -1;

        return $this->comparable = $this->getValue() + $sign * 1;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->applyBoundarySymbol($this->getValue());
    }
}
