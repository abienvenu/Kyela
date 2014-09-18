<?php

namespace Abienvenu\KyelaBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EventControllerTest extends WebTestCase
{
    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = static::createClient();
        $t = $client->getContainer()->get('translator');

        // Create a new entry in the database
        $crawler = $client->request('GET', '/kyela/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /kyela/");
        $link = $crawler->selectLink('Proposer une date')->link();
        $crawler = $client->click($link);

        // Fill in the form and submit it
        $name = uniqid('Test Event ');
        $datetime = [
        	'abienvenu_kyelabundle_event[datetime][date][day]' => '2',
        	'abienvenu_kyelabundle_event[datetime][date][month]' => '3',
        	'abienvenu_kyelabundle_event[datetime][date][year]' => '2014',
        	'abienvenu_kyelabundle_event[datetime][time][hour]' => '20',
        	'abienvenu_kyelabundle_event[datetime][time][minute]' => '30',
        ];
        $form = $crawler->selectButton($t->trans('create'))->form(['abienvenu_kyelabundle_event[name]'  => $name] + $datetime);
        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $link = $crawler->filter('li:contains("' . $name . '")');
        $this->assertEquals(1, $link->count(), 'Missing element li:contains("' . $name . '")');

        // Edit the entity
        $crawler = $client->click($link->filter('a')->link());

        $name = "M $name";
        $form = $crawler->selectButton($t->trans('save'))->form(['abienvenu_kyelabundle_event[name]'  => $name] + $datetime);
        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $link = $crawler->filter('li:contains("' . $name . '")');
        $this->assertEquals(1, $link->count(), 'Missing element li:contains("' . $name . '")');

        // Delete the entity
        $crawler = $client->click($link->filter('a')->link());
        $client->submit($crawler->selectButton($t->trans('delete'))->form());
        $client->followRedirect();

        // Check the entity has been delete on the list
        $this->assertNotRegExp("/$name/", $client->getResponse()->getContent());
    }
}
