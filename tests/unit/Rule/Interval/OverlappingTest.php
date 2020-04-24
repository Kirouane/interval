<?php
declare(strict_types=1);

namespace Interval\Rule\Interval;

use Interval\Interval;
use Mockery as m;

class OverlappingTest extends \PHPUnit\Framework\TestCase
{
    public function assertProvider()
    {
        return [
            [
                10, 20, false, false, //                                    ██████████████████
                30, 40, false, false, //                                                          ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                false,
            ],
            [
                10, 20, false, false, //                                    ██████████████████
                20, 40, false, false, //                                                     ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                true,
            ],
            [
                10, 20, false, true,  //                                    █████████████████
                20, 40, false, false, //                                                     ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                false,
            ],
            [
                10, 20, false, false, //                                    █████████████████
                20, 40, true, false,  //                                                     ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                false,
            ],
            [
                10, 30, false, false, //                                    ███████████████████████
                20, 40, false, false, //                                                      ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                true,
            ],
            [
                10, 30, false, false, //                                    ███████████████████████
                20, 30, false, false, //                                                      ▒▒▒▒▒
                true,
            ],
            [
                10, 30, false, false, //                                    ███████████████████████
                20, 30, false, true,  //                                                      ▒▒▒▒
                true,
            ],
            [
                10, 30, false, true,  //                                    ██████████████████████
                20, 30, false, false, //                                                      ▒▒▒▒▒
                true,
            ],
            [
                10, 60, false, false, //                                    █████████████████████████████████████████████████
                20, 40, false, false, //                                                      ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                true,
            ],
            [
                10, 40, false, false, //                                    ███████████████████
                10, 40, false, false, //                                    ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                true,
            ],
            [
                30, 40, false, false, //                                    ██████████████████
                10, 20, false, false, //                ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                false,
            ],
            [
                30, 40, false, false, //                                    ██████████████████
                10, 30, false, false, //                ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                true,
            ],
            [
                30, 40, true, false,  //                                    ██████████████████
                10, 30, false, false, //                ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                false,
            ],
            [
                30, 40, false, false, //                                    ██████████████████
                10, 30, false, true,  //                ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                false,
            ],
            [
                30, 40, false, false, //                                    ██████████████████
                10, 35, false, false, //                ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                true,
            ],
            [
                30, 40, false, false, //                                    ██████████████████
                30, 35, false, false, //                                    ▒▒▒▒
                true,
            ],
            [
                30, 40, true, false,  //                                     ██████████████████
                30, 35, false, false, //                                    ▒▒▒▒
                true,
            ],
            [
                30, 40, false, false, //                                    ██████████████████
                30, 35, true, false,  //                                     ▒▒▒▒
                true,
            ],
            [
                30, 40, false, false, //                                    ██████████████████
                10, 60, false, false, //                ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                true,
            ],
        ];
    }

    /**
     * @test
     * @dataProvider assertProvider
     * @param $firstStart
     * @param $firstEnd
     * @param $firstLeftOpen
     * @param $firstRightOpen
     * @param $secondStart
     * @param $secondEnd
     * @param $secondLeftOpen
     * @param $secondRightOpen
     * @param mixed $expected
     */
    public function assert($firstStart, $firstEnd, $firstLeftOpen, $firstRightOpen, $secondStart, $secondEnd, $secondLeftOpen, $secondRightOpen, $expected)
    {
        $asserter  = new Overlapping();
        $result    = $asserter->assert(
            new Interval($firstStart, $firstEnd, $firstLeftOpen, $firstRightOpen),
            new Interval($secondStart, $secondEnd, $secondLeftOpen, $secondRightOpen)
        );
        $this->assertIsBool($result);
        $this->assertSame($expected, $result);
    }

    public function tearDown(): void
    {
        m::close();
    }
}
