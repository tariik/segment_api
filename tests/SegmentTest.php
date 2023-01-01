<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\Segment;
use DateTime;

class SegmentTest extends TestCase
{
    public function testProperties(): void
    {
        $segment = new Segment();
        
        $segment->setOriginCode('AirportCode');
        $segment->setOriginName('AirportName');
        $segment->setDestinationCode('AirportCodeDestination');
        $segment->setDestinationName('AirportNameDestination');
        $start = DateTime::createFromFormat('Y-m-d H:i',"2022-06-01 11:50") ;
        $end = DateTime::createFromFormat('Y-m-d H:i', "2022-06-01 13:50") ;
        $segment->setStart($start);
        $segment->setEnd($end);
        $segment->setTransportNumber('FlightNumber');
        $segment->setCompanyCode('AirlineID');
        $segment->setCompanyName('AirlineName');

        $this->AssertEquals('AirportCode',$segment->getOriginCode());
        $this->AssertEquals('AirportName',$segment->getOriginName());
        $this->AssertEquals('AirportCodeDestination',$segment->getDestinationCode());
        $this->AssertEquals('AirportNameDestination',$segment->getDestinationName());
        $this->AssertEquals($start,$segment->getStart());
        $this->AssertEquals($end,$segment->getEnd());
        $this->AssertEquals('FlightNumber',$segment->getTransportNumber());
        $this->AssertEquals('AirlineID',$segment->getCompanyCode());
        $this->AssertEquals('AirlineName',$segment->getCompanyName());

    }
}
