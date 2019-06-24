<?php
declare(strict_types=1);

namespace Interval\Boundary;

require_once __DIR__ . '/../../../vendor/autoload.php';

use Mockery as m;

class InfinityTest extends \PHPUnit\Framework\TestCase
{
    public function tearDown(): void
    {
        m::close();
    }

    public function compareEqualsProvider()
    {
        return [
            [']-INF', ']11.0', false],
            [']-INF', '[11.0', false],
            [']-INF', '11.0[', false],
            [']-INF', '11.0]', false],

            ['[-INF', ']11.0', false],
            ['[-INF', '[11.0', false],
            ['[-INF', '11.0[', false],
            ['[-INF', '11.0]', false],

            ['-INF[', ']11.0', false],
            ['-INF[', '[11.0', false],
            ['-INF[', '11.0[', false],
            ['-INF[', '11.0]', false],

            ['-INF]', ']11.0', false],
            ['-INF]', '[11.0', false],
            ['-INF]', '11.0[', false],
            ['-INF]', '11.0]', false],

            //-------------------

            [']-INF', ']-INF', true],
            [']-INF', '[-INF', true],
            [']-INF', '-INF[', true],
            [']-INF', '-INF]', true],

            ['[-INF', ']-INF', true],
            ['[-INF', '[-INF', true],
            ['[-INF', '-INF[', true],
            ['[-INF', '-INF]', true],

            ['-INF[', ']-INF', true],
            ['-INF[', '[-INF', true],
            ['-INF[', '-INF[', true],
            ['-INF[', '-INF]', true],

            ['-INF]', ']-INF', true],
            ['-INF]', '[-INF', true],
            ['-INF]', '-INF[', true],
            ['-INF]', '-INF]', true],
        ];
    }

    /**
     * @dataProvider compareEqualsProvider
     * @test
     * @param mixed $symbole
     * @param mixed $comparedSymbole
     * @param mixed $expected
     */
    public function compareEquals($symbole, $comparedSymbole, $expected)
    {
        $arguments          = $this->getArguments($symbole);
        $argumentsToCompare = $this->getArguments($comparedSymbole);

        $bounday = new Infinity(...$arguments);
        self::assertSame($expected, $bounday->compare(new Infinity(...$argumentsToCompare)) === 0);
    }

    private function getArguments($symoble)
    {
        $value  = (float)str_replace(['[', ']'], '', $symoble);
        $isLeft = substr($symoble, 0, 1) === '[' || substr($symoble, 0, 1) === ']' ;
        $isOpen = ($isLeft && substr($symoble, 0, 1) === ']') || (!$isLeft && substr($symoble, -1) === '[');

        return [$value, $isLeft, $isOpen];
    }

    public function compareGreaterProvider()
    {
        return [
            [']-INF', ']11.0', false],
            [']-INF', '[11.0', false],
            [']-INF', '11.0[', false],
            [']-INF', '11.0]', false],

            ['[-INF', ']11.0', false],
            ['[-INF', '[11.0', false],
            ['[-INF', '11.0[', false],
            ['[-INF', '11.0]', false],

            ['-INF[', ']11.0', false],
            ['-INF[', '[11.0', false],
            ['-INF[', '11.0[', false],
            ['-INF[', '11.0]', false],

            ['-INF]', ']11.0', false],
            ['-INF]', '[11.0', false],
            ['-INF]', '11.0[', false],
            ['-INF]', '11.0]', false],

            //-------------------

            [']-INF', ']-INF', false],
            [']-INF', '[-INF', false],
            [']-INF', '-INF[', false],
            [']-INF', '-INF]', false],

            ['[-INF', ']-INF', false],
            ['[-INF', '[-INF', false],
            ['[-INF', '-INF[', false],
            ['[-INF', '-INF]', false],

            ['-INF[', ']-INF', false],
            ['-INF[', '[-INF', false],
            ['-INF[', '-INF[', false],
            ['-INF[', '-INF]', false],

            ['-INF]', ']-INF', false],
            ['-INF]', '[-INF', false],
            ['-INF]', '-INF[', false],
            ['-INF]', '-INF]', false],
        ];
    }

    /**
     * @dataProvider compareGreaterProvider
     * @test
     * @param mixed $symbole
     * @param mixed $comparedSymbole
     * @param mixed $expected
     */
    public function compareGreater($symbole, $comparedSymbole, $expected)
    {
        $arguments          = $this->getArguments($symbole);
        $argumentsToCompare = $this->getArguments($comparedSymbole);

        $bounday = new Infinity(...$arguments);
        self::assertSame($expected, $bounday->compare(new Infinity(...$argumentsToCompare)) === 1);
    }

    public function compareLessProvider()
    {
        return [
            [']-INF', ']11.0', true],
            [']-INF', '[11.0', true],
            [']-INF', '11.0[', true],
            [']-INF', '11.0]', true],

            ['[-INF', ']11.0', true],
            ['[-INF', '[11.0', true],
            ['[-INF', '11.0[', true],
            ['[-INF', '11.0]', true],

            ['-INF[', ']11.0', true],
            ['-INF[', '[11.0', true],
            ['-INF[', '11.0[', true],
            ['-INF[', '11.0]', true],

            ['-INF]', ']11.0', true],
            ['-INF]', '[11.0', true],
            ['-INF]', '11.0[', true],
            ['-INF]', '11.0]', true],

            //-------------------

            [']-INF', ']-INF', false],
            [']-INF', '[-INF', false],
            [']-INF', '-INF[', false],
            [']-INF', '-INF]', false],

            ['[-INF', ']-INF', false],
            ['[-INF', '[-INF', false],
            ['[-INF', '-INF[', false],
            ['[-INF', '-INF]', false],

            ['-INF[', ']-INF', false],
            ['-INF[', '[-INF', false],
            ['-INF[', '-INF[', false],
            ['-INF[', '-INF]', false],

            ['-INF]', ']-INF', false],
            ['-INF]', '[-INF', false],
            ['-INF]', '-INF[', false],
            ['-INF]', '-INF]', false],
        ];
    }

    /**
     * @dataProvider compareLessProvider
     * @test
     * @param mixed $symbole
     * @param mixed $comparedSymbole
     * @param mixed $expected
     */
    public function compareLess($symbole, $comparedSymbole, $expected)
    {
        $arguments          = $this->getArguments($symbole);
        $argumentsToCompare = $this->getArguments($comparedSymbole);

        $bounday = new Infinity(...$arguments);
        $bounday->compare(new Infinity(...$argumentsToCompare));
        self::assertSame($expected, $bounday->compare(new Infinity(...$argumentsToCompare)) === -1);
    }

    /**
     * @test
     */
    public function toStringTest()
    {
        $this->assertSame('[-∞', (string)new Infinity(-INF, true));
    }
}
