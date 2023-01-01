<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;


class AvailabilityPriceClientTest extends TestCase
{
    private const ENDPOINT = 'https://testapi.lleego.com/prueba-tecnica/availability-price';

    public function testApiResponse(): void
    {
        $client = HttpClient::create();
        $response = $client->request('GET', self::ENDPOINT);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaders()['content-type'][0]);        
    }
}
