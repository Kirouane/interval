<?php
declare(strict_types=1);

namespace Interval\Parser;

use Interval\Boundary\DateTime;
use Interval\Boundary\Infinity;
use Interval\Boundary\Integer;
use Interval\Boundary\Real;
use Interval\Interval;

/**
 * Class Parser
 * @package Interval
 */
class IntervalParser
{
    private const BOUNDARIES = 2;
    private const LEFT       = ']';
    private const RIGHT      = '[';

    /**
     * Create a Interval from an expression
     * @param string $expression
     * @return Interval
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     * @throws \RangeException
     * @throws \ErrorException
     */
    public function parse(string $expression): Interval
    {
        $parse = \explode(',', $expression);
        if (self::BOUNDARIES !== count($parse)) {
            throw new \ErrorException('Parse interval expression');
        }

        $startTerm = \reset($parse);
        $endTerm   = \end($parse);

        $startTerm = $this->parseStartTerm($startTerm);
        $endTerm   = $this->parseEndTerm($endTerm);

        return new Interval(
            $startTerm,
            $endTerm
        );
    }

    /**
     * Parses the start term
     * @param string $startTerm
     * @return float|int|string
     * @throws \InvalidArgumentException
     * @throws \ErrorException
     */
    private function parseStartTerm(string $startTerm)
    {
        if ('' === $startTerm) {
            throw new \ErrorException('Parse interval expression');
        }

        $startInclusion = $startTerm[0];

        if (!\in_array($startInclusion, [self::LEFT, self::RIGHT], true)) {
            throw new \ErrorException('Parse interval expression');
        }

        $isOpen         = $startInclusion === self::LEFT;
        $startValue     = \substr($startTerm, 1);
        return $this->parseValue($startValue, true, $isOpen);
    }

    /**
     * Pareses the end term
     * @param string $endTerm
     * @return float|int|string
     * @throws \InvalidArgumentException
     * @throws \ErrorException
     */
    private function parseEndTerm(string $endTerm)
    {
        if ('' === $endTerm) {
            throw new \ErrorException('Parse interval expression');
        }

        $endInclusion = \substr($endTerm, -1);

        if (!\in_array($endInclusion, [self::LEFT, self::RIGHT], true)) {
            throw new \ErrorException('Parse interval expression');
        }

        $isOpen       = $endInclusion === self::RIGHT;
        $endValue     = \substr($endTerm, 0, -1);
        return $this->parseValue($endValue, false, $isOpen);
    }

    /**
     * Cast a value to its expected type
     * @param mixed $value
     * @param bool $isLeft
     * @param bool $isOpen
     * @return float|int|string
     * @throws \InvalidArgumentException
     */
    private function parseValue($value, bool $isLeft, bool $isOpen)
    {
        if ($this->isInt($value)) {
            return new Integer((int)$value, $isLeft, $isOpen);
        }

        if ($this->isInfinity($value)) {
            $value = '-INF' === $value ? -\INF : \INF;
            return new Infinity($value, $isLeft, $isOpen);
        }

        if ($this->isFloat($value)) {
            return new Real((float)$value, $isLeft, $isOpen);
        }

        if ($this->isDate($value)) {
            $value = \DateTimeImmutable::createFromFormat('U', (string)\strtotime($value));
            $value = $value->setTimezone(new \DateTimeZone(\date_default_timezone_get()));
            return new DateTime($value, $isLeft, $isOpen);
        }

        throw new \InvalidArgumentException('Unexpected $value type');
    }

    /**
     * Returns true if the value is an integer
     * @param $value
     * @return bool
     */
    private function isInt(string $value): bool
    {
        return \is_numeric($value) && (float)\round($value, 0) === (float)$value;
    }

    /**
     * Returns true if the value is infinite
     * @param string $value
     * @return bool
     */
    private function isInfinity(string $value): bool
    {
        return false !== \strpos($value, 'INF');
    }

    /**
     * Returns true if the value is a float
     * @param string $value
     * @return bool
     */
    private function isFloat(string $value): bool
    {
        return \is_numeric($value) && !$this->isInt($value);
    }

    /**
     * @param $value
     * @return bool
     */
    private function isDate($value): bool
    {
        return true === (bool)\strtotime($value);
    }
}
