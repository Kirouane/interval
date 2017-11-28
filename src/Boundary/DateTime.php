<?php
declare(strict_types=1);

namespace Interval\Boundary;

/**
 * Class DateTime
 * @package Interval\Boundary
 */
class DateTime extends BoundaryAbstract
{
    /**
     * @var int
     */
    private $comparable;

    /**
     * @param BoundaryAbstract|DateTime $comparable
     * @return int
     */
    public function compare(BoundaryAbstract $comparable): int
    {
        return $this->toComparable() <=> $comparable->toComparable();
    }

    /**
     * @return int
     */
    public function toComparable(): int
    {
        if ($this->comparable) {
            return $this->comparable;
        }

        $value = $this->getValue()->getTimestamp();

        if ($this->isClosed()) {
            return $this->comparable = $value;
        }

        $sign = $this->isLeft() ? 1 : -1;

        return $this->comparable = $value + $sign * 1;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->applyBoundarySymbol($this->getValue()->format(\DateTime::RFC3339));
    }
}
