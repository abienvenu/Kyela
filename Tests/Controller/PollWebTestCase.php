<?php

namespace Abienvenu\KyelaBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PollWebTestCase extends WebTestCase
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
	public static function createPollEntry($title)
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
	public static function deletePollEntry($crawler)
	{
        $link = $crawler->selectLink(self::$translator->trans('edit.poll'));
        $crawler = self::$client->click($link->link());
        self::$client->submit($crawler->selectButton(self::$translator->trans('delete'))->form());
        return self::$client->followRedirect();
	}
}
