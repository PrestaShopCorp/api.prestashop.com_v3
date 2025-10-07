<?php

namespace App\Console\Commands;

use App\Services\RssService;
use Illuminate\Console\Command;

class GetRssBlogXml extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-rss-blog-xml';
    private $rssService;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get blog languages XML on prestashop.com';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        echo "Get RSS Blog files \n";
        $this->rssService = new RssService();
        $languages = ['fr', 'en', 'es', 'de', 'it', 'pt', 'pl', 'nl'];
        foreach($languages as $isoLang) {
            $this->rssService->importRssBlogByLanguage($isoLang);
        }
    }
}
