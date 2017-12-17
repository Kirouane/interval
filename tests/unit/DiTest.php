<?php
declare(strict_types=1);

namespace Interval;

require_once __DIR__ . '/../../vendor/autoload.php';

class DiTest extends \PHPUnit\Framework\TestCase
{
    public function getServiceProvider()
    {
        return [
            [Di::OPERATION_INTERVAL_INTERSECTION],
            [Di::PARSER_INTERVALS],
        ];
    }
    /**
     * @test
     * @dataProvider getServiceProvider
     * @param mixed $serviceName
     */
    public function getService($serviceName)
    {
        $di      = new Di();
        $service = $di->get($serviceName);
        self::assertInstanceOf($serviceName, $service);
        $sameService = $di->get($serviceName);
        self::assertSame($service, $sameService);
    }
}
