<?php
/*
 * Copyright 2014-2017 Arnaud Bienvenu
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

class EventControllerTest extends PollWebTestCase
{
    /**
     * Creates, edits and deletes an event
     */
    public function testCompleteScenario()
    {
        // Create a poll to work with
        $crawler = self::createPollEntry(uniqid('Test Poll '));

        // Create an event
        $name = uniqid('Test Event ');
        $crawler = self::clickLink($crawler, 'add.a.date');
        $crawler = self::submitForm($crawler, 'create', 'event',
            ['name' => $name, 'place' => 'Nowhere', 'date' => date('d-m-Y', strtotime("tomorrow")), 'time' => '20:30']);

        // Check data in the show view
        $filter = 'div.list-group-item:contains("' . $name . '")';
        $crawler = self::checkElement($crawler, $filter);

        // Edit the event
        $crawler = self::clickLink($crawler, '');
        $name = "M $name";
        $crawler = self::submitForm($crawler, 'save', 'event',
            ['name' => $name, 'place' => 'Nowhere', 'date' => '04-09-2034', 'time' => '20:30']);

        // Check data in the show view
        $filter = 'div.list-group-item:contains("' . $name . '")';
        $crawler = self::checkElement($crawler, $filter);

        // Delete the event
        $crawler = self::clickLink($crawler, '');
        $crawler = self::submitForm($crawler, 'delete');

        // Check the participant has been deleted from the list
        $this->assertNotRegExp("/$name/", self::$client->getResponse()->getContent());

        // Delete the poll
        self::deletePollEntry($crawler);
    }
}
