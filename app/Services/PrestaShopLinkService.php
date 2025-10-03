<?php

namespace App\Services;

class PrestaShopLinkService
{
    /**
     * @param array $parameters
     * @return string
     */
    public function getPrestaShopLink(array $parameters): string
    {
        getTranslations('partner', $parameters['iso_lang']);
        return view('prestashop_link', ['parameters' => $parameters])->render();
    }
}
