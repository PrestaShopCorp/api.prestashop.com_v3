<?php

namespace Tests\Unit;

use App\Services\RssService;
use Tests\TestCase;

class RssNewsServiceTest extends TestCase
{
    /**
     * Test getRssNews
     *
     * @return void
     */
    public function test_getRssNews(): void
    {
        $service = new RssService();

        $parameters = [];
        try {
            $service->getNews($parameters);
            $this->fail();
        } catch (\Exception $e) {
            $this->assertStringContainsString('is required', $e->getMessage());
        }

        $parameters = ['version' => '', 'iso_lang' => 'gr'];
        try {
            $buffer = $service->getNews($parameters);
            $this->assertStringNotContainsString('block_news_first', $buffer);
        } catch (\Exception $e) {
            $this->fail();
        }

        $parameters = ['version' => '', 'iso_lang' => 'fr'];
        try {
            $buffer = $service->getNews($parameters);
            $this->assertStringContainsString('block_news_first', $buffer);
        } catch (\Exception $e) {
            $this->fail();
        }
    }
}
