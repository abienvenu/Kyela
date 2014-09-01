<?php

namespace Abienvenu\KyelaBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ParticipantControllerTest extends WebTestCase
{
	protected static $client;
	protected static $translator;

	public static function setUpBeforeClass()
	{
        // Create a new client to browse the application
		self::$client = static::createClient();
		self::$translator = self::$client->getContainer()->get('translator');
	}

	public static function createEntry($name)
	{
        // Create a new entry in the database
        $crawler = self::$client->request('GET', '/kyela/');
        $link = $crawler->selectLink(self::$translator->trans('add.a.participant'))->link();
        $crawler = self::$client->click($link);

        // Fill in the form and submit it
        $form = $crawler->selectButton(self::$translator->trans('create'))->form(['abienvenu_kyelabundle_participant[name]'  => $name]);
        self::$client->submit($form);
        return self::$client->followRedirect();
	}

	public static function deleteEntry($name)
	{
        // Create a new entry in the database
        $crawler = self::$client->request('GET', '/kyela/');
        $link = $crawler->filter('a:contains("' . $name . '")');
        $crawler = self::$client->click($link->link());
        self::$client->submit($crawler->selectButton(self::$translator->trans('delete'))->form());
        return self::$client->followRedirect();
	}

    public function testCompleteScenario()
    {
        $name = 'Test Participant L2PX';
    	$crawler = self::createEntry($name);

        // Check data in the show view
        $link = $crawler->filter('a:contains("' . $name . '")');
        $this->assertEquals(1, $link->count(), 'Missing element a:contains("' . $name . '")');

        // Edit the entity
        $crawler = self::$client->click($link->link());

        $name = "M $name";
        $form = $crawler->selectButton(self::$translator->trans('save'))->form(['abienvenu_kyelabundle_participant[name]'  => $name]);
        self::$client->submit($form);
        $crawler = self::$client->followRedirect();

        // Check data in the show view
        $link = $crawler->filter('a:contains("' . $name . '")');
        $this->assertEquals(1, $link->count(), 'Missing element a:contains("' . $name . '")');

        // Delete the entity
        return self::deleteEntry($name);

        // Check the entity has been delete on the list
        $this->assertNotRegExp("/$name/", self::$client->getResponse()->getContent());
    }
}
