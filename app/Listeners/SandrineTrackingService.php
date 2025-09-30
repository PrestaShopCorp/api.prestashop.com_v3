<?php

namespace App\Listeners;

use App\Events\PrestaShopVersionChecked;
use Exception;

class SandrineTrackingService
{
    /**
     * Handle the event.
     */
    public function handle(PrestaShopVersionChecked $event): void
    {
        $parameters = $event->getParameters();
        try {
            $this->checkParameters($parameters);
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
     * @throws Exception
     */
    private function checkParameters(array $parameters): void
    {
        if (!isset($parameters['version'])) {
            throw new Exception('Parameter "version" is required.');
        }
        if (!isset($parameters['iso_code'])) {
            throw new Exception('Parameter "iso_code" is required.');
        }
        if (!isset($parameters['activity'])) {
            throw new Exception('Parameter "activity" is required.');
        }
        if (!isset($parameters['address'])) {
            throw new Exception('Parameter "address" is required.');
        }
        if (!isset($parameters['referer'])) {
            throw new Exception('Parameter "referer" is required.');
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
