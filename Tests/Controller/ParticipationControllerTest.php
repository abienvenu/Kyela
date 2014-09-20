<?php

namespace Abienvenu\KyelaBundle\Tests\Controller;

class ParticipationControllerTest extends PollWebTestCase
{
	/**
	 * Creates, edits and deletes a participation
	 */
    public function testCompleteScenario()
    {
    	// Create a poll to work with
    	$crawler = self::createPollEntry(uniqid('Test Poll '));

    	// Create a participant
    	$participant = uniqid('Test Participant ');
        $crawler = self::clickLink($crawler, 'add.a.participant');
        $crawler = self::submitForm($crawler, 'create', 'participant', ['name' => $participant]);

    	// Create an event
    	$event = uniqid('Test Event ');
        $crawler = self::clickLink($crawler, 'add.a.date');
        $crawler = self::submitForm($crawler, 'create', 'event',
        	['name' => $event, 'place' => 'Nowhere', 'date' => '04-09-2034', 'time' => '20:30']);

        // Check "yes" is not selected
        $filter = 'button:contains("' . self::$translator->trans('yes') . '")';
        self::checkElement($crawler, $filter, 1);

        // Choose "yes"
        $crawler = self::clickLink($crawler, 'yes');
        $crawler = self::$client->followRedirect();

        // Check "yes is selected
        $filter = 'button:contains("' . self::$translator->trans('yes') . '")';
        self::checkElement($crawler, $filter, 2);

        // Choose "no"
        $crawler = self::clickLink($crawler, 'no');
        $crawler = self::$client->followRedirect();

        // Check "yes" is not selected
        $filter = 'button:contains("' . self::$translator->trans('yes') . '")';
        self::checkElement($crawler, $filter, 1);

        // Check "no" is selected
        $filter = 'button:contains("' . self::$translator->trans('no') . '")';
        self::checkElement($crawler, $filter, 2);

        // Delete the poll
        self::deletePollEntry($crawler);
    }
}
