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

class ParticipantControllerTest extends PollWebTestCase
{
    /**
     * Creates, edits and deletes a participant
     */
    public function testCompleteScenario()
    {
        // Create a poll to work with
        $crawler = self::createPollEntry();

        // Create a participant
        $name = uniqid('Test Participant ');
        $crawler = self::submitForm($crawler, 'add.a.participant', 'participant', ['name' => $name]);

        // Check data in the show view
        $filter = 'a:contains("' . $name . '")';
        self::checkElement($crawler, $filter);

        // Edit the participant
        $crawler = self::clickLink($crawler, $name);
        $name = "M $name";
        $crawler = self::submitForm($crawler, 'save', 'participant', ['name' => $name]);

        // Check data in the show view
        $filter = 'a:contains("' . $name . '")';
        self::checkElement($crawler, $filter);

        // Delete the participant
        $crawler = self::clickLink($crawler, $name);
        $crawler = self::submitForm($crawler, 'delete');

        // Check the participant has been deleted from the list
        $this->assertNotRegExp("/$name/", self::$client->getResponse()->getContent());

        // Delete the poll
        self::deletePollEntry($crawler);
    }
}
