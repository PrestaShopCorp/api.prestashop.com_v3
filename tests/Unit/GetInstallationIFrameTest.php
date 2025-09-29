<?php

namespace Tests\Unit;

use App\Events\GetInstallationIFrameQuery;
use App\Listeners\InstallationIFrameService;
use App\Services\GeoIPService;
use Tests\TestCase;

class GetInstallationIFrameTest extends TestCase
{
    /**
     * Test the installation iframe
     *
     * @return void
     */
    public function test_iFrameInstallation(): void
    {
        //Test on needed parameters
        $geoIPService = new GeoIPService();
        $service = new InstallationIFrameService($geoIPService);

        $parameters = [];
        $query = new GetInstallationIFrameQuery($parameters);
        $service->handle($query);
        $this->assertFalse($query->isSuccess());
        $this->assertStringContainsString('is required', $query->getError());

        $parameters = ['address' => '91.175.57.172', 'iso_lang' => 'en', 'step' => 'system', 'errors' => 'lala'];
        $query = new GetInstallationIFrameQuery($parameters);
        $service->handle($query);
        $this->assertTrue($query->isSuccess());
        $this->assertStringContainsString('inmotion', $query->getResult());

        $parameters['iso_lang'] = 'es';
        $query = new GetInstallationIFrameQuery($parameters);
        $service->handle($query);
        $this->assertTrue($query->isSuccess());
        $this->assertStringContainsString('1and1', $query->getResult());

        $parameters['iso_lang'] = 'fr';
        $query = new GetInstallationIFrameQuery($parameters);
        $service->handle($query);
        $this->assertTrue($query->isSuccess());
        $this->assertStringContainsString('ovh', $query->getResult());

        $parameters['step'] = '';
        $query = new GetInstallationIFrameQuery($parameters);
        $service->handle($query);
        $this->assertTrue($query->isSuccess());
        $this->assertStringContainsString('call-support', $query->getResult());
        $this->assertStringContainsString('tuto-installation-FR', $query->getResult());
    }
}
