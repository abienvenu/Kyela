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

namespace Abienvenu\KyelaBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Abienvenu\KyelaBundle\Entity\Poll;
use Abienvenu\KyelaBundle\Entity\Event;
use Abienvenu\KyelaBundle\Entity\Choice;
use Abienvenu\KyelaBundle\Entity\Participant;
use Abienvenu\KyelaBundle\Entity\Participation;

class LoadExamplePolls implements FixtureInterface
{
    protected function loadConcert(ObjectManager $manager)
    {
        $poll = $this->resetPoll($manager, 'concert', 'Prochaines répétitions');
        $names = ['Aretha', 'Jimmy', 'Miles', 'John', 'Paul'];
        $participantsObj = [];
        foreach ($names as $name)
        {
            $participant = (new Participant)->setName($name)->setPoll($poll);
            $manager->persist($participant);
            $participantsObj[$name] = $participant;
        }

        $events = [
            ['name' => 'Répétition', 'place' => 'Studio', 'date' => new \DateTime('+10 year'), 'time' => new \DateTime('15:00')],
            ['name' => 'Générale', 'place' => 'Olympia', 'date' => new \DateTime('+10 year +2 days'), 'time' => new \DateTime('20:30')],
            ['name' => 'Concert', 'place' => 'Olympia', 'date' => new \DateTime('+10 year +7 days'), 'time' => new \DateTime('20:00')],
            ['name' => 'Concert 2', 'place' => 'New-York', 'date' => new \DateTime('+10 year + 10 days'), 'time' => new \DateTime('20:30')],
        ];
        $eventsObj = [];
        foreach ($events as $event)
        {
            $eventObj = (new Event)
                ->setName($event['name'])
                ->setPlace($event['place'])
                ->setDate($event['date'])
                ->setTime($event['time'])
                ->setPoll($poll);
            $manager->persist($eventObj);
            $eventsObj[$event['name']] = $eventObj;
        }

        $choices = [
            ['name' => 'Oui', 'value' => 1, 'color' => 'green', 'priority' => 0, 'icon' => 'ok'],
            ['name' => 'Non', 'value' => 0, 'color' => 'red', 'priority' => 1, 'icon' => 'remove'],
            ['name' => 'Peut-être', 'value' => 0, 'color' => 'gray', 'priority' => 2, 'icon' => 'time'],
        ];
        $choicesObj = [];
        foreach ($choices as $choice)
        {
            $choiceObj = (new Choice)
                ->setName($choice['name'])
                ->setValue($choice['value'])
                ->setColor($choice['color'])
                ->setPriority($choice['priority'])
                ->setIcon($choice['icon'])
                ->setPoll($poll);
            $manager->persist($choiceObj);
            $choicesObj[$choice['name']] = $choiceObj;
        }

        $participations = [
            ['who' => 'Aretha', 'when' => 'Répétition', 'choice' => 'Oui'],
            ['who' => 'Jimmy', 'when' => 'Répétition', 'choice' => 'Oui'],
            ['who' => 'Miles', 'when' => 'Répétition', 'choice' => 'Non'],
            ['who' => 'John', 'when' => 'Répétition', 'choice' => 'Oui'],
            ['who' => 'Paul', 'when' => 'Répétition', 'choice' => 'Oui'],
            ['who' => 'Aretha', 'when' => 'Générale', 'choice' => 'Peut-être'],
            ['who' => 'Jimmy', 'when' => 'Générale', 'choice' => 'Oui'],
            ['who' => 'Miles', 'when' => 'Générale', 'choice' => 'Oui'],
            ['who' => 'John', 'when' => 'Générale', 'choice' => 'Oui'],
            ['who' => 'Paul', 'when' => 'Générale', 'choice' => 'Oui'],
            ['who' => 'Aretha', 'when' => 'Concert', 'choice' => 'Oui'],
            ['who' => 'Jimmy', 'when' => 'Concert', 'choice' => 'Oui'],
            ['who' => 'Miles', 'when' => 'Concert', 'choice' => 'Oui'],
            ['who' => 'John', 'when' => 'Concert', 'choice' => 'Oui'],
            ['who' => 'Paul', 'when' => 'Concert', 'choice' => 'Non'],
            ['who' => 'Aretha', 'when' => 'Concert 2', 'choice' => 'Non'],
            ['who' => 'Jimmy', 'when' => 'Concert 2', 'choice' => 'Non'],
            ['who' => 'John', 'when' => 'Concert 2', 'choice' => 'Oui'],
            ['who' => 'Paul', 'when' => 'Concert 2', 'choice' => 'Peut-être'],
        ];
        foreach ($participations as $row)
        {
            $participation = (new Participation)
                ->setParticipant($participantsObj[$row['who']])
                ->setEvent($eventsObj[$row['when']])
                ->setChoice($choicesObj[$row['choice']]);
            $manager->persist($participation);
        }

    }

    protected function loadPicnic(ObjectManager $manager)
    {
        $poll = $this->resetPoll($manager, 'picnic', 'Pique-nique', 'Pour le pique-unique, merci d\'indiquer si vous apportez du salé, du sucré, ou des boissons.');
        $names = ['Élise', 'Jules', 'Marie', 'Romain', 'Margaux'];
        $participantsObj = [];
        foreach ($names as $name)
        {
            $participant = (new Participant)->setName($name)->setPoll($poll);
            $manager->persist($participant);
            $participantsObj[$name] = $participant;
        }

        $events = [
            ['name' => 'Date 1', 'place' => 'Central Park', 'date' => new \DateTime('+10 year'), 'time' => new \DateTime('12:00')],
            ['name' => 'Date 2', 'place' => 'Central Park', 'date' => new \DateTime('+10 year +1 days'), 'time' => new \DateTime('12:00')],
        ];
        $eventsObj = [];
        foreach ($events as $event)
        {
            $eventObj = (new Event)
                ->setName($event['name'])
                ->setPlace($event['place'])
                ->setDate($event['date'])
                ->setTime($event['time'])
                ->setPoll($poll);
            $manager->persist($eventObj);
            $eventsObj[$event['name']] = $eventObj;
        }

        $choices = [
            ['name' => 'Sucré', 'value' => 1, 'color' => 'blue', 'priority' => 0, 'icon' => 'cutlery'],
            ['name' => 'Salé', 'value' => 1, 'color' => 'cyan', 'priority' => 1, 'icon' => 'cutlery'],
            ['name' => 'Boisson', 'value' => 1, 'color' => 'purple', 'priority' => 2, 'icon' => 'glass'],
            ['name' => 'Absent', 'value' => 0, 'color' => 'gray', 'priority' => 3, 'icon' => 'plane'],
        ];
        $choicesObj = [];
        foreach ($choices as $choice)
        {
            $choiceObj = (new Choice)
                ->setName($choice['name'])
                ->setValue($choice['value'])
                ->setColor($choice['color'])
                ->setPriority($choice['priority'])
                ->setIcon($choice['icon'])
                ->setPoll($poll);
            $manager->persist($choiceObj);
            $choicesObj[$choice['name']] = $choiceObj;
        }

        $participations = [
            ['who' => 'Élise', 'when' => 'Date 1', 'choice' => 'Sucré'],
            ['who' => 'Jules', 'when' => 'Date 1', 'choice' => 'Salé'],
            ['who' => 'Marie', 'when' => 'Date 1', 'choice' => 'Boisson'],
            ['who' => 'Romain', 'when' => 'Date 1', 'choice' => 'Sucré'],
            ['who' => 'Margaux', 'when' => 'Date 1', 'choice' => 'Salé'],
            ['who' => 'Élise', 'when' => 'Date 2', 'choice' => 'Sucré'],
            ['who' => 'Jules', 'when' => 'Date 2', 'choice' => 'Absent'],
            ['who' => 'Marie', 'when' => 'Date 2', 'choice' => 'Boisson'],
            ['who' => 'Romain', 'when' => 'Date 2', 'choice' => 'Sucré'],
            ['who' => 'Margaux', 'when' => 'Date 2', 'choice' => 'Salé'],
        ];
        foreach ($participations as $row)
        {
            $participation = (new Participation)
                ->setParticipant($participantsObj[$row['who']])
                ->setEvent($eventsObj[$row['when']])
                ->setChoice($choicesObj[$row['choice']]);
            $manager->persist($participation);
        }
    }

    protected function loadHolidays(ObjectManager $manager)
    {
        $poll = $this->resetPoll($manager, 'holidays', 'Vacances', 'Où souhaitez-vous partir ?');
        $names = ['Jean', 'Lisa', 'Étienne', 'Martine', 'Lilou'];
        $participantsObj = [];
        foreach ($names as $name)
        {
            $participant = (new Participant)->setName($name)->setPoll($poll);
            $manager->persist($participant);
            $participantsObj[$name] = $participant;
        }

        $events = [
            ['name' => 'Vacances de février'],
            ['name' => 'Vacances de Pâques'],
            ['name' => 'Vacances d\'été'],
        ];
        $eventsObj = [];
        foreach ($events as $event)
        {
            $eventObj = (new Event)
                ->setName($event['name'])
                ->setPoll($poll);
            $manager->persist($eventObj);
            $eventsObj[$event['name']] = $eventObj;
        }

        $choices = [
            ['name' => 'Les Alpes', 'value' => 1, 'color' => 'cyan', 'priority' => 0, 'icon' => 'tree-conifer'],
            ['name' => 'Les Pyrénées', 'value' => 1, 'color' => 'blue', 'priority' => 1, 'icon' => 'tree-conifer'],
            ['name' => 'Les Canaries', 'value' => 1, 'color' => 'orange', 'priority' => 2, 'icon' => 'plane'],
            ['name' => 'Peu importe', 'value' => 1, 'color' => 'purple', 'priority' => 2, 'icon' => 'globe'],
            ['name' => 'Je ne pars pas', 'value' => 0, 'color' => 'gray', 'priority' => 3, 'icon' => 'home'],
        ];
        $choicesObj = [];
        foreach ($choices as $choice)
        {
            $choiceObj = (new Choice)
                ->setName($choice['name'])
                ->setValue($choice['value'])
                ->setColor($choice['color'])
                ->setPriority($choice['priority'])
                ->setIcon($choice['icon'])
                ->setPoll($poll);
            $manager->persist($choiceObj);
            $choicesObj[$choice['name']] = $choiceObj;
        }

        $participations = [
            ['who' => 'Jean', 'when' => 'Vacances de février', 'choice' => 'Les Alpes'],
            ['who' => 'Lisa', 'when' => 'Vacances de février', 'choice' => 'Les Alpes'],
            ['who' => 'Étienne', 'when' => 'Vacances de février', 'choice' => 'Les Canaries'],
            ['who' => 'Martine', 'when' => 'Vacances de février', 'choice' => 'Les Pyrénées'],
            ['who' => 'Lilou', 'when' => 'Vacances de février', 'choice' => 'Les Alpes'],
            ['who' => 'Jean', 'when' => 'Vacances de Pâques', 'choice' => 'Les Alpes'],
            ['who' => 'Lisa', 'when' => 'Vacances de Pâques', 'choice' => 'Je ne pars pas'],
            ['who' => 'Étienne', 'when' => 'Vacances de Pâques', 'choice' => 'Les Canaries'],
            ['who' => 'Martine', 'when' => 'Vacances de Pâques', 'choice' => 'Les Pyrénées'],
            ['who' => 'Lilou', 'when' => 'Vacances de Pâques', 'choice' => 'Je ne pars pas'],
            ['who' => 'Jean', 'when' => 'Vacances d\'été', 'choice' => 'Les Canaries'],
            ['who' => 'Lisa', 'when' => 'Vacances d\'été', 'choice' => 'Les Canaries'],
            ['who' => 'Étienne', 'when' => 'Vacances d\'été', 'choice' => 'Les Canaries'],
            ['who' => 'Martine', 'when' => 'Vacances d\'été', 'choice' => 'Peu importe'],
            ['who' => 'Lilou', 'when' => 'Vacances d\'été', 'choice' => 'Les Canaries'],
        ];
        foreach ($participations as $row)
        {
            $participation = (new Participation)
                ->setParticipant($participantsObj[$row['who']])
                ->setEvent($eventsObj[$row['when']])
                ->setChoice($choicesObj[$row['choice']]);
            $manager->persist($participation);
        }
    }

    protected function resetPoll(ObjectManager $manager, $url, $title, $headLines = '', $bottomLines = '')
    {
        $entity = $manager->getRepository('KyelaBundle:Poll')->findOneByUrl($url);
        if ($entity) {
            $manager->remove($entity);
            $manager->flush();
        }

        $poll = (new Poll)
            ->setUrl($url)
            ->setTitle($title)
            ->setHeadLines($headLines)
            ->setBottomLines($bottomLines)
            ->setAccessCode('kode');
        $manager->persist($poll);
        return $poll;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->loadConcert($manager);
        $this->loadPicnic($manager);
        $this->loadHolidays($manager);
        $manager->flush();
    }
}
