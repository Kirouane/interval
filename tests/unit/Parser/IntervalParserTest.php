<?php
declare(strict_types=1);

namespace Interval\Parser;

require_once __DIR__ . '/../../../vendor/autoload.php';

use Interval\Boundary\DateTime;
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
            ['[2010-01-02, 2010-01-03]', new \DateTimeImmutable('2010-01-02'), new \DateTimeImmutable('2010-01-03')],
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
            self::assertInstanceOf(DateTime::class, $interval->getStart());
            self::assertInstanceOf(DateTime::class, $interval->getEnd());
            self::assertInstanceOf(\DateTimeInterface::class, $interval->getStart()->getValue());
            self::assertInstanceOf(\DateTimeInterface::class, $interval->getEnd()->getValue());
            $this->assertEquals($start, $interval->getStart()->getValue());
            $this->assertEquals($end, $interval->getEnd()->getValue());
        } else {
            $this->assertSame($start, $interval->getStart()->getValue());
            $this->assertSame($end, $interval->getEnd()->getValue());
        }

        $this->assertSame(true, $interval->getStart()->isLeft());
        $this->assertSame(true, $interval->getStart()->isClosed());

        $this->assertSame(true, $interval->getEnd()->isRight());
        $this->assertSame(true, $interval->getEnd()->isClosed());
    }

    public function parseExceptionProvider()
    {
        return [
            [''],
            ['invalid'],
            ['[]'],
            ['[,]'],
            ['[,'],
            [',]'],
            ['[1]'],
            ['[1 2]'],
            ['[1 2'],
            ['1 2]'],
            ['1,2)'],
            ['(1,2)'],
            ['(1,2'],
            ['[1,2'],
            ['1,2]'],
        ];
    }

    /**
     * @test
     * @expectedException \Exception
     * @dataProvider parseExceptionProvider
     * @param mixed $expression
     */
    public function parseException($expression)
    {
        $parser = new IntervalParser();
        $parser->parse($expression);
    }

    public function parseOpenProvider()
    {
        return [
            [']1,3[', 1, 3, true, true],
        ];
    }

    /**
     * @test
     * @dataProvider parseOpenProvider
     * @param mixed $expression
     * @param mixed $start
     * @param mixed $end
     * @param mixed $isOpenStart
     * @param mixed $isOpenEnd
     */
    public function parseOpen($expression, $start, $end, $isOpenStart, $isOpenEnd)
    {
        $parser = new IntervalParser();

        /** @var Interval $interval */
        $interval = $parser->parse($expression);
        $this->assertInstanceOf(Interval::class, $interval);
        if ($start instanceof \DateTimeInterface) {
            self::assertInstanceOf(\DateTimeInterface::class, $interval->getStart()->getValue());
            $this->assertEquals($start, $interval->getStart()->getValue());
            $this->assertEquals($end, $interval->getEnd()->getValue());
        } else {
            $this->assertSame($start, $interval->getStart()->getValue());
            $this->assertSame($end, $interval->getEnd()->getValue());
        }

        $this->assertSame(true, $interval->getStart()->isLeft());
        $this->assertSame($isOpenStart, $interval->getStart()->isOpen());

        $this->assertSame(true, $interval->getEnd()->isRight());
        $this->assertSame($isOpenEnd, $interval->getEnd()->isOpen());
    }
}
