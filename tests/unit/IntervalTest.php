<?php
declare(strict_types=1);
namespace UnitTest\Interval;
require_once __DIR__ . '/../../vendor/autoload.php';
use \Mockery as m;
class IntervalTest extends \PHPUnit\Framework\TestCase
{

    public function tearDown()
    {
        m::close();
    }

    /**
     * @test
     * @expectedException \RangeException
     */
    public function constructorShouldThrowException()
    {
        new \Interval\Interval(2, 1);
    }
}