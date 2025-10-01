<?php

namespace App\Services;

use Exception;
use SimpleXMLElement;

class PrestashopVersionCheckerService
{
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
        if (!isset($parameters['iso_lang'])) {
            throw new Exception('Parameter "iso_lang" is required.');
        }
    }


    /**
     * @return SimpleXMLElement|false
     */
    private function getPrestaShopChannels(): SimpleXMLElement|false
    {
        $fileName = resource_path() . '/xml/channel.xml';
        if (file_exists($fileName)) {
            return simpleXML_load_file($fileName);
        }
        return false;
    }

    /**
     * @param string $version
     * @param string $isoCode
     * @param SimpleXMLElement $channels
     * @return array
     */
    private function getNewVersionInformations(string $version, string $isoCode, SimpleXMLElement $channels): array
    {
        $isoCode = in_array(strtolower($isoCode), ['en', 'fr', 'es', 'it', 'de', 'nl', 'pl', 'pt', 'ru']) ? strtolower($isoCode) : 'en';
        foreach ($channels->channel as $channel) {
            if ((string)$channel['name'] == 'stable') {
                foreach ($channel as $branch) {
                    if (in_array($branch['name'], ['1.6', '1.7'])) {
                        if (version_compare((string)$branch->num, $version, '>')) {
                            return [
                                'has_new_version' => true,
                                'version' => (string)$branch->num,
                                'link' => 'https://addons.prestashop.com/' . $isoCode . '/data-migration-backup/5496-.html'
                            ];
                        }
                    }
                }
            }
        }
        return ['has_new_version' => false];
    }

    /**
     * @param string $version
     * @param string $isoCode
     * @return array
     */
    private function checkNewVersion(string $version, string $isoCode): array
    {
        $channels = $this->getPrestaShopChannels();
        return $this->getNewVersionInformations($version, $isoCode, $channels);
    }

    /**
     * @param array $parameters
     * @return string
     * @throws Exception
     */
    public function checkPrestaShopVersion(array $parameters): string
    {
        $this->checkParameters($parameters);
        getTranslations($parameters['iso_lang']);
        $newVersionCheck = $this->checkNewVersion($parameters['version'], $parameters['iso_lang']);
        return view('prestashop_version_update', ['parameters' => $parameters, 'new_version_check' => $newVersionCheck])->render();
    }
}
