<?php

namespace Abienvenu\KyelaBundle\Tests\Controller;

class PollControllerTest extends PollWebTestCase
{
    public function testCompleteScenario()
    {
    	// Create entry
        $title = uniqid('Test Poll ');
        $crawler = self::createPollEntry($title);

        // Check it was created
        $filter = 'h3.panel-title:contains("' . self::$translator->trans('success') . '")';
        $message = $crawler->filter($filter);
        $this->assertEquals(1, $message->count(), "Missing element $filter");

        $this->checkElement($crawler, $filter);

        $filter = 'a:contains("' . $title . '")';
        $linkToPoll = $crawler->filter($filter);
        $this->assertEquals(1, $linkToPoll->count(), "Missing element $filter");

        // Edit entry
        $newtitle = "M $title";
		$crawler = self::clickLink($crawler, 'edit.poll');
		$crawler = self::submitForm($crawler, 'save', 'poll', ['title' => $newtitle]);

		// Check the edition worked
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
