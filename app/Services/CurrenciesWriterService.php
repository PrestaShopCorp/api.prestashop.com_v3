<?php

namespace App\Services;

use Exception;
use SimpleXMLElement;

class CurrenciesWriterService
{
    const BASE_CURRENCY = 'EUR';

    /**
     * @param array $rates
     * @return string
     * @throws Exception
     */
    public function getDailyCurrencyFileBuffer(array $rates): string
    {
        $xmlString = '<?xml version="1.0" encoding="UTF-8"?><currencies><source iso_code="' . self::BASE_CURRENCY . '" /></currencies>';
        $xml = new SimpleXMLElement($xmlString);

        $listNode = $xml->addChild('list');

        foreach ($rates as $isoCode => $rate) {
            $currency = $listNode->addChild('currency');
            $currency->addAttribute('iso_code', $isoCode);
            $currency->addAttribute('rate', $rate);
        }

        $dom = dom_import_simplexml($xml)->ownerDocument;
        $dom->formatOutput = true;

        $xmlErrors = libxml_get_errors();
        if (0 < count($xmlErrors)) {
            $errorMessage = 'XML Errors have been found:'.PHP_EOL;
            foreach ($xmlErrors as $error) {
                $errorMessage .= $error->message;
            }
            throw new Exception($errorMessage);
        }
        return $dom->saveXML();
    }
}
