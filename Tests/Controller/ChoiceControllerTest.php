<?php

namespace Abienvenu\KyelaBundle\Tests\Controller;

class ChoiceControllerTest extends PollWebTestCase
{

    public function testCompleteScenario()
    {
    	// Create a poll to work with
    	$crawler = self::createPollEntry(uniqid('Test Poll '));

    	// Go to options list
    	$crawler = self::clickLink($crawler, 'edit.options');

    	// Create a choice
    	$name = uniqid('Test Choice ');
        $crawler = self::clickLink($crawler, 'add.an.option');
        self::checkElement($crawler, 'h1:contains("' . self::$translator->trans('new.choice') . '")');
        $crawler = self::submitForm($crawler, 'create', 'choice', ['name' => $name, 'value' => 1, 'color' => 'green', 'priority' => 12]);

        // Check data in the show view
        $filter = 'tr:contains("' . $name . '")';
        $crawler = self::checkElement($crawler, $filter);

        // Edit the choice
        $crawler = self::clickLink($crawler, 'edit');
        $name = "M $name";
        $crawler = self::submitForm($crawler, 'save', 'choice', ['name' => $name, 'value' => 1, 'color' => 'green', 'priority' => 12]);

        // Check data in the show view
        $filter = 'tr:contains("' . $name . '")';
        $crawler = self::checkElement($crawler, $filter);

        // Delete the choice
        $crawler = self::clickLink($crawler, 'edit');
        $crawler = self::submitForm($crawler, 'delete');

        // Check the choice has been deleted from the list
        $this->assertNotRegExp("/$name/", self::$client->getResponse()->getContent());

        // Go back to the poll
        $crawler = self::clickLink($crawler, 'back');

        // Delete the poll
        self::deletePollEntry($crawler);
    }
}
