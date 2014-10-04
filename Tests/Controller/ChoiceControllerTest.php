<?php
/**
 * Copyright 2014 Arnaud Bienvenu
 *
 * This file is part of Kyela.

 * Kyela is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * Kyela is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.

 * You should have received a copy of the GNU Affero General Public License
 * along with Kyela.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace Abienvenu\KyelaBundle\Tests\Controller;

class ChoiceControllerTest extends PollWebTestCase
{

    public function testCompleteScenario()
    {
    	// Create a poll to work with
    	$crawler = self::createPollEntry(uniqid('Test Poll '));

    	// Go to options list
    	$crawler = self::clickLink($crawler, 'edit.options');
    	$this->assertNotNull($crawler);

    	// Create a choice
    	$name = uniqid('Test Choice ');
        $crawler = self::clickLink($crawler, 'add.an.option');
        self::checkElement($crawler, 'h1:contains("' . self::$translator->trans('new.choice') . '")');
        $crawler = self::submitForm($crawler, 'create', 'choice', ['name' => $name, 'value' => 1, 'color' => 'green', 'icon' => 'ok']);

        // Check data in the show view
        $filter = 'tr:contains("' . $name . '")';
        $crawler = self::checkElement($crawler, $filter);

        // Edit the choice
        $crawler = self::clickLink($crawler, 'edit');
        $name = "M $name";
        $crawler = self::submitForm($crawler, 'save', 'choice', ['name' => $name, 'value' => 1, 'color' => 'green']);

        // Check data in the show view
        $filter = 'tr:contains("' . $name . '")';
        $crawler = self::checkElement($crawler, $filter);

        // Delete the choice
        $crawler = self::clickLink($crawler, 'edit');
        $crawler = self::submitForm($crawler, 'delete');

        // Check the choice has been deleted from the list
        $this->assertNotRegExp("/$name/", self::$client->getResponse()->getContent());

        // Go back to the poll
        $crawler = self::clickLink($crawler, 'back');

        // Delete the poll
        self::deletePollEntry($crawler);
    }
}
