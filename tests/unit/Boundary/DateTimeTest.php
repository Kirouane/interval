<?php
declare(strict_types=1);

namespace Interval\Boundary;

require_once __DIR__ . '/../../../vendor/autoload.php';

use Mockery as m;

class DateTimeTest extends \PHPUnit\Framework\TestCase
{
    public function tearDown(): void
    {
        m::close();
    }

    public function compareEqualsProvider()
    {
        return [
            [']10', ']11', false],
            [']10', '[11', true],
            [']10', '11[', false],
            [']10', '11]', true],

            ['[10', ']11', false],
            ['[10', '[11', false],
            ['[10', '11[', true],
            ['[10', '11]', false],

            ['10[', ']11', false],
            ['10[', '[11', false],
            ['10[', '11[', false],
            ['10[', '11]', false],

            ['10]', ']11', false],
            ['10]', '[11', false],
            ['10]', '11[', true],
            ['10]', '11]', false],

            //-------------------

            [']10', ']10', true],
            [']10', '[10', false],
            [']10', '10[', false],
            [']10', '10]', false],

            ['[10', ']10', false],
            ['[10', '[10', true],
            ['[10', '10[', false],
            ['[10', '10]', true],

            ['10[', ']10', false],
            ['10[', '[10', false],
            ['10[', '10[', true],
            ['10[', '10]', false],

            ['10]', ']10', false],
            ['10]', '[10', true],
            ['10]', '10[', false],
            ['10]', '10]', true],
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

        $bounday = new DateTime(...$arguments);
        self::assertSame($expected, $bounday->compare(new DateTime(...$argumentsToCompare)) === 0);
    }

    private function getArguments($symoble)
    {
        $value  = (int)str_replace(['[', ']'], '', $symoble);
        $value  = new \DateTime('2017-11-01 10:00:' . $value);
        $isLeft = substr($symoble, 0, 1) === '[' || substr($symoble, 0, 1) === ']' ;
        $isOpen = ($isLeft && substr($symoble, 0, 1) === ']') || (!$isLeft && substr($symoble, -1) === '[');

        return [$value, $isLeft, $isOpen];
    }

    public function compareGreaterProvider()
    {
        return [
            [']10', ']11', false],
            [']10', '[11', false],
            [']10', '11[', true],
            [']10', '11]', false],

            ['[10', ']11', false],
            ['[10', '[11', false],
            ['[10', '11[', false],
            ['[10', '11]', false],

            ['10[', ']11', false],
            ['10[', '[11', false],
            ['10[', '11[', false],
            ['10[', '11]', false],

            ['10]', ']11', false],
            ['10]', '[11', false],
            ['10]', '11[', false],
            ['10]', '11]', false],

            //-------------------

            [']10', ']10', false],
            [']10', '[10', true],
            [']10', '10[', true],
            [']10', '10]', true],

            ['[10', ']10', false],
            ['[10', '[10', false],
            ['[10', '10[', true],
            ['[10', '10]', false],

            ['10[', ']10', false],
            ['10[', '[10', false],
            ['10[', '10[', false],
            ['10[', '10]', false],

            ['10]', ']10', false],
            ['10]', '[10', false],
            ['10]', '10[', true],
            ['10]', '10]', false],
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

        $bounday = new DateTime(...$arguments);
        $bounday->compare(new DateTime(...$argumentsToCompare));
        self::assertSame($expected, $bounday->compare(new DateTime(...$argumentsToCompare)) === 1);
    }

    public function compareLessProvider()
    {
        return [
            [']10', ']11', true],
            [']10', '[11', false],
            [']10', '11[', false],
            [']10', '11]', false],

            ['[10', ']11', true],
            ['[10', '[11', true],
            ['[10', '11[', false],
            ['[10', '11]', true],

            ['10[', ']11', true],
            ['10[', '[11', true],
            ['10[', '11[', true],
            ['10[', '11]', true],

            ['10]', ']11', true],
            ['10]', '[11', true],
            ['10]', '11[', false],
            ['10]', '11]', true],

            //-------------------

            [']10', ']10', false],
            [']10', '[10', false],
            [']10', '10[', false],
            [']10', '10]', false],

            ['[10', ']10', true],
            ['[10', '[10', false],
            ['[10', '10[', false],
            ['[10', '10]', false],

            ['10[', ']10', true],
            ['10[', '[10', true],
            ['10[', '10[', false],
            ['10[', '10]', true],

            ['10]', ']10', true],
            ['10]', '[10', false],
            ['10]', '10[', false],
            ['10]', '10]', false],
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

        $bounday = new DateTime(...$arguments);
        self::assertSame($expected, $bounday->compare(new DateTime(...$argumentsToCompare)) === -1);
    }

    /**
     * @test
     */
    public function toStringTest()
    {
        $this->assertSame('[2010-10-10T00:00:00+00:00', (string)new DateTime(new \DateTime('2010-10-10'), true));
    }
}
