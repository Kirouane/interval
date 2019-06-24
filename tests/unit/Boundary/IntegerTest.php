<?php
declare(strict_types=1);

namespace Interval\Boundary;

require_once __DIR__ . '/../../../vendor/autoload.php';

use Mockery as m;

class IntegerTest extends \PHPUnit\Framework\TestCase
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

        $bounday = new Integer(...$arguments);
        self::assertSame($expected, $bounday->compare(new Integer(...$argumentsToCompare)) === 0);
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

        $bounday = new Integer(...$arguments);
        $bounday->compare(new Integer(...$argumentsToCompare));
        self::assertSame($expected, $bounday->compare(new Integer(...$argumentsToCompare)) === 1);
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

        $bounday = new Integer(...$arguments);
        self::assertSame($expected, $bounday->compare(new Integer(...$argumentsToCompare)) === -1);
    }

    /**
     * @test
     */
    public function equalToTest()
    {
        $bounday = new Integer(1, true);
        $this->assertInternalType('boolean', $bounday->equalTo(new Integer(1, true)));
    }

    /**
     * @test
     */
    public function greaterThanTest()
    {
        $bounday = new Integer(1, true);
        $this->assertInternalType('boolean', $bounday->greaterThan(new Integer(1, true)));
    }

    /**
     * @test
     */
    public function lessThanTest()
    {
        $bounday = new Integer(1, true);
        $this->assertInternalType('boolean', $bounday->lessThan(new Integer(1, true)));
    }

    /**
     * @test
     */
    public function greaterThanOrEqualToTest()
    {
        $bounday = new Integer(1, true);
        $this->assertInternalType('boolean', $bounday->greaterThanOrEqualTo(new Integer(1, true)));
    }

    /**
     * @test
     */
    public function lessThanOrEqualToTest()
    {
        $bounday = new Integer(1, true);
        $this->assertInternalType('boolean', $bounday->lessThanOrEqualTo(new Integer(1, true)));
    }

    public function toStringProvider()
    {
        return [
            [10, true, true, ']10'],
            [10, true, false, '[10'],
            [10, false, true, '10['],
            [10, false, false, '10]'],
        ];
    }

    /**
     * @test
     * @dataProvider  toStringProvider
     * @param mixed $value
     * @param mixed $isLeft
     * @param mixed $isOpen
     * @param mixed $expected
     */
    public function toStringTest($value, $isLeft, $isOpen, $expected)
    {
        $this->assertSame($expected, (string)new Integer($value, $isLeft, $isOpen));
    }

    public function flipProvider()
    {
        return [
            ']x => x]' => ['left', 'open', 'right', 'closed'],
            '[x => x[' => ['left', 'closed', 'right', 'open'],
            'x] => ]x' => ['right', 'closed', 'left', 'open'],
            'x[ => [x' => ['right', 'open', 'left', 'closed'],
        ];
    }

    /**
     * @test
     * @dataProvider flipProvider
     * @param mixed $side
     * @param mixed $openClosed
     * @param mixed $expectedSide
     * @param mixed $expectedopenClosed
     */
    public function flip($side, $openClosed, $expectedSide, $expectedopenClosed)
    {
        $boundary    = new Integer(1, $side === 'left', $openClosed === 'open');
        $newBoundary = $boundary->flip();

        self::assertSame($expectedSide == 'left', $newBoundary->isLeft());
        self::assertSame($expectedopenClosed == 'open', $newBoundary->isOpen());
    }
}
