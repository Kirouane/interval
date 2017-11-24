<?php
declare(strict_types=1);

namespace UnitTest\Interval\Operation\Intervals;

use Interval\Interval;
use Interval\Intervals;
use Interval\Operation\Intervals\Exclusion;

use Mockery as m;

class IntersectionTest extends \PHPUnit\Framework\TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function computeProvider()
    {
        return [
            [
                [
                    ['10:00', '12:00'],
                ],
                [],
                [
                    ['10:00', '12:00'],
                ],
            ],
            [
                [
                    ['10:00', '12:00'],
                ],
                [
                    ['09:00', '10:00'],
                    ['12:00', '14:00'],
                ],
                [
                    ['10:00', '12:00'],
                ],
            ],
            [
                [
                    ['10:00', '12:00'],
                ],
                [
                    ['10:30', '11:30'],
                ],
                [
                    ['10:00', '10:30'],
                    ['11:30', '12:00'],
                ],
            ],
            [
                [
                    ['10:00', '14:00'],
                ],
                [
                    ['11:00', '11:30'],
                    ['12:00', '12:30'],
                ],
                [
                    ['10:00', '11:00'],
                    ['11:30', '12:00'],
                    ['12:30', '14:00'],
                ],
            ],
            [
                [
                    ['10:00', '14:00'],
                    ['15:00', '16:00'],
                ],
                [
                    ['11:00', '11:30'],
                    ['12:00', '15:30'],
                ],
                [
                    ['10:00', '11:00'],
                    ['11:30', '12:00'],
                    ['15:30', '16:00'],
                ],
            ],
            [
                [
                    ['10:00', '13:00'],
                ],
                [
                    ['10:00', '12:00'],
                    ['12:00', '13:00'],
                ],
                [],
            ],
        ];
    }

    /**
     * @dataProvider computeProvider
     * @param $intervalsData
     * @param $intervalsToExcludeData
     * @param $expected
     * @test
     */
    public function compute(array $intervalsData, array $intervalsToExcludeData, array $expected)
    {
        $intervals = [];
        foreach ($intervalsData as $intervalData) {
            $intervals[] = new Interval(
                new \DateTime('2014-01-01 ' . $intervalData[0]),
                new \DateTime('2014-01-01 ' . $intervalData[1])
            );
        }

        $intervalsToExclude = [];
        foreach ($intervalsToExcludeData as $intervalToExcludeData) {
            $intervalsToExclude[] = new Interval(
                new \DateTime('2014-01-01 ' . $intervalToExcludeData[0]),
                new \DateTime('2014-01-01 ' . $intervalToExcludeData[1])
            );
        }

        $intervals          = new Intervals($intervals);
        $intervalsToExclude =  new Intervals($intervalsToExclude);
        $exclusion          = new Exclusion();
        $results            = $exclusion->compute($intervals, $intervalsToExclude);

        $this->assertCount(count($expected), $results);
        $count = count($results);
        for ($i = 0; $i < $count; $i++) {
            $interval = $results[$i];
            $this->assertInstanceOf(Interval::class, $interval);
            $this->assertSame($interval->getStart()->format('H:i'), $expected[$i][0]);
            $this->assertSame($interval->getEnd()->format('H:i'), $expected[$i][1]);
        }
    }
}
