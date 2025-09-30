<?php

namespace App\Console\Commands;

use App\Services\CurrenciesWriterService;
use App\Services\FixerIoConverterService;
use App\Services\GoogleStorageService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateCurrenciesRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-currencies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update currencies and create xml';

    private CurrenciesWriterService $currenciesWriterService;
    private FixerIoConverterService $converterService;
    private GoogleStorageService $storageService;


    public function __construct(
        CurrenciesWriterService $currenciesWriterService, FixerIoConverterService $converterService, GoogleStorageService $storageService
    ) {
        $this->converterService = $converterService;
        $this->currenciesWriterService = $currenciesWriterService;
        $this->storageService = $storageService;
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        echo "Update currencies \n";
        try {
            $date = Carbon::now()->format('Y-m-d');
            $this->converterService->setBaseCurrency('EUR');
            $rates = $this->converterService->getRates();
            $buffer = $this->currenciesWriterService->getDailyCurrencyFileBuffer($rates);
            $this->storageService->pushOnBucket('api/currencies', 'currency_' . $date . '.xml', $buffer);
            echo "Success !\n";
        } catch (\Exception $e) {
            echo "Error: ". $e->getMessage() . "\n";
        }
    }
}
