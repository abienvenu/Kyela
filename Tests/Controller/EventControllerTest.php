<?php

namespace Abienvenu\KyelaBundle\Tests\Controller;

class EventControllerTest extends PollWebTestCase
{
	/**
	 * Creates, edits and deletes an event
	 */
    public function testCompleteScenario()
    {
    	// Create a poll to work with
    	$crawler = self::createPollEntry(uniqid('Test Poll '));

    	// Create a participant
    	$name = uniqid('Test Event ');
        $crawler = self::clickLink($crawler, 'add.a.date');
        //$date = ['day' => '2', 'month' => '3', $year => '2014'];
        //$time = ['hours' => '20', 'minute' => '30'];
        $crawler = self::submitForm($crawler, 'create', 'event',
        	['name' => $name, 'place' => 'Nowhere', 'date' => '04-09-2034', 'time' => '20:30']);

        // Check data in the show view
        $filter = 'div.list-group-item:contains("' . $name . '")';
        $crawler = self::checkElement($crawler, $filter);

        // Edit the event
        $crawler = self::clickLink($crawler, '');
        $name = "M $name";
        $crawler = self::submitForm($crawler, 'save', 'event',
			['name' => $name, 'place' => 'Nowhere', 'date' => '04-09-2034', 'time' => '20:30']);

        // Check data in the show view
        $filter = 'div.list-group-item:contains("' . $name . '")';
        $crawler = self::checkElement($crawler, $filter);

        // Delete the event
        $crawler = self::clickLink($crawler, '');
        $crawler = self::submitForm($crawler, 'delete');

        // Check the participant has been deleted from the list
        $this->assertNotRegExp("/$name/", self::$client->getResponse()->getContent());

        // Delete the poll
        self::deletePollEntry($crawler);
    }
}
