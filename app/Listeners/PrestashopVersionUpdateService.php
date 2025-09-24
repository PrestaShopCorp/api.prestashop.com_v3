<?php

namespace App\Listeners;

use App\Events\CheckPrestaShopVersionUpdatesQuery;
use Exception;

class PrestashopVersionUpdateService
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CheckPrestaShopVersionUpdatesQuery $event): void
    {
        $parameters = $event->getParameters();
        try {
            $buffer = $this->sendPrestaShopVersionUpdateFrame($parameters);
            $event->setResult($buffer);
            $event->setSuccess(true);
        } catch (Exception $exception) {
            $event->setSuccess(false);
            $event->setError($exception->getMessage());
        }
    }

    /**
     * @param array $parameters
     * @return string
     */
    private function sendPrestaShopVersionUpdateFrame(array $parameters): string
    {
        getTranslations($parameters['iso_code']);
        $newVersionCheck = $this->checkNewVersion($parameters['version'], $parameters['iso_code']);
        return view('prestashop_version_update', ['parameters' => $parameters, 'new_version_check' => $newVersionCheck])->render();
    }

    /**
     * @param string $version
     * @param string $isoCode
     * @return array
     */
    private function checkNewVersion(string $version, string $isoCode): array
    {
        $channels = simpleXML_load_file(resource_path() . '/xml/channel.xml');
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
}
