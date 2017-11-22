<?php
declare(strict_types=1);
namespace Interval;

require_once __DIR__ . '/../../vendor/autoload.php';
use Interval\Interval;
use Interval\Intervals;
use \Mockery as m;

class ParserTest extends \PHPUnit\Framework\TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function parseProvider()
    {
        return [
            ['[1,2]', 1, 2],
            ['[-INF,2]', -INF, 2],
            ['[-INF,+INF]', -INF, INF],

            ['[1.1,2.2]', 1.1, 2.2],

            ['[a,b]', 'a', 'b'],
        ];
    }

    /**
     * @test
     * @dataProvider parseProvider
     */
    public function parse($expression, $start, $end)
    {
        $parser = new Parser();

        /** @var Interval $interval */
        $interval = $parser->parse($expression);
        $this->assertInstanceOf(Interval::class, $interval);
        $this->assertSame($start, $interval->getStart());
        $this->assertSame($end, $interval->getEnd());
    }
}
