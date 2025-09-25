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

    private $url1 = 'https://api.prestashop.com';
    private $url2 = 'http://localhost:8001/api';

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

    private function checkVersion(string $url): void
    {
        echo "Testing Call: " . $url . "\n\n";

        if (!$stream = fopen($this->url1 . $url, 'r')) {
            return;
        }
        $contents = stream_get_contents($stream);
        $bodyContents1 = $this->getBodyContents($contents);

        if (!$stream = fopen($this->url2 . $url, 'r')) {
            return;
        }
        $contents = stream_get_contents($stream);
        $bodyContents2 = $this->getBodyContents($contents);

        if ($bodyContents1 != $bodyContents2) {
            echo "-------------- No -------------\n";
            die();
        } else {
            echo "-------------- Yes -------------\n";
        }
        echo "\n\n";
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $fileName = resource_path() . '/txt/api_calls.txt';
        $fileContents = file_get_contents($fileName);
        $calls = explode("\n", $fileContents);

        foreach ($calls as $call) {
            preg_match("/GET (.*) HTTP/", $call, $matches);
            if (!isset($matches[1])) {
                break;
            }
            if (str_starts_with($matches[1], '/version/check_version.php')) {
                //$this->checkVersion($matches[1]);
            } else {
                echo "Testing Call: " . $matches[1] . "\n\n";
            }
        }
    }
}
