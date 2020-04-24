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
                10, 20, false, false, //                              ██████████████████
                20, 20, false, false, //                                               ▒
                '{[10, 20[}', //                                      ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓
            ],
            [
                10, 10, false, false, //                              █
                10, 10, false, false, //                              ▒
                '{}', //                                              No intervals
            ],
            [
                10, 20, false, false, //                              ██████████████████
                30, 40, false, false, //                                                    ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                '{[10, 20]}', //                                      ██████████████████
            ],
            [
                10, 20, false, false, //                              ██████████████████
                20, 40, false, false, //                                                ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                '{[10, 20[}', //                                      ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓
            ],
            [
                10, 20, false, false, //                              ██████████████████
                20, 40, true, false,  //                                                ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                '{[10, 20]}', //                                      ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓
            ],
            [
                10, 30, false, false, //                              ███████████████████████
                20, 40, false, false, //                                                ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                '{[10, 20[}', //                                      ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓
            ],
            [
                10, 30, false, false, //                              ███████████████████████
                20, 40, true, false, //                                                ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                '{[10, 20]}', //                                      ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓
            ],
            [
                10, 30, false, false, //                              ███████████████████████
                20, 30, false, false, //                                                ▒▒▒▒▒
                '{[10, 20[}', //                                      ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓
            ],
            [
                10, 50, false, false, //                              █████████████████████████████████████████████████
                20, 40, false, false, //                                                ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                '{[10, 20[, ]40, 50]}', //                            ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓                    ▓▓▓▓▓▓▓▓▓▓▓
            ],
            [
                10, 40, false, false, //                              ███████████████████
                10, 40, false, false, //                              ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                '{}' , //                                             No intervals
            ],
            [
                30, 40, false, false, //                              ██████████████████
                10, 20, false, false, //          ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                '{[30, 40]}', //                  ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓
            ],
            [
                30, 40, false, false, //                              ██████████████████
                10, 30, false, false, //          ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                '{]30, 40]}', //                  ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓
            ],
            [
                30, 40, false, false, //                              ██████████████████
                10, 35, false, false, //          ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                '{]35, 40]}', //                                          ▓▓▓▓▓▓▓▓▓▓▓▓▓▓
            ],
            [
                30, 40, false, false, //                              ██████████████████
                30, 35, false, false, //                              ▒▒▒▒
                '{]35, 40]}', //                                          ▓▓▓▓▓▓▓▓▓▓▓▓▓▓
            ],
            [
                30, 40, false, false, //                              ██████████████████
                10, 50, false, false, //          ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                '{}', //                                              No intervals
            ],

            //with infinity
            [
                -INF, 20, false, false, //                         ∞██████████████████
                30, 40, false, false, //                                                    ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                '{]-∞, 20]}', //                                   ∞██████████████████
            ],
            [
                -INF, +INF, false, false, //                       ∞██████████████████████████████████████████████████████∞
                30, 40, false, false, //                                                ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                '{]-∞, 30[, ]40, +∞[}', //                         ∞████████████████████                ██████████████████∞
            ],
            [
                -INF, +INF, false, false, //                       ∞██████████████████████████████████████████████████████∞
                30, 40, true, true, //                                                  ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                '{]-∞, 30], [40, +∞[}', //                         ∞████████████████████                ██████████████████∞
            ],
        ];
    }

    /**
     * @dataProvider computeProvider
     * @param $firstStart
     * @param $firstEnd
     * @param $firstLeftOpen
     * @param $firstRightOpen
     * @param $secondStart
     * @param $secondEnd
     * @param $secondLeftOpen
     * @param $secondRightOpen
     * @param $expected
     * @test
     */
    public function compute($firstStart, $firstEnd, $firstLeftOpen, $firstRightOpen, $secondStart, $secondEnd, $secondLeftOpen, $secondRightOpen, $expected)
    {
        $exclusion = new Exclusion();
        $intervals = $exclusion(
            new Interval($firstStart, $firstEnd, $firstLeftOpen, $firstRightOpen),
            new Interval($secondStart, $secondEnd, $secondLeftOpen, $secondRightOpen)
        );
        $this->assertInstanceOf(Intervals::class, $intervals);

        $this->assertSame($expected, (string)$intervals);
    }
}
