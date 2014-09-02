<?php

namespace Abienvenu\KyelaBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ChoiceControllerTest extends WebTestCase
{
    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = static::createClient();
        $t = $client->getContainer()->get('translator');

        // Create a new entry in the database
        $crawler = $client->request('GET', '/kyela/choice/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /kyela/choice/");
        $link = $crawler->selectLink($t->trans('add.an.option'))->link();
        $crawler = $client->click($link);

        // Fill in the form and submit it
        $name = uniqid('Test Choice ');
        $form = $crawler->selectButton($t->trans('create'))->form(array(
            'abienvenu_kyelabundle_choice[name]'  => $name,
        	'abienvenu_kyelabundle_choice[value]'  => 0,
        	'abienvenu_kyelabundle_choice[color]'  => '#000000',
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $entryRow = $crawler->filter('tr:contains("' . $name . '")');
        $this->assertEquals(1, $entryRow->count(), 'Missing element tr:contains("' . $name . '")');

        // Edit the entity
        $crawler = $client->click($entryRow->filter('a')->link());

        $name = "M $name";
        $form = $crawler->selectButton($t->trans('save'))->form(array(
            'abienvenu_kyelabundle_choice[name]'  => $name,
        	'abienvenu_kyelabundle_choice[value]'  => 0,
        	'abienvenu_kyelabundle_choice[color]'  => '#000000',
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $entryRow = $crawler->filter('tr:contains("' . $name . '")');
        $this->assertEquals(1, $entryRow->count(), 'Missing element tr:contains("' . $name . '")');

        // Delete the entity
        $crawler = $client->click($entryRow->filter('a')->link());
        $client->submit($crawler->selectButton($t->trans('delete'))->form());
        $crawler = $client->followRedirect();

        // Check the entity has been delete on the list
        $this->assertNotRegExp("/$name/", $client->getResponse()->getContent());
    }
}
