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

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Translation\TranslatorInterface;

class PollWebTestCase extends WebTestCase
{
    /** @var Client */
    protected static $client;
    /** @var TranslatorInterface */
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
     * @param string $button Translation key of the button name
     * @param string $formName Name of the template
     * @param array $formData Form data
     *
     * @return Crawler
     */
    public static function submitForm(Crawler $crawler, $button, $formName = "", $formData = [])
    {
        $crawler = $crawler->selectButton(self::$translator->trans($button));
        $data = [];
        foreach ($formData as $key => $value)
        {
            $data['abienvenu_kyelabundle_' . $formName . "[$key]"] = $value;
        }
        $form = $crawler->form($data);
        self::$client->submit($form);
        return self::$client->followRedirect();
    }

    /**
     * Checks an element is present in the page
     *
     * @param Crawler $crawler
     * @param string $filter CSS filter
     * @param integer $count number of expected occurences
     *
     * @return Crawler
     */
    public function checkElement(Crawler $crawler, $filter, $count = 1)
    {
        $crawler = $crawler->filter($filter);
        $this->assertEquals($count, $crawler->count(), "Wrong count of element $filter");
        return $crawler;
    }

    /**
     * Creates a poll
     *
     * @return Crawler
     */
    public static function createPollEntry()
    {
        $route = self::$client->getContainer()->get('router')->generate('poll_new');
        $crawler = self::$client->request('GET', $route);
        if (!$crawler)
        {
            throw new \Exception("Could not create crawler");
        }
        return self::submitForm($crawler, 'create', 'newpoll');
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
