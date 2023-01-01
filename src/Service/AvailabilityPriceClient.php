<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class AvailabilityPriceClient
{
    private const ENDPOINT = 'https://testapi.lleego.com/prueba-tecnica/availability-pricexx';
    private $httpClient;
    
    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getData(): String
    {  
        $response = $this->httpClient->request('GET', self::ENDPOINT);

        if ($response->getStatusCode() != 200) {
            throw new \Exception('An error occurred with endpoint: ' . $response->getStatusCode());
        }
        
        return $response->getContent();
    }
}