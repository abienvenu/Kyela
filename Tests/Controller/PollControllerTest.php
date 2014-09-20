<?php

namespace Abienvenu\KyelaBundle\Tests\Controller;

class PollControllerTest extends PollWebTestCase
{
	/**
	 * Creates, edits and deletes a poll
	 */
    public function testCompleteScenario()
    {
    	// Create entry
        $title = uniqid('Test Poll ');
        $crawler = self::createPollEntry($title);

        // Check it was created
        $filter = 'h3.panel-title:contains("' . self::$translator->trans('success') . '")';
        $this->checkElement($crawler, $filter);
        $filter = 'a:contains("' . $title . '")';
        $this->checkElement($crawler, $filter);

        // Edit entry
        $newtitle = "M $title";
		$crawler = self::clickLink($crawler, 'edit.poll');
		$crawler = self::submitForm($crawler, 'save', 'poll', ['title' => $newtitle]);

		// Check the edition worked
        $filter = 'a:contains("' . $newtitle . '")';
        $this->checkElement($crawler, $filter);

        // Delete entry
        $crawler = self::deletePollEntry($crawler);

        // Check it was deleted
        $filter = 'div.panel-body:contains("' . self::$translator->trans('deleted') . '")';
        $this->checkElement($crawler, $filter);
    }
}
