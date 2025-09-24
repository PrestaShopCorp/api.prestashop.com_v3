<?php

namespace App\Listeners;

use App\Events\SendTrackingToSandrineCommand;
use Exception;

class SandrineTrackingService
{
    /**
     * Handle the event.
     */
    public function handle(SendTrackingToSandrineCommand $event): void
    {
        $parameters = $event->getParameters();
        try {
            $this->sendTrackingToSandrine($parameters);
            $event->setSuccess(true);
        } catch (Exception $exception) {
            $event->setSuccess(false);
            $event->setError($exception->getMessage());
        }
    }

    /**
     * @param array $parameters
     * @return void
     */
    private function sendTrackingToSandrine(array $parameters): void
    {
        $version = $parameters['version'];
        $isoCode = $parameters['iso_code'];
        $activity = $parameters['activity'];
        $address = $parameters['address'];
        $referer = $parameters['referer'];

        $context = stream_context_create(['http' => ['timeout' => 5]]);
        file_get_contents('http://sandrine.prestashop.com/tracker/tracker.php?v=' . $version .
            '&lang=' . $isoCode . '&activity=' . $activity . '&REMOTE_ADDR=' . $address .
            '&HTTP_REFERER=' . $referer, false, $context);
    }
}
