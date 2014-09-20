<?php

namespace Abienvenu\KyelaBundle\Tests\Controller;

class PollControllerTest extends PollWebTestCase
{
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
        $crawler = self::createPollEntry($title);

        // Check it was created
        $filter = 'h3.panel-title:contains("' . self::$translator->trans('success') . '")';
        $message = $crawler->filter($filter);
        $this->assertEquals(1, $message->count(), "Missing element $filter");
        $filter = 'a:contains("' . $title . '")';
        $linkToPoll = $crawler->filter($filter);
        $this->assertEquals(1, $linkToPoll->count(), "Missing element $filter");

        // Edit entry
        $newtitle = "M $title";
        $crawler = self::editEntry($crawler, $newtitle);
        $filter = 'a:contains("' . $newtitle . '")';
        $linkToPoll = $crawler->filter($filter);
        $this->assertEquals(1, $linkToPoll->count(), "Missing element $filter");

        // Delete entry
        $crawler = self::deletePollEntry($crawler);

        // Check it was deleted
        $filter = 'div.panel-body:contains("' . self::$translator->trans('deleted') . '")';
        $message = $crawler->filter($filter);
        $this->assertEquals(1, $message->count(), "Missing element $filter");
    }
}
