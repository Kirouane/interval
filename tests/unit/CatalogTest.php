<?php
declare(strict_types=1);

namespace Interval;

require_once __DIR__ . '/../../vendor/autoload.php';

class CatalogTest extends \PHPUnit\Framework\TestCase
{
    public function getServiceProvider()
    {
        return [
            [Catalog::OPERATION_INTERVAL_EXCLUSION],
            [Catalog::PARSER_INTERVALS],
        ];
    }
    /**
     * @test
     * @dataProvider getServiceProvider
     * @param mixed $serviceName
     */
    public function getService($serviceName)
    {
        $catalog = new Catalog();
        $service = $catalog->get($serviceName);
        self::assertInstanceOf($serviceName, $service);
    }
}
