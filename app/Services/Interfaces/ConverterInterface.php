<?php

namespace App\Services\Interfaces;

interface ConverterInterface
{
    public function setBaseCurrency($baseCurrency);

    public function getRates();
}
