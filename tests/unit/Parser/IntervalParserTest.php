<?php
declare(strict_types=1);

namespace Interval\Parser;

require_once __DIR__ . '/../../../vendor/autoload.php';
use Interval\Interval;
use Mockery as m;

class IntervalParserTest extends \PHPUnit\Framework\TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function parseProvider()
    {
        return [
            ['[1,2]', 1, 2],
            ['[-INF,2]', -INF, 2],
            ['[-INF,+INF]', -INF, INF],
            ['[1.1,2.2]', 1.1, 2.2],
            ['[2010-01-02,2010-01-03]', new \DateTimeImmutable('2010-01-02'), new \DateTimeImmutable('2010-01-03')],
        ];
    }

    /**
     * @test
     * @dataProvider parseProvider
     * @param mixed $expression
     * @param mixed $start
     * @param mixed $end
     */
    public function parse($expression, $start, $end)
    {
        $parser = new IntervalParser();

        /** @var Interval $interval */
        $interval = $parser->parse($expression);
        $this->assertInstanceOf(Interval::class, $interval);
        if ($start instanceof \DateTimeInterface) {
            self::assertInstanceOf(\DateTimeInterface::class, $interval->getStart());
            $this->assertEquals($start, $interval->getStart());
            $this->assertEquals($end, $interval->getEnd());
        } else {
            $this->assertSame($start, $interval->getStart());
            $this->assertSame($end, $interval->getEnd());
        }
    }
}
