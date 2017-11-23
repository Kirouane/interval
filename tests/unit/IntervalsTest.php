<?php
declare(strict_types=1);
namespace Interval;

require_once __DIR__ . '/../../vendor/autoload.php';

use Interval\Interval;
use Interval\Intervals;
use \Mockery as m;

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
            [[[0, 1], [3, 7]], '{[0, 1], [3, 7]}']
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
    public function exclude()
    {
        $intervals = new Intervals([
            new Interval(1, 10),
            new Interval(16, 20)
        ]);

        $results = $intervals->exclude(new Intervals([
            new Interval(2, 7),
            new Interval(17, 18)
        ]));

        $this->assertInstanceOf(Intervals::class, $results);
        $this->assertCount(4, $results);
    }


    /**
     * @test
     */
    public function create()
    {
        $intervals = Intervals::create(['[10, 15]']);
        $this->assertSame(10, $intervals[0]->getStart());
        $this->assertSame(15, $intervals[0]->getEnd());
    }
}
