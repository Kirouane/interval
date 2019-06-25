<?php
declare(strict_types=1);

namespace Interval;

require_once __DIR__ . '/../../vendor/autoload.php';
use Mockery as m;

class IntervalTest extends \PHPUnit\Framework\TestCase
{
    public function tearDown(): void
    {
        m::close();
    }

    public function constructorShouldThrowExceptionProvider()
    {
        return [
            [2, 1],
            ['string', 'string'],
        ];
    }

    /**
     * @test
     * @expectedException \Exception
     * @dataProvider constructorShouldThrowExceptionProvider
     * @param mixed $start
     * @param mixed $end
     * @return Interval
     */
    public function constructorShouldThrowException($start, $end)
    {
        return new \Interval\Interval($start, $end);
    }

    /**
     * @test
     */
    public function union()
    {
        $interval = new \Interval\Interval(1, 2);
        $this->assertInstanceOf(Intervals::class, $interval->union(new \Interval\Interval(2, 3)));
    }

    /**
     * @test
     */
    public function intersection()
    {
        $interval = new \Interval\Interval(1, 4);
        $this->assertInstanceOf(Interval::class, $interval->intersect(new \Interval\Interval(3, 5)));
    }

    /**
     * @test
     */
    public function exclusion()
    {
        $interval = new \Interval\Interval(1, 4);
        $this->assertInstanceOf(Intervals::class, $interval->exclude(new \Interval\Interval(3, 5)));
    }

    /**
     * @test
     */
    public function includes()
    {
        $interval = new \Interval\Interval(1, 4);
        $this->assertInternalType('bool', $interval->includes(new \Interval\Interval(3, 5)));
    }

    /**
     * @test
     */
    public function overlaps()
    {
        $interval = new \Interval\Interval(1, 4);
        $this->assertInternalType('bool', $interval->overlaps(new \Interval\Interval(3, 5)));
    }

    /**
     * @test
     */
    public function isNeighborBeforeOf()
    {
        $interval = new \Interval\Interval(1, 4);
        $this->assertInternalType('bool', $interval->isNeighborBefore(new \Interval\Interval(3, 5)));
    }

    /**
     * @test
     */
    public function isNeighborAfterOf()
    {
        $interval = new \Interval\Interval(1, 4);
        $this->assertInternalType('bool', $interval->isNeighborAfter(new \Interval\Interval(3, 5)));
    }

    /**
     * @test
     */
    public function isBeforeOf()
    {
        $interval = new \Interval\Interval(1, 4);
        $this->assertInternalType('bool', $interval->isBefore(new \Interval\Interval(3, 5)));
    }

    /**
     * @test
     */
    public function isAfter()
    {
        $interval = new \Interval\Interval(1, 4);
        $this->assertInternalType('bool', $interval->isAfter(new \Interval\Interval(3, 5)));
    }

    /**
     * @test
     */
    public function starts()
    {
        $interval = new \Interval\Interval(1, 4);
        $this->assertInternalType('bool', $interval->starts(new \Interval\Interval(3, 5)));
    }

    /**
     * @test
     */
    public function ends()
    {
        $interval = new \Interval\Interval(1, 4);
        $this->assertInternalType('bool', $interval->ends(new \Interval\Interval(3, 5)));
    }

    /**
     * @test
     */
    public function equals()
    {
        $interval = new \Interval\Interval(1, 4);
        $this->assertInternalType('bool', $interval->equals(new \Interval\Interval(3, 5)));
    }

    public function toStringProvider()
    {
        return [
            [1, 2, '[1, 2]'],
            [1.2, 2.2, '[1.2, 2.2]'],
            [new \DateTime('2016-01-01'), new \DateTime('2016-01-02'), '[2016-01-01T00:00:00+00:00, 2016-01-02T00:00:00+00:00]'],
            [-INF, +INF, ']-∞, +∞['],
            [-INF, 1, ']-∞, 1]'],
            [1, +INF, '[1, +∞['],
            [null, 1, ']-∞, 1]'],
            [1, null, '[1, +∞['],
        ];
    }

    /**
     * @test
     * @dataProvider toStringProvider
     * @param mixed $start
     * @param mixed $end
     * @param mixed $expected
     */
    public function toStringTest($start, $end, $expected)
    {
        $interval = new \Interval\Interval($start, $end);
        $this->assertSame($expected, $interval->__toString());
    }

    public function toComparableProvider()
    {
        return [
            [1, 1],
            [1.1, 1.1],
            ['1', '1'],
            ['a', 'a'],
            [true, true],
            [false, false],
            [new \DateTime('2016-01-01 10:00:00'), 1451642400],
            [INF, INF],
            [-INF, -INF],
        ];
    }

    /**
     * @test
     * @dataProvider toComparableProvider
     * @param mixed $boundary
     * @param mixed $expected
     */
    public function toComparable($boundary, $expected)
    {
        $this->assertSame($expected, Interval::toComparable($boundary));
    }

    public function toComparableExceptionProvider()
    {
        return [
            [[]],
            [new \stdClass()],
            [null],
        ];
    }

    /**
     * @test
     * @dataProvider toComparableExceptionProvider
     * @expectedException UnexpectedValueException
     * @param mixed $boundary
     */
    public function toComparableException($boundary)
    {
        Interval::toComparable($boundary);
    }

    /**
     * @test
     */
    public function create()
    {
        $interval = Interval::create('[10, 15]');
        $this->assertSame(10, $interval->getStart()->getValue());
        $this->assertSame(15, $interval->getEnd()->getValue());
    }
}
