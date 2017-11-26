<?php

namespace Interval;

class Boundary
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
     * @var mixed
     */
    private $comparableValue;

    /**
     * Boundary constructor.
     * @param mixed $value
     * @param bool $isLeft
     * @param bool $isOpen
     */
    public function __construct($value, bool $isLeft, bool $isOpen)
    {
        $this->value = $value;
        $this->left  = $isLeft;
        $this->open  = $isOpen;
    }

    public function compare(Boundary $comparable)
    {
        if ($this->getValue() !== $comparable->getValue()) {
            return $this->getValue() <=> $comparable->getValue();
        }

        return $this->getComparableValue($comparable->toNumber()) <=> $comparable->getComparableValue($this->toNumber());
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
     * @return mixed
     * @param mixed $comparedValue
     */
    public function getComparableValue($comparedValue)
    {
        $value = $this->toNumber();

        if ($this->isClosed()) {
            return $value;
        }

        $sign = $this->isLeft() ? 1 : -1;

        if (is_float($value)) {
            $increment = abs($value - $comparedValue) / 2;
        } elseif (is_int($value)) {
            $increment = 1;
        }

        $value += $sign * $increment;

        return $value;
    }

    public function toNumber()
    {
        $value = $this->getValue();

        if (is_int($value) || is_float($value)) {
            return $value;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->getTimestamp();
        }

        throw new \InvalidArgumentException('Unexpected $value type');
    }
}
