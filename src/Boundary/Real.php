<?php
declare(strict_types=1);

namespace Interval\Boundary;

/**
 * Class Real
 * @package Interval\Boundary
 */
class Real extends BoundaryAbstract
{
    /**
     * @var float
     */
    private $comparable;

    /**
     * @param BoundaryAbstract|Real $comparable
     * @return int
     */
    public function compare(BoundaryAbstract $comparable): int
    {
        if ($this->isClosed() && $comparable->isClosed()) {
            return $this->getValue() <=> $comparable->getValue();
        }

        if ($this->getValue() !== $comparable->getValue()) {
            return $this->getValue() <=> $comparable->getValue();
        }

        return $this->toComparable() <=> $comparable->toComparable();
    }

    /**
     * @return float|mixed
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
