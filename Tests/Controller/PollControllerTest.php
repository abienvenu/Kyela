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
