<?php
declare(strict_types=1);

namespace Interval\Parser;

require_once __DIR__ . '/../../../vendor/autoload.php';
use Interval\Intervals;
use Mockery as m;

class IntervalsParserTest extends \PHPUnit\Framework\TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function parseProvider()
    {
        return [
            [['[1,2]', '[2,3]'], [[1, 2], [2, 3]]],
            [['[1,2]'], [[1, 2]]],
            [[], []],
        ];
    }

    /**
     * @test
     * @dataProvider parseProvider
     * @param mixed $expressions
     * @param mixed $expected
     */
    public function parse($expressions, $expected)
    {
        $parser = new IntervalsParser(new IntervalParser());

        /** @var Intervals $intervals */
        $intervals = $parser->parse($expressions);
        $this->assertInstanceOf(Intervals::class, $intervals);
        $this->assertCount(count($expected), $intervals);
        foreach ($intervals as $i => $interval) {
            self::assertSame($expected[$i][0], $interval->getStart()->getValue());
            self::assertSame($expected[$i][1], $interval->getEnd()->getValue());
        }
    }
}
