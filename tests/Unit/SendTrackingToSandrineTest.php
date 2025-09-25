<?php

namespace Tests\Unit;

use App\Events\SendTrackingToSandrineCommand;
use App\Listeners\SandrineTrackingService;
use Tests\TestCase;

class SendTrackingToSandrineTest extends TestCase
{
    /**
     * Test on sending tracking to Sandrine
     */
    public function test_sendTrackingToSandrine(): void
    {
        $service = new SandrineTrackingService();

        //Test on needed parameters
        $parameters = [];
        $command = new SendTrackingToSandrineCommand($parameters);
        $service->handle($command);
        $this->assertFalse($command->isSuccess());
        $this->assertStringContainsString('is required', $command->getError());

        //Test on sending command
        $parameters = ['version' => '1.7.3.1', 'iso_code' => 'en', 'activity' => 0, 'address' => '', 'referer' => ''];
        $command = new SendTrackingToSandrineCommand($parameters);
        $service->handle($command);
        $this->assertTrue($command->isSuccess());
    }
}
