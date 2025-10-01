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
     * @return array
     */
    private function getOldRates(): array
    {
        $oldRates = [];
        $buffer = $this->storageService->getFileOnBucket('api/currencies', 'currency.xml')->downloadAsString();
        $currencies = explode("\n", $buffer);
        foreach($currencies as $currency) {
            if (!($pos = strpos($currency, 'currency iso_code')) || !($pos2 = strpos($currency, 'rate="'))) {
                continue;
            }
            $currencyCode = substr($currency, $pos + 19, 3);
            $rate = (float) substr($currency, $pos2 + 6);
            $oldRates[$currencyCode] = $rate;
        }
        return $oldRates;
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
            if (!$this->storageService->isFileExists('api/currencies', 'currency.xml')) {
                $this->storageService->pushOnBucket('api/currencies', 'currency.xml', $buffer);
            } else {
                $oldRates = $this->getOldRates();
                foreach ($oldRates as $isoCurrency => $rate) {
                    if (!isset($rates[$isoCurrency])) {
                        echo "ISO currency $isoCurrency missing for currencies\n";
                        die();
                    } else {
                        $diff = round((float)(100 * ($rate - $rates[$isoCurrency]) / $rates[$isoCurrency]), 2);
                        if ($diff > 10) {
                            echo "ISO currency $isoCurrency changed too much\n";
                            die();
                        }
                        echo "Currency $isoCurrency: " . $diff . "%\n";
                    }
                }
                $this->storageService->pushOnBucket('api/currencies', 'currency.xml', $buffer);
            }


            echo "Success !\n";
        } catch (\Exception $e) {
            echo "Error: ". $e->getMessage() . "\n";
        }
    }
}
