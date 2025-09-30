<?php

namespace Tests\Unit;

use App\Services\GeoIPService;
use App\Services\InstallationSupportService;
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
        $installationSupportService = new InstallationSupportService($geoIPService);

        $parameters = [];
        try {
            $installationSupportService->getIFrame($parameters);
            $this->fail();
        } catch (\Exception $e) {
            $this->assertStringContainsString('is required', $e->getMessage());
        }

        $parameters = ['address' => '91.175.57.172', 'iso_lang' => 'en', 'step' => 'system', 'errors' => 'lala'];
        try {
            $buffer = $installationSupportService->getIFrame($parameters);
            $this->assertStringContainsString('inmotion', $buffer);
        } catch (\Exception $e) {
            $this->fail();
        }

        $parameters['iso_lang'] = 'es';
        try {
            $buffer = $installationSupportService->getIFrame($parameters);
            $this->assertStringContainsString('1and1', $buffer);
        } catch (\Exception $e) {
            $this->fail();
        }

        $parameters['iso_lang'] = 'fr';
        try {
            $buffer = $installationSupportService->getIFrame($parameters);
            $this->assertStringContainsString('ovh', $buffer);
        } catch (\Exception $e) {
            $this->fail();
        }

        $parameters['step'] = '';
        try {
            $buffer = $installationSupportService->getIFrame($parameters);
            $this->assertStringContainsString('call-support', $buffer);
            $this->assertStringContainsString('tuto-installation-FR', $buffer);
        } catch (\Exception $e) {
            $this->fail();
        }
    }
}
