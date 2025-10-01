<?php

namespace App\Services;

use Exception;

class RssService
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
     * @param array $parameters
     * @return array
     */
    private function getXMLInformations(array $parameters): array
    {
        $fileName = storage_path('app/public/xml/blog/blog-' . $parameters['iso_lang'] . '.xml');
        $xml = simplexml_load_string(file_get_contents($fileName));

        if (!isset($xml->channel->item) || !$xml->channel->item) {
            return [];
        }

        $items = [];
        foreach ($xml->channel->item as $item) {
            $items []= [
                'title' => (string)$item->title,
                'link' => (string)$item->link,
                'description' => (string)$item->description,
            ];
        }
        return array_slice($items, 0, 4);
    }


    /**
     * @param array $parameters
     * @return string
     * @throws Exception
     */
    public function getNews(array $parameters): string
    {
        $this->checkParameters($parameters);
        getTranslations($parameters['iso_lang']);
        $xmlItems = $this->getXMLInformations($parameters);
        return view('rss_news', ['parameters' => $parameters, 'xml_items' => $xmlItems])->render();
    }
}
