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
	 * Clicks a link
	 *
	 * @param Crawler $crawler
	 * @param string $where Translation key of the link text
	 */
	public static function clickLink($crawler, $where)
	{
		$link = $crawler->selectLink(self::$translator->trans($where));
        return self::$client->click($link->link());
	}

	/**
	 * Clicks a button
	 *
	 * @param Crawler $crawler
	 * @param string $where Translation key of the button name
	 * @param string $formName Name of the template
	 * @param array $formData Form data
	 */
	public static function submitForm($crawler, $button, $formName, $formData = [])
	{
        $form = $crawler->selectButton(self::$translator->trans($button));
        foreach ($formData as $key => $value)
        {
        	$form = $form->form(['abienvenu_kyelabundle_' . $formName . "[$key]"  => $value]);
        }
        self::$client->submit($form);
        return self::$client->followRedirect();
	}

	public function checkElement($crawler, $filter, $count = 1)
	{
        $message = $crawler->filter($filter);
        $this->assertEquals($count, $message->count(), "Missing element $filter");
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
