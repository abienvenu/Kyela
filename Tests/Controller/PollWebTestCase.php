<?php

namespace Abienvenu\KyelaBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class PollWebTestCase extends WebTestCase
{
	protected static $client;
	protected static $translator;

	/**
	 * WebTestCase setup (automitically called by framework before any test)
	 */
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
	 *
	 * @return Crawler
	 */
	public static function clickLink(Crawler $crawler, $where)
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
	 *
	 * @return Crawler
	 */
	public static function submitForm(Crawler $crawler, $button, $formName = "", $formData = [])
	{
        $form = $crawler->selectButton(self::$translator->trans($button));
        foreach ($formData as $key => $value)
        {
        	$form = $form->form(['abienvenu_kyelabundle_' . $formName . "[$key]"  => $value]);
        }
        if (!$formData)
        {
        	$form = $form->form();
        }
        self::$client->submit($form);
        return self::$client->followRedirect();
	}

	/**
	 * Checks an element is present in the page
	 *
	 * @param Crawler $crawler
	 * @param string $filter CSS filter
	 * @param number $count number of expected occurences
	 */
	public function checkElement(Crawler $crawler, $filter, $count = 1)
	{
        $message = $crawler->filter($filter);
        $this->assertEquals($count, $message->count(), "Wrong count of element $filter");
	}

	/**
	 * Creates a poll
	 *
	 * @param string $name
	 *
	 * @return Crawler
	 */
	public static function createPollEntry($title)
	{
        $baseUrl = '/kyela/';
        $crawler = self::$client->request('GET', $baseUrl);
        return self::submitForm($crawler, 'create', 'newpoll', ['title' => $title]);
	}

	/**
	 * Deletes a poll
	 *
	 * @param Crawler $crawler
	 *
	 * @return Crawler
	 */
	public static function deletePollEntry(Crawler $crawler)
	{
		$crawler = self::clickLink($crawler, 'edit.poll');
		return self::submitForm($crawler, 'delete');
	}
}
