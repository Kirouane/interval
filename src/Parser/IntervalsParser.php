<?php
declare(strict_types=1);
namespace Interval\Parser;

use Interval\Intervals;

/**
 * Class Parser
 * @package Interval
 */
class IntervalsParser
{

    /**
     * @var IntervalParser
     */
    private $intervalParser;

    /**
     * IntervalsParser constructor.
     * @param IntervalParser $intervalParser
     */
    public function __construct(IntervalParser $intervalParser)
    {
        $this->intervalParser = $intervalParser;
    }

    /**
     * @param array $expressions
     * @return Intervals
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     * @throws \RangeException
     * @throws \ErrorException
     */
    public function parse(array $expressions) : Intervals
    {
        $intervals = [];
        foreach ($expressions as $expression) {
            $intervals[] = $this->intervalParser->parse($expression);
        }

        return new Intervals($intervals);
    }
}
