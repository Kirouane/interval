<?php
declare(strict_types=1);

namespace Interval\Operation\Interval;

use Interval\Interval;
use Interval\Intervals;
use Mockery as m;

class ExclusionTest extends \PHPUnit\Framework\TestCase
{
    public function tearDown(): void
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
     * @param mixed $expected
     * @test
     */
    public function compute($firstStart, $firstEnd, $secondStart, $secondEnd, $expected)
    {
        $union     = new Exclusion();
        $intervals = $union(new Interval($firstStart, $firstEnd), new Interval($secondStart, $secondEnd));
        $this->assertInstanceOf(Intervals::class, $intervals);

        $data = [];
        /** @var Interval $interval */
        foreach ($intervals as $interval) {
            $data[] = [$interval->getStart()->getValue(), $interval->getEnd()->getValue()];
        }

        $this->assertSame($expected, $data);
    }
}
