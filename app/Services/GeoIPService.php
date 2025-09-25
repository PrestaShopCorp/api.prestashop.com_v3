<?php

namespace App\Services;

use Exception;
use GeoIp2\Database\Reader;

class GeoIPService
{
    /**
     * @param string $ip
     * @return array|null
     */
    public function getGeoLocalizationFromIP(string $ip): ?array
    {
        if (in_array($ip, ['127.0.0.1', '::1'])) {
            return null;
        }
        $fileName = resource_path() . '/config/GeoLite2-Country.mmdb';
        try {
            $reader = new Reader($fileName);
            $country = $reader->country($ip);
            return [
                'country_iso_code' => strtolower($country->country->isoCode),
                'continent_iso_code' => strtolower($country->continent->code)
            ];
        } catch (Exception $e) {
            return null;
        }
    }
}
