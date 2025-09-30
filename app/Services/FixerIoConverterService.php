<?php

namespace App\Services;

use App\Services\Interfaces\ConverterInterface;
use Exception;

class FixerIoConverterService implements ConverterInterface
{
    const CONVERTER_URL = 'http://data.fixer.io/api/latest?access_key=';

    private $baseCurrency;

    /**
     * @param string $baseCurrency
     */
    public function setBaseCurrency($baseCurrency)
    {
        $this->baseCurrency = $baseCurrency;
    }

    /**
     * @return float[] Rates indexed by currency code
     * @throws Exception
     */
    public function getRates(): array
    {
        $url = self::CONVERTER_URL . getenv('FIXER_IO_API_KEY');
        $response = file_get_contents($url);

        if (empty($response)) {
            throw new Exception("Unable to load currencies from Fixer.io");
        }

        $content = json_decode($response, true);

        if (JSON_ERROR_NONE !== $lastError = json_last_error()) {
            throw new Exception(sprintf("Unable to parse JSON from Fixer.io. Error code: %s", $lastError));
        }

        if (empty($content['success'])) {
            throw new Exception("Fixer.io request returned success = false, maybe over API limit?");
        }

        if (empty($content['rates'])) {
            throw new Exception("No currencies returned by Fixer.io!");
        }

        if (!isset($content['base']) || $content['base'] != $this->baseCurrency) {
            throw new Exception("Default currency is no " . $this->baseCurrency);
        }

        return $content['rates'];
    }
}
