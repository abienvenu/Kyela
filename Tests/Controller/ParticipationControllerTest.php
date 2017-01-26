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

class ParticipationControllerTest extends PollWebTestCase
{
    /**
     * Creates, edits and deletes a participation
     */
    public function testCompleteScenario()
    {
        // Create a poll to work with
        $crawler = self::createPollEntry(uniqid('Test Poll '));

        // Create a participant
        $participant = uniqid('Test Participant ');
        $crawler = self::submitForm($crawler, 'add.a.participant', 'participant', ['name' => $participant]);

        // Create an event
        $event = uniqid('Test Event ');
        $crawler = self::clickLink($crawler, 'add.a.date');
        $crawler = self::submitForm($crawler, 'create', 'event',
            ['name' => $event, 'place' => 'Nowhere', 'date' => date('d-m-Y', strtotime("tomorrow")), 'time' => '20:30']);

        // Check "yes" is not selected
        $filter = 'button:contains("' . self::$translator->trans('yes') . '")';
        self::checkElement($crawler, $filter, 1);

        // Choose "yes"
        $button = $crawler->selectButton(self::$translator->trans('yes'));
        $url = $button->extract(['data-url'])[0];
        self::$client->request('GET', $url);
        self::$client->back();
        $crawler = self::$client->reload();

        // Check "yes is selected
        $filter = 'button:contains("' . self::$translator->trans('yes') . '")';
        self::checkElement($crawler, $filter, 2);

        // Choose "no"
        $button = $crawler->selectButton(self::$translator->trans('no'));
        $url = $button->extract(['data-url'])[0];
        self::$client->request('GET', $url);
        self::$client->back();
        $crawler = self::$client->reload();

        // Check "yes" is not selected anymore
        $filter = 'button:contains("' . self::$translator->trans('yes') . '")';
        self::checkElement($crawler, $filter, 1);

        // Check "no" is selected
        $filter = 'button:contains("' . self::$translator->trans('no') . '")';
        self::checkElement($crawler, $filter, 2);

        // Delete the poll
        self::deletePollEntry($crawler);
    }
}
