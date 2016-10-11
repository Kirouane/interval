<?php
declare(strict_types=1);
namespace Interval;

/**
 * Class Parser
 * @package Interval
 */
class Parser
{
    CONST L = ']';
    CONST R = '[';

    /**
     * Create a Interval from an expression
     * @param string $expression
     * @return Interval
     * @throws \UnexpectedValueException
     * @throws \RangeException
     * @throws \ErrorException
     */
    public function parse(string $expression) : Interval
    {
        $parse = explode(',', $expression);
        if (2 !== count($parse)) {
            throw new \ErrorException('Parse interval expression');
        }

        $startTerm = reset($parse);
        $endTerm = end($parse);

        $startTerm = $this->parseStartTerm($startTerm);
        $endTerm = $this->parseEndTerm($endTerm);

        $interval = new Interval(reset($startTerm), reset($endTerm), end($startTerm), end($endTerm));

        return $interval;
    }

    /**
     * Parses the start term
     * @param string $startTerm
     * @return array
     * @throws \ErrorException
     */
    private function parseStartTerm(string $startTerm) : array
    {
        $startInclusion = $startTerm[0];

        if (!in_array($startInclusion, [self::L, self::R], true)) {
            throw new \ErrorException('Parse interval expression');
        }

        $startInclusion = $startInclusion === self::R;
        $startValue     = substr($startTerm, 1);
        $startValue     = $this->parseValue($startValue);

        return [$startValue, $startInclusion];
    }

    /**
     * Pareses the end term
     * @param string $endTerm
     * @return array
     * @throws \ErrorException
     */
    private function parseEndTerm(string $endTerm) : array
    {
        $endInclusion = substr($endTerm, -1);

        if (!in_array($endInclusion, [self::L, self::R], true)) {
            throw new \ErrorException('Parse interval expression');
        }

        $endInclusion = $endInclusion === self::L;
        $endValue     = substr($endTerm, 0, -1);
        $endValue     = $this->parseValue($endValue);

        return [$endValue, $endInclusion];
    }

    /**
     * Cast a value to its expected type
     * @param mixed $value
     * @return float|int|string
     */
    private function parseValue($value)
    {
        if ($this->isInt($value)) {
            $value = (int)$value;
        } elseif ($this->isInfinity($value) || $this->isFloat($value)) {
            $value = (float)$value;
        }

        return $value;
    }

    /**
     * Returns true if the value is an integer
     * @param $value
     * @return bool
     */
    private function isInt(string $value) : bool
    {
        return is_numeric($value) && $value == (int)$value;
    }

    /**
     * Returns true if the value is infinite
     * @param string $value
     * @return bool
     */
    private function isInfinity(string $value) : bool
    {
        return false !== strpos($value, 'INF');
    }

    /**
     * Returns true if the value is a float
     * @param string $value
     * @return bool
     */
    private function isFloat(string $value) : bool
    {
        return is_numeric($value) && $value == (float)$value;
    }
}