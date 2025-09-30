<?php

namespace App\Services;

use Exception;

class InstallationSupportService
{
    private GeoIPService $geoIPService;

    /**
     * Create the event listener.
     */
    public function __construct(GeoIPService $geoIPService)
    {
        $this->geoIPService = $geoIPService;
    }

    /**
     * Handle the event.
     * @throws Exception
     */
    public function getIFrame(array $parameters): string
    {
        $this->checkParameters($parameters);
        return $this->getInstallationIFrame($parameters);
    }

    /**
     * @param array $parameters
     * @return void
     * @throws Exception
     */
    private function checkParameters(array $parameters): void
    {
        if (!isset($parameters['address'])) {
            throw new Exception('Parameter "address" is required.');
        }
        if (!isset($parameters['iso_lang'])) {
            throw new Exception('Parameter "iso_lang" is required.');
        }
        if (!isset($parameters['step'])) {
            throw new Exception('Parameter "step" is required.');
        }
        if (!isset($parameters['errors'])) {
            throw new Exception('Parameter "errors" is required.');
        }
    }

    /**
     * @param array $parameters
     * @param array|null $localization
     * @return string[]
     */
    private function getInformations(array $parameters, ?array $localization): array
    {
        if ($localization === null) {
            return ['phone' => '+33 (0)1 40 18 30 04', 'installation_image' => 'tuto-installation-EN.jpg'];
        }
        if ($localization['continent_iso_code'] == 'eu') {
            if ($localization['country_iso_code'] == 'es') {
                $phone = '+ 34.917.87.29.09';
                $inst = 'tuto-installation-ES.jpg';
            } elseif ($localization['country_iso_code'] == 'it') {
                $phone = '+39 068 997 00 52';
                $inst = 'tuto-installation-IT.jpg';
            } elseif (in_array($localization['country_iso_code'], ['uk', 'en', 'gb', 'ie'])) {
                $phone = '+44 .2 036.971.999';
                $inst = 'tuto-installation-EN.jpg';
            } elseif ($parameters['iso_lang'] == 'fr' || in_array($localization['country_iso_code'], ['fr', 'be', 'ch'])) {
                $phone = '+33 (0)1 40 18 30 04';
                $inst = 'tuto-installation-FR.jpg';
            } else {
                $phone = '+33 (0)1 40 18 30 04';
                $inst = 'tuto-installation-EN.jpg';
            }
        } elseif (in_array($localization['continent_iso_code'], ['na', 'sa', 'oc'])) {
            $phone = '+1 (888) 947-6543';
            $inst = 'tuto-installation-EN.jpg';
        } else {
            $phone = '+33 (0)1 40 18 30 04';
            $inst = 'tuto-installation-EN.jpg';
        }
        return ['phone' => $phone, 'installation_image' => $inst];
    }


    /**
     * @param array $parameters
     * @return string
     */
    private function getInstallationIFrame(array $parameters): string
    {
        if ($parameters['step'] == 'system' && !empty($parameters['errors'])) {
            return view('hosting_iframe', ['parameters' => $parameters])->render();
        }
        $geolocalization = $this->geoIPService->getGeoLocalizationFromIP($parameters['address']);
        $informations = $this->getInformations($parameters, $geolocalization);
        return view('installation_iframe', [
            'parameters' => $parameters,
            'geolocalization' => $geolocalization,
            'informations' => $informations
        ])->render();
    }
}
