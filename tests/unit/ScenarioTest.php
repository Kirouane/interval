<?php
declare(strict_types=1);

namespace Interval;

require_once __DIR__ . '/../../vendor/autoload.php';

class ScenarioTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function scenario()
    {
        $interval = Interval::create('[0, 10]');
        $interval = $interval->intersect(Interval::create(']1, 20['));

        $this->assertSameInterval(
            Interval::create(']1, 10]'),
            $interval
        );

        $intervals = $interval->union(Interval::create('[3, 30['));

        $this->assertSameIntervals(
            Intervals::create([']1, 30[']),
            $intervals
        );

        $intervals = $intervals->exclude(Intervals::create(['[5, 7[', '[9, 9]', ']16, 20[']));

        $this->assertSameIntervals(
            Intervals::create([']1, 5[', '[7, 9[', ']9, 16]', '[20, 30[']),
            $intervals
        );
    }

    private function assertSameInterval(Interval $interval, Interval $comparedInterval)
    {
        self::assertSame($interval->getStart()->getValue(), $comparedInterval->getStart()->getValue());
        self::assertSame($interval->getStart()->isLeft(), $comparedInterval->getStart()->isLeft());
        self::assertSame($interval->getStart()->isOpen(), $comparedInterval->getStart()->isOpen());

        self::assertSame($interval->getEnd()->getValue(), $comparedInterval->getEnd()->getValue());
        self::assertSame($interval->getEnd()->isLeft(), $comparedInterval->getEnd()->isLeft());
        self::assertSame($interval->getEnd()->isOpen(), $comparedInterval->getEnd()->isOpen());
    }

    private function assertSameIntervals(Intervals $intervals, Intervals $comparedIntervals)
    {
        foreach ($intervals as $i => $interval) {
            $this->assertSameInterval($interval, $comparedIntervals[$i]);
        }
    }
}
