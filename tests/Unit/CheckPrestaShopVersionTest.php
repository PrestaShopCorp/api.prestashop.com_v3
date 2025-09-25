<?php

namespace Tests\Unit;

use App\Events\CheckPrestaShopVersionUpdatesQuery;
use App\Listeners\PrestashopVersionUpdateService;
use Tests\TestCase;

class CheckPrestaShopVersionTest extends TestCase
{

    /**
     * Test the channel.xml file
     *
     * @return void
     */
    public function test_channel_xml(): void
    {
        //Channel file exists
        $fileName = resource_path() . '/xml/channel.xml';
        $this->assertTrue(file_exists($fileName));

        //There is a nodes in it
        $channels = simpleXML_load_file($fileName);
        $this->assertTrue($channels instanceof \SimpleXMLElement);

        //There is a stable channel in it
        $hasStable = false;
        $stableChannel = false;
        foreach ($channels as $channel) {
            if ($channel['name'] == 'stable') {
                $hasStable = true;
                $stableChannel = $channel;
            }
        }
        $this->assertTrue($hasStable);

        //There is a 1.7 node in it
        $has17Node = false;
        foreach ($stableChannel as $branch) {
            if ($branch['name'] == '1.7') {
                $has17Node = true;
            }
        }
        $this->assertTrue($has17Node);
    }

    /**
     * Test the check_version.php call
     */
    public function test_check_prestashop_version_update(): void
    {
        $service = new PrestashopVersionUpdateService();

        //Test on needed parameters
        $parameters = [];
        $query = new CheckPrestaShopVersionUpdatesQuery($parameters);
        $service->handle($query);
        $this->assertFalse($query->isSuccess());
        $this->assertStringContainsString('is required', $query->getError());

        //Test on an update is available
        $parameters = ['version' => '1.7.3.1', 'iso_code' => 'en', 'hosted_mode' => 0];
        $query = new CheckPrestaShopVersionUpdatesQuery($parameters);
        $service->handle($query);
        $this->assertTrue($query->isSuccess());
        $this->assertStringContainsString('You can update to PrestaShop', $query->getResult());

        //Test on PS version is written
        preg_match("/You can update to PrestaShop ([0-9.]*)/", $query->getResult(), $matches);
        $this->assertTrue(isset($matches[1]));

        $parameters['version'] = $matches[1];
        $query = new CheckPrestaShopVersionUpdatesQuery($parameters);
        $service->handle($query);
        $this->assertTrue($query->isSuccess());
        $this->assertStringContainsString('Your PrestaShop version is up to date', $query->getResult());
    }
}
