<?php
declare(strict_types=1);
namespace Interval\Parser;

require_once __DIR__ . '/../../../vendor/autoload.php';
use Interval\Interval;
use Interval\Intervals;
use Interval\Parser\IntervalParser;
use \Mockery as m;

class IntervalsPareserTest extends \PHPUnit\Framework\TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function parseProvider()
    {
        return [
            [['[1,2]', '[2,3]'], [[1,2], [2,3]]],
            [['[1,2]'], [[1,2]]],
            [[], []]
        ];
    }

    /**
     * @test
     * @dataProvider parseProvider
     */
    public function parse($expressions, $expected)
    {
        $parser = new IntervalsParser(new IntervalParser());

        /** @var Intervals $intervals */
        $intervals = $parser->parse($expressions);
        $this->assertInstanceOf(Intervals::class, $intervals);
        $this->assertCount(count($expected), $intervals);
        foreach ($intervals as $i => $interval) {
            self::assertSame($expected[$i][0], $interval->getStart());
            self::assertSame($expected[$i][1], $interval->getEnd());
        }
    }
}
