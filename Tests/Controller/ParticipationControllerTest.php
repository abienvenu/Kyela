<?php

namespace Abienvenu\KyelaBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ParticipationControllerTest extends WebTestCase
{
    public function testCompleteScenario()
    {
    	$name = "Participation Test User";
    	$crawler = ParticipantControllerTest::createEntry($name);

    	$entryRow = $crawler->filter('tr:contains("' . $name . '")');
    	// TODO

    	ParticipantControllerTest::deleteEntry($name);
    }
}
