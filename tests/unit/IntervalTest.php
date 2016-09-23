<?php
declare(strict_types=1);
namespace UnitTest\Interval;
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
            [2, 1],
            [2, 2]
        ];
    }

    /**
     * @test
     * @expectedException \RangeException
     * @dataProvider constructorShouldThrowExceptionProvider
     */
    public function constructorShouldThrowException($start, $end)
    {
        new \Interval\Interval($start, $end);
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
        $interval = new \Interval\Interval(1, 2);
        $this->assertInstanceOf(Interval::class, $interval->intersect(new \Interval\Interval(2, 3)));
    }
}