<?php
declare(strict_types=1);

namespace Interval;

require_once __DIR__ . '/../../vendor/autoload.php';

use Mockery as m;

class IntervalsTest extends \PHPUnit\Framework\TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function toStringTestProvider()
    {
        return [
            [[], '{}'],
            [[[0, 1]], '{[0, 1]}'],
            [[[0, 1], [3, 7]], '{[0, 1], [3, 7]}'],
        ];
    }

    /**
     * @dataProvider toStringTestProvider
     * @param array $intervalsData
     * @param $expected
     * @test
     */
    public function toStringTest(array $intervalsData, $expected)
    {
        $array = [];
        foreach ($intervalsData as $intervalData) {
            $array[] = new Interval($intervalData[0], $intervalData[1]);
        }

        $intervals = new Intervals($array);
        $this->assertSame($expected, (string)$intervals);
    }

    /**
     * @test
     */
    public function create()
    {
        $intervals = Intervals::create(['[10, 15]']);
        $this->assertSame(10, $intervals[0]->getStart()->getValue());
        $this->assertSame(15, $intervals[0]->getEnd()->getValue());
    }

    /**
     * @test
     */
    public function filter()
    {
        $intervals = Intervals::create(['[10, 15]', '[10, 11]']);
        $filtered  = $intervals->filter(function (Interval $interval) {
            return $interval->getEnd()->getValue() === 11;
        });
        self::assertCount(1, $filtered);
        self::assertSame(10, $filtered[0]->getStart()->getValue());
        self::assertSame(11, $filtered[0]->getEnd()->getValue());
    }

    /**
     * @test
     */
    public function map()
    {
        $intervals = Intervals::create(['[10, 15]', '[10, 11]']);
        $filtered  = $intervals->map(function (Interval $interval) {
            return new Interval(0, $interval->getEnd());
        });

        self::assertCount(2, $filtered);
        self::assertSame(0, $filtered[0]->getStart()->getValue());
        self::assertSame(15, $filtered[0]->getEnd()->getValue());
        self::assertSame(0, $filtered[1]->getStart()->getValue());
        self::assertSame(11, $filtered[1]->getEnd()->getValue());
    }

    /**
     * @test
     */
    public function sort()
    {
        $intervals = Intervals::create(['[12, 15]', '[10, 11]']);
        $filtered  = $intervals->sort(function (Interval $first, Interval $second) {
            return $first->getStart() <=> $second->getEnd();
        });

        self::assertCount(2, $filtered);
        self::assertSame(10, $filtered[0]->getStart()->getValue());
        self::assertSame(11, $filtered[0]->getEnd()->getValue());
        self::assertSame(12, $filtered[1]->getStart()->getValue());
        self::assertSame(15, $filtered[1]->getEnd()->getValue());
    }
}
