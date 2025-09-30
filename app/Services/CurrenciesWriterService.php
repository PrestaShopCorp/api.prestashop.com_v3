<?php

namespace App\Services;

use Exception;
use SimpleXMLElement;

class CurrenciesWriterService
{
    const BASE_CURRENCY = 'EUR';

    private FixerIoConverterService $converter;

    public function __construct(FixerIoConverterService $converter)
    {
        $this->converter = $converter;
    }

    /**
     * @param float[] $rates indexed by currency code
     *
     * @throws Exception
     */
    private function writeCurrencies(array $rates): void
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
        fwrite(fopen('currencies.xml.new', 'w'), $dom->saveXML());
    }


    /**
     * @return void
     * @throws Exception
     */
    public function createDailyCurrencyFile(): void
    {
        $this->converter->setBaseCurrency(self::BASE_CURRENCY);
        $rates = $this->converter->getRates();
        $this->writeCurrencies($rates);
    }
}
