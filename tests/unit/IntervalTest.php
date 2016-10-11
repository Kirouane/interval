<?php
declare(strict_types=1);
namespace Interval;
require_once __DIR__ . '/../../vendor/autoload.php';
use Interval\Interval;
use Interval\Intervals;
use \Mockery as m;
class IntervalTest extends \PHPUnit\Framework\TestCase
{

    public function tearDown()
    {
        m::close();
    }

    public function constructorShouldThrowExceptionProvider()
    {
        return [
            [2, 1, true, true, true],
            [2, 2, false, false, true],
            [2, 2, true, false, true],
            [2, 2, false, true, true],

            [2, 2, true, true, false],
            [1, 2, true, true, false],
            [1, 2, false, true, false],
            [1, 2, true, false, false],
            [1, 2, false, false, false],

        ];
    }

    /**
     * @test
     * @dataProvider constructorShouldThrowExceptionProvider
     */
    public function constructorShouldThrowException($start, $end, $startIncluded, $endIncluded, $expectException)
    {
        if ($expectException) {
            $this->expectException('RangeException');
        }
        new \Interval\Interval($start, $end, $startIncluded, $endIncluded);
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
    public function isNeighborOf()
    {
        $interval = new \Interval\Interval(1, 4);
        $this->assertInternalType('bool', $interval->isNeighborOf(new \Interval\Interval(3, 5)));
    }

    public function toStringProvider()
    {
        return [
            [1, 2, true, true, '[1, 2]'],
            [new \DateTime('2016-01-01'), new \DateTime('2016-01-02'), true, true, '[2016-01-01T00:00:00+00:00, 2016-01-02T00:00:00+00:00]'],
            [1, 2, false, true, ']1, 2]'],
            [1, 2, true, false, '[1, 2['],
            [1, 2, false, false, ']1, 2['],
            [-INF, +INF, true, true, '[-∞, +∞]'],
            [-INF, 1, true, false, '[-∞, 1['],
            ['a', +INF, false, true, ']a, +∞]'],
            ['1', +INF, false, true, ']1, +∞]'],
        ];
    }

    /**
     * @test
     * @dataProvider toStringProvider
     */
    public function toStringTest($start, $end, $startIncluded, $endIncluded, $expected)
    {
        $interval = new \Interval\Interval($start, $end, $startIncluded, $endIncluded);
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
     */
    public function toComparable($endpoint, $expected)
    {
        $this->assertSame($expected, Interval::toComparable($endpoint));
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
     */
    public function toComparableException($endpoint)
    {
        Interval::toComparable($endpoint);
    }


    /**
     * @test
     */
    public function create()
    {
        $interval = Interval::create('[10, 15[');
        $this->assertSame(10, $interval->getStart());
        $this->assertSame(15, $interval->getEnd());
        $this->assertSame(true, $interval->isStartIncluded());
        $this->assertSame(false, $interval->isEndIncluded());
    }
}