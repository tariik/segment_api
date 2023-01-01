<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\AvailabilityPriceClient;
use App\Service\SoapXmlParser;
use App\Entity\Segment;
use \Datetime;

/**
 * Class SegmentController
 * @package App\Controller
 *
 * @Route(path="/api/")
 */
class SegmentController
{
    private $availabilityPrice;
    private $soapXmlParser;

    public function __construct(
        AvailabilityPriceClient $availabilityPrice,
        SoapXmlParser $soapXmlParser
    )
    {
        $this->availabilityPrice = $availabilityPrice;
        $this->soapXmlParser = $soapXmlParser;
    }

    /**
     * @Route("segments", name="get_all_segments", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $segments = [];

        // petecion para los datos de availability price
        $availabilityPriceaData = $this->availabilityPrice->getData();

        // valid xml file
        if (!$this->soapXmlParser->isXml($availabilityPriceaData)) {
            return new JsonResponse("The XML format provided is invalid.", 500);
        }
       
        // extrair los datos 
        $flightSegmens = $this->soapXmlParser->parseFlightSegment($availabilityPriceaData);
        
        if (!$flightSegmens) {
            return new JsonResponse("The XML file does not contain a list of flight segments.", 500);
        }
        // generar array de objetos segment 
        foreach ($flightSegmens as $flightSegment) {
            
            $segment = new Segment();
            $segment->setOriginCode((string)$flightSegment->Departure->AirportCode);
            $segment->setOriginName((string)$flightSegment->Departure->AirportName);
            $segment->setDestinationCode((string)$flightSegment->Arrival->AirportCode);
            $segment->setDestinationName((string)$flightSegment->Arrival->AirportName);

            $start = (string) $flightSegment->Departure->Date." ".(string) $flightSegment->Departure->Time;
            $end = (string) $flightSegment->Arrival->Date." ".(string) $flightSegment->Arrival->Time;
            $segment->setStart(DateTime::createFromFormat('Y-m-d H:i', $start));
            $segment->setEnd(DateTime::createFromFormat('Y-m-d H:i', $end));
    
            $segment->setTransportNumber((string)$flightSegment->MarketingCarrier->FlightNumber);
            $segment->setCompanyCode((string)$flightSegment->MarketingCarrier->AirlineID);
            $segment->setCompanyName((string)$flightSegment->MarketingCarrier->Name);

            //TODO: segemtToArray
            // array para la repuesta json
            $data[] = [
                'originCode' => $segment->getOriginCode(),
                'originName' => $segment->getOriginName(),
                'destinationCode' => $segment->getDestinationCode(),
                'destinationName' => $segment->getDestinationName(),
                'start' => $segment->getStart(),
                'end' => $segment->getEnd(),
                'transportNumber' => $segment->getTransportNumber(),
                'companyCode' => $segment->getCompanyCode(),
                'companyName' => $segment->getCompanyName(),
            ];

            $segments[] =$segment;
        }

        $json = json_encode($data);
                
        return new JsonResponse($json, 200, [], true);
    }

}
