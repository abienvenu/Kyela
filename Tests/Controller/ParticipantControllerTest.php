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
        $crawler = self::clickLink($crawler, 'add.a.participant');
        return self::submitForm($crawler, 'create', 'participant', ['name' => $name]);
	}

	/**
	 * Deletes a participant
	 *
	 * @param string $name
	 */
	public static function deleteEntry($crawler, $name)
	{
        $crawler = self::clickLink($crawler, $name);
        return self::submitForm($crawler, 'delete');
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
