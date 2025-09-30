<?php

namespace App\Console\Commands;

use App\Services\CurrenciesWriterService;
use App\Services\FixerIoConverterService;
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

    public function __construct(CurrenciesWriterService $currenciesWriterService)
    {
        $this->currenciesWriterService = $currenciesWriterService;
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        echo "Update currencies \n";
        try {
            $this->currenciesWriterService->createDailyCurrencyFile();
            echo "Success !\n";
        } catch (\Exception $e) {
            echo "Error: ". $e->getMessage() . "\n";
        }
    }
}
