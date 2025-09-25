<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckPrestaShopVersionRetroCompat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-version';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check retro compatibility for check_version.php';

    /**
     * @param string $contents
     * @return string
     */
    private function getBodyContents(string $contents): string
    {
        $startPos = strpos($contents, "<body>");
        $finalPos = strpos($contents, "</body>");
        $bodyContents = substr($contents, $startPos + 6, $finalPos-$startPos-6);

        $startPos = strpos($bodyContents, "<script>");
        $finalPos = strpos($bodyContents, "</script>");
        return $startPos
            ? trim(substr($bodyContents, 0, $startPos) . substr($bodyContents, $finalPos + 9))
            : trim($bodyContents);
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $url1 = 'https://api.prestashop.com';
        $url2 = 'http://localhost:8001/api';
        $fileName = resource_path() . '/txt/api_calls.txt';
        $fileContents = file_get_contents($fileName);
        $calls = explode("\n", $fileContents);
        foreach ($calls as $call) {
            preg_match("/GET (.*) HTTP/", $call, $matches);
            if (isset($matches[1]) && substr($matches[1], 0, 26) == '/version/check_version.php') {
                echo "Testing Call: " . $matches[1] . "\n\n";

                if ($stream = fopen($url1 . $matches[1], 'r')) {
                    $contents = stream_get_contents($stream);
                    $bodyContents1 = $this->getBodyContents($contents);
                    //echo 'Size 1: ' . strlen($bodyContents1) . "\n";
                    //echo $bodyContents1."\n";
                } else {
                    break;
                }

                if ($stream = fopen($url2 . $matches[1], 'r')) {
                    $contents = stream_get_contents($stream);
                    $bodyContents2 = $this->getBodyContents($contents);
                    //echo 'Size 2: ' . strlen($bodyContents2) . "\n";
                    //echo $bodyContents2."\n";
                } else {
                    break;
                }

                if ($bodyContents1 != $bodyContents2) {
                    echo "-------------- No -------------\n";
                    die();
                } else {
                    echo "-------------- Yes -------------\n";
                }
                echo "\n\n";
            }
        }
    }
}
