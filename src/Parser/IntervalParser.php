<?php
declare(strict_types=1);

namespace Interval\Parser;

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

        return new Interval(\reset($startTerm), \reset($endTerm));
    }

    /**
     * Parses the start term
     * @param string $startTerm
     * @return array
     * @throws \InvalidArgumentException
     * @throws \ErrorException
     */
    private function parseStartTerm(string $startTerm): array
    {
        $startInclusion = $startTerm[0];

        if (!\in_array($startInclusion, [self::LEFT, self::RIGHT], true)) {
            throw new \ErrorException('Parse interval expression');
        }

        $startInclusion = $startInclusion === self::RIGHT;
        $startValue     = \substr($startTerm, 1);
        $startValue     = $this->parseValue($startValue);

        return [$startValue, $startInclusion];
    }

    /**
     * Pareses the end term
     * @param string $endTerm
     * @return array
     * @throws \InvalidArgumentException
     * @throws \ErrorException
     */
    private function parseEndTerm(string $endTerm): array
    {
        $endInclusion = \substr($endTerm, -1);

        if (!\in_array($endInclusion, [self::LEFT, self::RIGHT], true)) {
            throw new \ErrorException('Parse interval expression');
        }

        $endInclusion = $endInclusion === self::LEFT;
        $endValue     = \substr($endTerm, 0, -1);
        $endValue     = $this->parseValue($endValue);

        return [$endValue, $endInclusion];
    }

    /**
     * Cast a value to its expected type
     * @param mixed $value
     * @return float|int|string
     * @throws \InvalidArgumentException
     */
    private function parseValue($value)
    {
        if ($this->isInt($value)) {
            $value = (int)$value;
        } elseif ($this->isInfinity($value)) {
            $value = '-INF' === $value ? -\INF : \INF;
        } elseif ($this->isFloat($value)) {
            $value = (float)$value;
        } elseif ($this->isDate($value)) {
            $value = \DateTimeImmutable::createFromFormat('U', (string)strtotime($value));
            $value = $value->setTimezone(new \DateTimeZone(date_default_timezone_get()));
        } else {
            throw new \InvalidArgumentException('Unexpected $value type');
        }

        return $value;
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
        return true === (bool)strtotime($value);
    }
}
