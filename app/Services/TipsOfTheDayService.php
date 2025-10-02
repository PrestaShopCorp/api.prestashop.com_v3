<?php

namespace App\Services;

use Exception;

class TipsOfTheDayService
{

    /**
     * @param string $tipName
     * @param string $isoLang
     * @return array
     */
    private function getTranslatedTip(string $tipName, string $isoLang): array
    {
        global $tip;
        switch($isoLang) {
            case 'fr':
            case 'en':
            case 'es':
            case 'it':
            case 'pl':
            case 'ru':
                break;

            default:
                $isoLang = 'en';
        }
        $defaultFileName = resource_path() . '/translations/tips/' . $tipName . '/en.php';
        $fileName = resource_path() . '/translations/tips/' . $tipName . '/' . $isoLang . '.php';
        if (is_file($fileName)) {
            include_once $fileName;
        } else {
            include_once $defaultFileName;
        }
        return $tip;
    }

    /**
     * @param array $tip
     * @return mixed
     */
    private function pickAdvice(array $tip): mixed
    {
        $pickedAdvice = rand(0, (count($tip['advices']) - 1));
        $advice = $tip['advices'][$pickedAdvice];
        $advice['advice'] = str_replace('/img/tips/', getenv('SERVER_URL') . '/img/tips/', $advice['advice']);
        return $advice;
    }

    /**
     * @param array $parameters
     * @return string
     */
    public function getTipOfTheDay(array $parameters): string
    {
        $tipName = 'paypal';
        $tip = $this->getTranslatedTip($tipName, $parameters['iso_lang']);
        $advice = $this->pickAdvice($tip);
        $tip['name'] = $tipName;
        $tip['advice_title'] = $advice['title'];
        $tip['advice'] = $advice['advice'];
        return view('tipsoftheday/tips_' . $tipName, ['parameters' => $parameters, 'tip' => $tip])->render();
    }

}
