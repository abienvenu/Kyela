<?php

namespace Abienvenu\KyelaBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PollControllerTest extends WebTestCase
{
	protected static $client;
	protected static $translator;

	public static function setUpBeforeClass()
	{
        // Create a new client to browse the application
		self::$client = static::createClient();
		self::$translator = self::$client->getContainer()->get('translator');
	}

	/**
	 * Creates a poll
	 *
	 * @param string $name
	 */
	public static function createEntry($title)
	{
        $baseUrl = '/kyela/';
        $crawler = self::$client->request('GET', $baseUrl);

        $form = $crawler->selectButton(
        	self::$translator->trans('create'))
        	->form(['abienvenu_kyelabundle_newpoll[title]'  => $title]);
        self::$client->submit($form);
        return self::$client->followRedirect();
	}

	/**
	 * Deletes a poll
	 *
	 * @param Crawler $crawler
	 */
	public static function deleteEntry($crawler)
	{
        $link = $crawler->selectLink(self::$translator->trans('edit.poll'));
        $crawler = self::$client->click($link->link());
        self::$client->submit($crawler->selectButton(self::$translator->trans('delete'))->form());
        return self::$client->followRedirect();
	}

	/**
	 * Edits a poll
	 *
	 * @param Crawler $crawler
	 */
	public static function editEntry($crawler, $newtitle)
	{
        $link = $crawler->selectLink(self::$translator->trans('edit.poll'));
        $crawler = self::$client->click($link->link());
        $form = $crawler->selectButton(
        	self::$translator->trans('save'))
        	->form(['abienvenu_kyelabundle_poll[title]' => $newtitle]);
        self::$client->submit($form);
        return self::$client->followRedirect();
	}

    public function testCompleteScenario()
    {
    	// Create entry
        $title = uniqid('Test Poll ');
        $crawler = self::createEntry($title);

        // Check it was created
        $filter = 'h3.panel-title:contains("' . self::$translator->trans('success') . '")';
        $message = $crawler->filter($filter);
        $this->assertEquals(1, $message->count(), "Missing element $filter");
        $filter = 'a:contains("' . $title . '")';
        $linkToPoll = $crawler->filter($filter);
        $this->assertEquals(1, $linkToPoll->count(), "Missing element $filter");

        // Edit entry
        $newtitle = "$title 2";
        $crawler = self::editEntry($crawler, $newtitle);
        $filter = 'a:contains("' . $newtitle . '")';
        $linkToPoll = $crawler->filter($filter);
        $this->assertEquals(1, $linkToPoll->count(), "Missing element $filter");

        // Delete entry
        $crawler = self::deleteEntry($crawler);

        // Check it was deleted
        $filter = 'div.panel-body:contains("' . self::$translator->trans('deleted') . '")';
        $message = $crawler->filter($filter);
        $this->assertEquals(1, $message->count(), "Missing element $filter");
    }
}
