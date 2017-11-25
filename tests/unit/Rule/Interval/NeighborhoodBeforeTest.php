<?php
declare(strict_types=1);

namespace Interval\Rule\Interval;

use Mockery as m;

class NeighborhoodBeforeTest extends \PHPUnit\Framework\TestCase
{
    public function assertProvider()
    {
        return [
            [
                10, 20, //                                    ██████████████████
                30, 40, //                                                          ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                false,
            ],
            [
                10, 20, //                                    ██████████████████
                20, 40, //                                                      ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                true,
            ],
            [
                10, 30, //                                    ███████████████████████
                20, 40, //                                                      ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                false,
            ],
            [
                10, 30, //                                    ███████████████████████
                20, 30, //                                                      ▒▒▒▒▒
                false,
            ],
            [
                10, 60, //                                    █████████████████████████████████████████████████
                20, 40, //                                                      ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                false,
            ],
            [
                10, 40, //                                    ███████████████████
                10, 40, //                                    ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                false,
            ],
            [
                30, 40, //                                    ██████████████████
                10, 20, //                ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                false,
            ],
            [
                30, 40, //                                    ██████████████████
                10, 30, //                ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                false,
            ],
            [
                30, 40, //                                    ██████████████████
                10, 35, //                ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                false,
            ],
            [
                30, 40, //                                    ██████████████████
                30, 35, //                                    ▒▒▒▒
                false,
            ],
            [
                30, 40, //                                    ██████████████████
                10, 60, //                ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
                false,
            ],
        ];
    }

    /**
     * @test
     * @dataProvider assertProvider
     * @param mixed $firstStart
     * @param mixed $firstEnd
     * @param mixed $secondStart
     * @param mixed $secondEnd
     * @param mixed $expected
     */
    public function assert($firstStart, $firstEnd, $secondStart, $secondEnd, $expected)
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

        $union  = new NeighborhoodBefore();
        $result = $union->assert($first, $second);
        $this->assertInternalType('bool', $result);
        $this->assertSame($expected, $result);
    }

    public function tearDown()
    {
        m::close();
    }
}
