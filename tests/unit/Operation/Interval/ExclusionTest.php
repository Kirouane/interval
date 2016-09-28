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
            ]
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
        $first->shouldReceive('getStart')->andReturn($firstStart);
        $first->shouldReceive('getEnd')->andReturn($firstEnd);

        $second = m::mock('\Interval\Interval');
        $second->shouldReceive('getStart')->andReturn($secondStart);
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
        $first->shouldReceive('getStart')->andReturn(new \DateTime('2016-01-01 10:' . $firstStart));
        $first->shouldReceive('getEnd')->andReturn(new \DateTime('2016-01-01 10:' . $firstEnd));

        $second = m::mock('\Interval\Interval');
        $second->shouldReceive('getStart')->andReturn(new \DateTime('2016-01-01 10:' . $secondStart));
        $second->shouldReceive('getEnd')->andReturn(new \DateTime('2016-01-01 10:' . $secondEnd));

        $union = new Exclusion();
        $intervals = $union->compute($first, $second);
        $this->assertInstanceOf(Intervals::class, $intervals);

        $data = [];
        /** @var Interval $interval */
        foreach ($intervals as $interval) {
            $data[] = [(int)$interval->getStart()->format('i'), (int)$interval->getEnd()->format('i')];
        }

        $this->assertSame($expected, $data);
    }
}