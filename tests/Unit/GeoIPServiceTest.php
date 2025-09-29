<?php

namespace Tests\Unit;

use App\Services\GeoIPService;
use Tests\TestCase;

class GeoIPServiceTest extends TestCase
{

    /**
     * Test getLocalization
     *
     * @return void
     */
    public function test_getLocalization(): void
    {
        $geoIPService = new GeoIPService();
        $result = $geoIPService->getGeoLocalizationFromIP('127.0.0.1');
        $this->assertNull($result);

        $result = $geoIPService->getGeoLocalizationFromIP('91.175.57.172');
        $this->assertIsArray($result);
        $this->assertArrayHasKey('country_iso_code', $result);
        $this->assertArrayHasKey('continent_iso_code', $result);
        $this->assertEquals('fr', $result['country_iso_code']);
        $this->assertEquals('eu', $result['continent_iso_code']);
    }
}
