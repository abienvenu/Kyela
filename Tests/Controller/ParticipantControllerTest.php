<?php

namespace Abienvenu\KyelaBundle\Tests\Controller;

class ParticipantControllerTest extends PollWebTestCase
{
    public function testCompleteScenario()
    {
    	// Create a poll to work with
    	$crawler = self::createPollEntry(uniqid('Test Poll '));

    	// Create a participant
    	$name = uniqid('Test Participant ');
        $crawler = self::clickLink($crawler, 'add.a.participant');
        $crawler = self::submitForm($crawler, 'create', 'participant', ['name' => $name]);

        // Check data in the show view
        $filter = 'a:contains("' . $name . '")';
        self::checkElement($crawler, $filter);

        // Edit the entity
        $crawler = self::clickLink($crawler, $name);
        $name = "M $name";
        $crawler = self::submitForm($crawler, 'save', 'participant', ['name' => $name]);

        // Check data in the show view
        $filter = 'a:contains("' . $name . '")';
        self::checkElement($crawler, $filter);

        // Delete the participant
        $crawler = self::clickLink($crawler, $name);
        $crawler = self::submitForm($crawler, 'delete');

        // Check the participant has been deleted from the list
        $this->assertNotRegExp("/$name/", self::$client->getResponse()->getContent());

        // Delete the poll
        self::deletePollEntry($crawler);
    }
}
