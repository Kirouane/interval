<?php
declare(strict_types=1);

namespace Interval\Boundary;

/**
 * Class BoundaryAbstract
 * @package Interval\Boundary
 */
abstract class BoundaryAbstract
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * @var bool
     */
    private $left;

    /**
     * @var boolean
     */
    private $open;

    /**
     * Boundary constructor.
     * @param mixed $value
     * @param bool $isLeft
     * @param bool $isOpen
     */
    public function __construct($value, bool $isLeft, bool $isOpen = false)
    {
        $this->value = $value;
        $this->left  = $isLeft;
        $this->open  = $isOpen;
    }

    /**
     * @param BoundaryAbstract $comparable
     * @return int
     */
    abstract public function compare(BoundaryAbstract $comparable) : int;

    /**
     * @return mixed
     */
    abstract public function toComparable();

    /**
     * @param BoundaryAbstract $comparable
     * @return bool
     */
    public function equalTo(BoundaryAbstract $comparable): bool
    {
        return $this->compare($comparable) === 0;
    }

    /**
     * @param BoundaryAbstract $comparable
     * @return bool
     */
    public function greaterThan(BoundaryAbstract $comparable): bool
    {
        return $this->compare($comparable) === 1;
    }

    /**
     * @param BoundaryAbstract $comparable
     * @return bool
     */
    public function lessThan(BoundaryAbstract $comparable): bool
    {
        return $this->compare($comparable) === -1;
    }

    /**
     * @param BoundaryAbstract $comparable
     * @return bool
     */
    public function greaterThanOrEqualTo(BoundaryAbstract $comparable): bool
    {
        return \in_array($this->compare($comparable), [0, 1], true);
    }

    /**
     * @param BoundaryAbstract $comparable
     * @return bool
     */
    public function lessThanOrEqualTo(BoundaryAbstract $comparable): bool
    {
        return \in_array($this->compare($comparable), [0, -1], true);
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return bool
     */
    public function isLeft(): bool
    {
        return $this->left;
    }

    /**
     * @return bool
     */
    public function isRight(): bool
    {
        return !$this->isLeft();
    }

    /**
     * @return bool
     */
    public function isOpen(): bool
    {
        return $this->open;
    }

    /**
     * @return bool
     */
    public function isClosed(): bool
    {
        return !$this->isOpen();
    }

    /**
     * @param $string
     * @return string
     */
    protected function applyBoundarySymbol($string): string
    {
        if ($this->isLeft()) {
            return ($this->isOpen() ? ']' : '[') . $string;
        }

        return $string . ($this->isOpen() ? '[' : ']');
    }
}
