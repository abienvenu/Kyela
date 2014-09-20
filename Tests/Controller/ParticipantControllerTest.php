<?php

namespace Abienvenu\KyelaBundle\Tests\Controller;

class ParticipantControllerTest extends PollWebTestCase
{
	/**
	 * Creates a participant
	 *
	 * @param string $name
	 */
	public static function createEntry($crawler, $name)
	{
        // Create a new entry in the database
        $link = $crawler->selectLink(self::$translator->trans('add.a.participant'))->link();
        $crawler = self::$client->click($link);

        // Fill in the form and submit it
        $form = $crawler->selectButton(self::$translator->trans('create'))->form(['abienvenu_kyelabundle_participant[name]'  => $name]);
        self::$client->submit($form);
        return self::$client->followRedirect();
	}

	/**
	 * Deletes a participant
	 *
	 * @param string $name
	 */
	public static function deleteEntry($crawler, $name)
	{
        // Create a new entry in the database
        $link = $crawler->filter('a:contains("' . $name . '")');
        $crawler = self::$client->click($link->link());
        self::$client->submit($crawler->selectButton(self::$translator->trans('delete'))->form());
        return self::$client->followRedirect();
	}

    public function testCompleteScenario()
    {
    	$name = uniqid('Test Participant ');
    	$crawler = self::createPollEntry(uniqid('Test Poll '));
    	$crawler = self::createEntry($crawler, $name);

        // Check data in the show view
        $filter = 'a:contains("' . $name . '")';
        $link = $crawler->filter($filter);
        $this->assertEquals(1, $link->count(), "Missing element $filter");

        // Edit the entity
        $crawler = self::$client->click($link->link());

        $name = "M $name";
        $form = $crawler->selectButton(self::$translator->trans('save'))->form(['abienvenu_kyelabundle_participant[name]'  => $name]);
        self::$client->submit($form);
        $crawler = self::$client->followRedirect();

        // Check data in the show view
        $filter = 'a:contains("' . $name . '")';
        $link = $crawler->filter($filter);
        $this->assertEquals(1, $link->count(), "Missing element $filter");

        // Delete the entity
        $crawler = self::deleteEntry($crawler, $name);

        // Check the entity has been delete on the list
        $this->assertNotRegExp("/$name/", self::$client->getResponse()->getContent());

        self::deletePollEntry($crawler);
    }
}
