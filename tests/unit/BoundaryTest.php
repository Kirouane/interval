<?php
declare(strict_types=1);

namespace Interval;

require_once __DIR__ . '/../../vendor/autoload.php';

use Mockery as m;

class BoundaryTest extends \PHPUnit\Framework\TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function compareEqualsProvider()
    {
        return [
            [']10.0', ']11.0', false],
            [']10.0', '[11.0', false],
            [']10.0', '11.0[', false],
            [']10.0', '11.0]', false],

            ['[10.0', ']11.0', false],
            ['[10.0', '[11.0', false],
            ['[10.0', '11.0[', false],
            ['[10.0', '11.0]', false],

            ['10.0[', ']11.0', false],
            ['10.0[', '[11.0', false],
            ['10.0[', '11.0[', false],
            ['10.0[', '11.0]', false],

            ['10.0]', ']11.0', false],
            ['10.0]', '[11.0', false],
            ['10.0]', '11.0[', false],
            ['10.0]', '11.0]', false],

            //-------------------

            [']10.0', ']10.0', true],
            [']10.0', '[10.0', false],
            [']10.0', '10.0[', false],
            [']10.0', '10.0]', false],

            ['[10.0', ']10.0', false],
            ['[10.0', '[10.0', true],
            ['[10.0', '10.0[', false],
            ['[10.0', '10.0]', true],

            ['10.0[', ']10.0', false],
            ['10.0[', '[10.0', false],
            ['10.0[', '10.0[', true],
            ['10.0[', '10.0]', false],

            ['10.0]', ']10.0', false],
            ['10.0]', '[10.0', true],
            ['10.0]', '10.0[', false],
            ['10.0]', '10.0]', true],
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

        $bounday = new Boundary(...$arguments);
        self::assertSame($expected, $bounday->compare(new Boundary(...$argumentsToCompare)) === 0);
    }

    private function getArguments($symoble)
    {
        $value  = (int)str_replace(['[', ']'], '', $symoble);
        $isLeft = substr($symoble, 0, 1) === '[' || substr($symoble, 0, 1) === ']' ;
        $isOpen = ($isLeft && substr($symoble, 0, 1) === ']') || (!$isLeft && substr($symoble, -1) === '[');

        return [$value, $isLeft, $isOpen];
    }

    public function compareGreaterProvider()
    {
        return [
            [']10.0', ']11.0', false],
            [']10.0', '[11.0', false],
            [']10.0', '11.0[', false],
            [']10.0', '11.0]', false],

            ['[10.0', ']11.0', false],
            ['[10.0', '[11.0', false],
            ['[10.0', '11.0[', false],
            ['[10.0', '11.0]', false],

            ['10.0[', ']11.0', false],
            ['10.0[', '[11.0', false],
            ['10.0[', '11.0[', false],
            ['10.0[', '11.0]', false],

            ['10.0]', ']11.0', false],
            ['10.0]', '[11.0', false],
            ['10.0]', '11.0[', false],
            ['10.0]', '11.0]', false],

            //-------------------

            [']10.0', ']10.0', false],
            [']10.0', '[10.0', true],
            [']10.0', '10.0[', true],
            [']10.0', '10.0]', true],

            ['[10.0', ']10.0', false],
            ['[10.0', '[10.0', false],
            ['[10.0', '10.0[', true],
            ['[10.0', '10.0]', false],

            ['10.0[', ']10.0', false],
            ['10.0[', '[10.0', false],
            ['10.0[', '10.0[', false],
            ['10.0[', '10.0]', false],

            ['10.0]', ']10.0', false],
            ['10.0]', '[10.0', false],
            ['10.0]', '10.0[', true],
            ['10.0]', '10.0]', false],
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

        $bounday = new Boundary(...$arguments);
        self::assertSame($expected, $bounday->compare(new Boundary(...$argumentsToCompare)) === 1);
    }

    public function compareLessProvider()
    {
        return [
            [']10.0', ']11.0', true],
            [']10.0', '[11.0', true],
            [']10.0', '11.0[', true],
            [']10.0', '11.0]', true],

            ['[10.0', ']11.0', true],
            ['[10.0', '[11.0', true],
            ['[10.0', '11.0[', true],
            ['[10.0', '11.0]', true],

            ['10.0[', ']11.0', true],
            ['10.0[', '[11.0', true],
            ['10.0[', '11.0[', true],
            ['10.0[', '11.0]', true],

            ['10.0]', ']11.0', true],
            ['10.0]', '[11.0', true],
            ['10.0]', '11.0[', true],
            ['10.0]', '11.0]', true],

            //-------------------

            [']10.0', ']10.0', false],
            [']10.0', '[10.0', false],
            [']10.0', '10.0[', false],
            [']10.0', '10.0]', false],

            ['[10.0', ']10.0', true],
            ['[10.0', '[10.0', false],
            ['[10.0', '10.0[', false],
            ['[10.0', '10.0]', false],

            ['10.0[', ']10.0', true],
            ['10.0[', '[10.0', true],
            ['10.0[', '10.0[', false],
            ['10.0[', '10.0]', true],

            ['10.0]', ']10.0', true],
            ['10.0]', '[10.0', false],
            ['10.0]', '10.0[', false],
            ['10.0]', '10.0]', false],
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

        $bounday = new Boundary(...$arguments);
        self::assertSame($expected, $bounday->compare(new Boundary(...$argumentsToCompare)) === -1);
    }
}
