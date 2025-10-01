<?php

namespace Tests\Unit;

use App\Events\ActionShouldBeTracked;
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
        $event = new ActionShouldBeTracked($parameters);
        $service->handle($event);
        $this->assertFalse($event->isSuccess());
        $this->assertStringContainsString('is required', $event->getError());

        //Test on sending command
        $parameters = ['version' => '1.7.3.1', 'iso_lang' => 'en', 'activity' => 0, 'address' => '', 'referer' => ''];
        $event = new ActionShouldBeTracked($parameters);
        $service->handle($event);
        $this->assertTrue($event->isSuccess());
    }
}
