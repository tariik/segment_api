<?php

namespace App\Service;

class SoapXmlParser
{
  
    public function parseFlightSegment(String $data): array 
    {
        $xml = simplexml_load_string($data);
        // Registrar el dominio segments y Accede al nodo ....FlightSegmentList/FlightSegment
        $xml->registerXPathNamespace('segments', 'http://www.iata.org/IATA/EDIST/2017.2');
        $xpath = '//soap:Envelope/soap:Body/segments:AirShoppingRS/segments:DataLists/segments:FlightSegmentList/segments:FlightSegment';
          
        return $xml->xpath($xpath);
    }

    function isXml(string $value): bool
    {
        $prev = libxml_use_internal_errors(true);

        $doc = simplexml_load_string($value);
        $errors = libxml_get_errors();

        libxml_clear_errors();
        libxml_use_internal_errors($prev);

        return false !== $doc && empty($errors);
    }
}