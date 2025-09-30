<?php

namespace Tests\Unit;

use App\Services\CurrenciesWriterService;
use Exception;
use Tests\TestCase;

class CurrenciesWriterServiceTest extends TestCase
{

    /**
     * Test createDailyCurrencyFile
     *
     * @return void
     * @throws Exception
     */
    public function test_createDailyCurrencyFile(): void
    {
        $currenciesWriterService = app(CurrenciesWriterService::class);
        try {
            $buffer = $currenciesWriterService->getDailyCurrencyFileBuffer(['EUR' => 1, 'GBP' => 0.8]);
            $this->assertStringContainsString('currency iso_code="EUR" rate="1"', $buffer);
        } catch (Exception $exception) {
            $this->fail($exception->getMessage());
        }
    }
}
