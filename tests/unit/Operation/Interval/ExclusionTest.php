<?php
declare(strict_types=1);
namespace UnitTest\Interval\Operation\Interval;
use Interval\Interval;
use Interval\Intervals;
use Interval\Operation\Interval\Exclusion;
use Interval\Operation\Interval\Intersection;
use Interval\Operation\Interval\Union;
use \Mockery as m;
class ExclusionTest extends \PHPUnit\Framework\TestCase
{

    public function tearDown()
    {
        m::close();
    }

    public function computeProvider()
    {
        return [
            [
                10, 20, //                                    ██████████████████
                30, 40, //                                                          ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                [[10, 20]], //                                ██████████████████
            ],
            [
                10, 20, //                                    ██████████████████
                20, 40, //                                                      ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                [[10, 20]], //                                ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓
            ],
            [
                10, 30, //                                    ███████████████████████
                20, 40, //                                                      ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                [[10, 20]], //                                ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓
            ],
            [
                10, 30, //                                    ███████████████████████
                20, 30, //                                                      ▒▒▒▒▒
                [[10, 20]], //                                ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓
            ],
            [
                10, 50, //                                    █████████████████████████████████████████████████
                20, 40, //                                                      ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                [[10, 20], [40, 50]], //                      ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓                    ▓▓▓▓▓▓▓▓▓▓▓
            ],
            [
                10, 40, //                                    ███████████████████
                10, 40, //                                    ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                 [] , //                                      No intervals
            ],
            [
                30, 40, //                                    ██████████████████
                10, 20, //                ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                [[30, 40]], //            ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓
            ],
            [
                30, 40, //                                    ██████████████████
                10, 30, //                ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                [[30,  40]], //           ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓
            ],
            [
                30, 40, //                                    ██████████████████
                10, 35, //                ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                [[35,  40]], //                                   ▓▓▓▓▓▓▓▓▓▓▓▓▓▓
            ],
            [
                30, 40, //                                    ██████████████████
                30, 35, //                                    ▒▒▒▒
                [[35,  40]], //                                   ▓▓▓▓▓▓▓▓▓▓▓▓▓▓
            ],
            [
                30, 40, //                                    ██████████████████
                10, 50, //                ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                [], //                                        No intervals
            ],

            //with infinity
            [
                -INF, 20, //                                 ∞██████████████████
                30, 40, //                                                          ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                [[-INF, 20]], //                             ∞██████████████████
            ],
            [
                -INF, +INF, //                                 ∞██████████████████████████████████████████████████████∞
                30, 40, //                                                          ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                [[-INF, 30], [40, +INF]], //                   ∞████████████████████                ██████████████████∞
            ],
        ];
    }

    /**
     * @dataProvider computeProvider
     * @param $firstStart
     * @param $firstEnd
     * @param $secondStart
     * @param $secondEnd
     * @test
     */
    public function compute($firstStart, $firstEnd, $secondStart, $secondEnd, $expected)
    {
        $first = m::mock('\Interval\Interval');
        $first->shouldReceive('getComparableStart')->andReturn($firstStart);
        $first->shouldReceive('getStart')->andReturn($firstStart);
        $first->shouldReceive('getComparableEnd')->andReturn($firstEnd);
        $first->shouldReceive('getEnd')->andReturn($firstEnd);

        $second = m::mock('\Interval\Interval');
        $second->shouldReceive('getComparableStart')->andReturn($secondStart);
        $second->shouldReceive('getStart')->andReturn($secondStart);
        $second->shouldReceive('getComparableEnd')->andReturn($secondEnd);
        $second->shouldReceive('getEnd')->andReturn($secondEnd);

        $union = new Exclusion();
        $intervals = $union->compute($first, $second);
        $this->assertInstanceOf(Intervals::class, $intervals);

        $data = [];
        /** @var Interval $interval */
        foreach ($intervals as $interval) {
            $data[] = [$interval->getStart(), $interval->getEnd()];
        }

        $this->assertSame($expected, $data);
    }

    /**
     * @dataProvider computeProvider
     * @param $firstStart
     * @param $firstEnd
     * @param $secondStart
     * @param $secondEnd
     * @test
     */
    public function computeWithDateTimeAsEndPoint($firstStart, $firstEnd, $secondStart, $secondEnd, $expected)
    {

        $first = m::mock('\Interval\Interval');
        $first->shouldReceive('getComparableStart')->andReturn(is_infinite($firstStart) ? $firstStart : (new \DateTime('2016-01-01 10:' . $firstStart))->getTimestamp());
        $first->shouldReceive('getStart')->andReturn(is_infinite($firstStart) ? $firstStart : new \DateTime('2016-01-01 10:' . $firstStart));
        $first->shouldReceive('getComparableEnd')->andReturn(is_infinite($firstEnd) ? $firstEnd : (new \DateTime('2016-01-01 10:' . $firstEnd))->getTimestamp());
        $first->shouldReceive('getEnd')->andReturn(is_infinite($firstEnd) ? $firstEnd : new \DateTime('2016-01-01 10:' . $firstEnd));

        $second = m::mock('\Interval\Interval');
        $second->shouldReceive('getComparableStart')->andReturn(is_infinite($secondStart) ? $secondStart : (new \DateTime('2016-01-01 10:' . $secondStart))->getTimestamp());
        $second->shouldReceive('getStart')->andReturn(is_infinite($secondStart) ? $secondStart : new \DateTime('2016-01-01 10:' . $secondStart));
        $second->shouldReceive('getComparableEnd')->andReturn(is_infinite($secondEnd) ? $secondEnd : (new \DateTime('2016-01-01 10:' . $secondEnd))->getTimestamp());
        $second->shouldReceive('getEnd')->andReturn(is_infinite($secondEnd) ? $secondEnd : new \DateTime('2016-01-01 10:' . $secondEnd));

        $union = new Exclusion();
        $intervals = $union->compute($first, $second);
        $this->assertInstanceOf(Intervals::class, $intervals);

        $data = [];
        /** @var Interval $interval */
        foreach ($intervals as $interval) {
            $data[] = [is_object($interval->getStart()) ? (int)$interval->getStart()->format('i') : $interval->getStart(), is_object($interval->getEnd()) ? (int)$interval->getEnd()->format('i') : $interval->getEnd()];
        }

        $this->assertSame($expected, $data);
    }
}