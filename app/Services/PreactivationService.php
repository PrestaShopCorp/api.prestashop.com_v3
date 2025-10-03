<?php

namespace App\Services;

use Exception;

class PreactivationService
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
        if (!isset($parameters['partner'])) {
            throw new Exception('Parameter "partner" is required.');
        }
    }


    /**
     * @param array $parameters
     * @return string
     * @throws Exception
     */
    public function getPreactivationWarnings(array $parameters): string
    {
        $this->checkParameters($parameters);
        if ($parameters['version'] != '1.0') {
            return 'KO|Unknow version';
        }
        if (!$parameters['partner']) {
            return 'KO|No partner detected';
        }
        return 'KO|OK';
    }

}
