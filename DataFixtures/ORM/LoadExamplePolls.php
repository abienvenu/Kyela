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
        $names = ['Aretha', 'Jimmy', 'Miles', 'John', 'Paul'];
        $events = [
            ['name' => 'Répétition', 'place' => 'Studio', 'date' => new \DateTime('+10 year'), 'time' => new \DateTime('15:00')],
            ['name' => 'Générale', 'place' => 'Olympia', 'date' => new \DateTime('+10 year +2 days'), 'time' => new \DateTime('20:30')],
            ['name' => 'Concert', 'place' => 'Olympia', 'date' => new \DateTime('+10 year +7 days'), 'time' => new \DateTime('20:00')],
            ['name' => 'Concert 2', 'place' => 'New-York', 'date' => new \DateTime('+10 year + 10 days'), 'time' => new \DateTime('20:30')],
        ];
        $choices = [
            ['name' => 'Oui', 'value' => 1, 'color' => 'green', 'priority' => 0, 'icon' => 'ok'],
            ['name' => 'Non', 'value' => 0, 'color' => 'red', 'priority' => 1, 'icon' => 'remove'],
            ['name' => 'Peut-être', 'value' => 0, 'color' => 'gray', 'priority' => 2, 'icon' => 'time'],
        ];
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
        $this->resetPoll($manager, 'concert', 'Prochaines répétitions',
            "Ceci est un sondage d'exemple pour l'organisation d'un concert.
            N'hésitez pas à l'essayer, mais les données seront automatiquement réinitialisées chaque matin.
            Pour créer votre propre sondage, retournez sur la page d'accueil en cliquant sur \"Kyélà\" en haut à gauche.",
            '', $names, $events, $choices, $participations);
    }

    protected function loadPicnic(ObjectManager $manager)
    {
        $names = ['Élise', 'Jules', 'Marie', 'Romain', 'Margaux'];
        $events = [
            ['name' => 'Date 1', 'place' => 'Central Park', 'date' => new \DateTime('+10 year'), 'time' => new \DateTime('12:00')],
            ['name' => 'Date 2', 'place' => 'Central Park', 'date' => new \DateTime('+10 year +1 days'), 'time' => new \DateTime('12:00')],
        ];
        $choices = [
            ['name' => 'Sucré', 'value' => 1, 'color' => 'blue', 'priority' => 0, 'icon' => 'cutlery'],
            ['name' => 'Salé', 'value' => 1, 'color' => 'cyan', 'priority' => 1, 'icon' => 'cutlery'],
            ['name' => 'Boisson', 'value' => 1, 'color' => 'purple', 'priority' => 2, 'icon' => 'glass'],
            ['name' => 'Absent', 'value' => 0, 'color' => 'gray', 'priority' => 3, 'icon' => 'plane'],
        ];
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
        $this->resetPoll($manager, 'picnic', 'Pique-nique',
            "Ceci est un sondage d'exemple pour l'organisation d'un pique-nique.
            N'hésitez pas à l'essayer, mais les données seront automatiquement réinitialisées chaque matin.
            Pour créer votre propre sondage, retournez sur la page d'accueil en cliquant sur \"Kyélà\" en haut à gauche.",
            '', $names, $events, $choices, $participations);
    }

    protected function loadHolidays(ObjectManager $manager)
    {
        $names = ['Jean', 'Lisa', 'Étienne', 'Martine', 'Lilou'];
        $events = [
            ['name' => 'Vacances de février'],
            ['name' => 'Vacances de Pâques'],
            ['name' => 'Vacances d\'été'],
        ];
        $choices = [
            ['name' => 'Les Alpes', 'value' => 1, 'color' => 'cyan', 'priority' => 0, 'icon' => 'tree-conifer'],
            ['name' => 'Les Pyrénées', 'value' => 1, 'color' => 'blue', 'priority' => 1, 'icon' => 'tree-conifer'],
            ['name' => 'Les Canaries', 'value' => 1, 'color' => 'orange', 'priority' => 2, 'icon' => 'plane'],
            ['name' => 'Peu importe', 'value' => 1, 'color' => 'purple', 'priority' => 2, 'icon' => 'globe'],
            ['name' => 'Je ne pars pas', 'value' => 0, 'color' => 'gray', 'priority' => 3, 'icon' => 'home'],
        ];
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
        $this->resetPoll($manager, 'holidays', 'Vacances',
            "Ceci est un sondage d'exemple pour l'organisation de vacances entre amis ou en famille.
            N'hésitez pas à l'essayer, mais les données seront automatiquement réinitialisées chaque matin.
            Pour créer votre propre sondage, retournez sur la page d'accueil en cliquant sur \"Kyélà\" en haut à gauche.",
            'Où souhaitez-vous partir ?',
            $names, $events, $choices, $participations);
    }

    protected function loadVolley(ObjectManager $manager)
    {
        $names = ['Jean', 'Marilou', 'Mehdi', 'Élodie', 'Cécile', 'Vincent', 'Patricia', 'Cyril', 'Lucie'];
        $events = [
            ['name' => 'Entraînement', 'date' => new \DateTime('+10 year'), 'time' => new \DateTime('20:30')],
            ['name' => 'Chanteloup 1 / Bourg-des-Comptes 1', 'place' => 'Chanteloup', 'date' => new \DateTime('+10 year + 7 days'), 'time' => new \DateTime('20:15')],
            ['name' => 'Entraînement ', 'date' => new \DateTime('+10 year + 14 days'), 'time' => new \DateTime('20:30')],
            ['name' => 'Bruz 2 / Chanteloup 1', 'place' => 'Bruz', 'date' => new \DateTime('+10 year + 14 days'), 'time' => new \DateTime('20:00')],
        ];
        $choices = [
            ['name' => 'Oui', 'value' => 1, 'color' => 'green', 'priority' => 0, 'icon' => 'ok'],
            ['name' => 'Non', 'value' => 0, 'color' => 'red', 'priority' => 1, 'icon' => 'remove'],
            ['name' => 'Si nécessaire', 'value' => 0, 'color' => 'orange', 'priority' => 2, 'icon' => 'phone'],
            ['name' => 'Arbitre', 'value' => 0, 'color' => 'gray', 'priority' => 3, 'icon' => 'bullhorn'],
            ['name' => 'Gâteau!', 'value' => 1, 'color' => 'purple', 'priority' => 4, 'icon' => 'cutlery'],
            ['name' => 'Chauffeur', 'value' => 1, 'color' => 'green', 'priority' => 5, 'icon' => 'road'],
        ];
        $participations = [
            ['who' => 'Jean', 'when' => 'Entraînement', 'choice' => 'Oui'],
            ['who' => 'Marilou', 'when' => 'Entraînement', 'choice' => 'Non'],
            ['who' => 'Mehdi', 'when' => 'Entraînement', 'choice' => 'Oui'],
            ['who' => 'Élodie', 'when' => 'Entraînement', 'choice' => 'Oui'],
            ['who' => 'Cécile', 'when' => 'Entraînement', 'choice' => 'Oui'],
            ['who' => 'Vincent', 'when' => 'Entraînement', 'choice' => 'Non'],
            ['who' => 'Patricia', 'when' => 'Entraînement', 'choice' => 'Oui'],
            ['who' => 'Cyril', 'when' => 'Entraînement', 'choice' => 'Oui'],
            ['who' => 'Lucie', 'when' => 'Entraînement', 'choice' => 'Oui'],
            ['who' => 'Jean', 'when' => 'Chanteloup 1 / Bourg-des-Comptes 1', 'choice' => 'Oui'],
            ['who' => 'Marilou', 'when' => 'Chanteloup 1 / Bourg-des-Comptes 1', 'choice' => 'Arbitre'],
            ['who' => 'Mehdi', 'when' => 'Chanteloup 1 / Bourg-des-Comptes 1', 'choice' => 'Non'],
            ['who' => 'Élodie', 'when' => 'Chanteloup 1 / Bourg-des-Comptes 1', 'choice' => 'Gâteau!'],
            ['who' => 'Cécile', 'when' => 'Chanteloup 1 / Bourg-des-Comptes 1', 'choice' => 'Oui'],
            ['who' => 'Vincent', 'when' => 'Chanteloup 1 / Bourg-des-Comptes 1', 'choice' => 'Non'],
            ['who' => 'Patricia', 'when' => 'Chanteloup 1 / Bourg-des-Comptes 1', 'choice' => 'Oui'],
            ['who' => 'Cyril', 'when' => 'Chanteloup 1 / Bourg-des-Comptes 1', 'choice' => 'Oui'],
            ['who' => 'Lucie', 'when' => 'Chanteloup 1 / Bourg-des-Comptes 1', 'choice' => 'Si nécessaire'],
            ['who' => 'Jean', 'when' => 'Entraînement ', 'choice' => 'Oui'],
            ['who' => 'Marilou', 'when' => 'Entraînement ', 'choice' => 'Oui'],
            ['who' => 'Mehdi', 'when' => 'Entraînement ', 'choice' => 'Non'],
            ['who' => 'Élodie', 'when' => 'Entraînement ', 'choice' => 'Non'],
            ['who' => 'Cécile', 'when' => 'Entraînement ', 'choice' => 'Oui'],
            ['who' => 'Vincent', 'when' => 'Entraînement ', 'choice' => 'Oui'],
            ['who' => 'Cyril', 'when' => 'Entraînement ', 'choice' => 'Oui'],
            ['who' => 'Lucie', 'when' => 'Entraînement ', 'choice' => 'Oui'],
            ['who' => 'Jean', 'when' => 'Bruz 2 / Chanteloup 1', 'choice' => 'Oui'],
            ['who' => 'Marilou', 'when' => 'Bruz 2 / Chanteloup 1', 'choice' => 'Oui'],
            ['who' => 'Mehdi', 'when' => 'Bruz 2 / Chanteloup 1', 'choice' => 'Chauffeur'],
            ['who' => 'Élodie', 'when' => 'Bruz 2 / Chanteloup 1', 'choice' => 'Oui'],
            ['who' => 'Cécile', 'when' => 'Bruz 2 / Chanteloup 1', 'choice' => 'Non'],
            ['who' => 'Vincent', 'when' => 'Bruz 2 / Chanteloup 1', 'choice' => 'Si nécessaire'],
            ['who' => 'Patricia', 'when' => 'Bruz 2 / Chanteloup 1', 'choice' => 'Oui'],
            ['who' => 'Cyril', 'when' => 'Bruz 2 / Chanteloup 1', 'choice' => 'Chauffeur'],
        ];
        $this->resetPoll($manager, 'volleyball', 'Volley', "
            Ceci est un sondage d'exemple pour l'organisation de matchs de volley.
            N'hésitez pas à l'essayer, mais les données seront automatiquement réinitialisées chaque matin.
            Pour créer votre propre sondage, retournez sur la page d'accueil en cliquant sur \"Kyélà\" en haut à gauche.
<ul>
<li><a href='http://umap.openstreetmap.fr/fr/map/volley-35_2960#11/47.9607/-1.6703'>Carte des salles de volley</a></li>
<li>Résultats et planning : <a href='http://www.ffvbbeach.org/ffvbapp/resu/vbspo_calendrier.php?saison=2014/2015&codent=PTBR35&poule=XSH'>Chanteloup 1</a> --
<a href='http://www.ffvbbeach.org/ffvbapp/resu/vbspo_calendrier.php?saison=2014/2015&codent=PTBR35&poule=XSI'>Chanteloup 2</a></li>
</ul>", '',
            $names, $events, $choices, $participations);
    }

    protected function resetPoll(ObjectManager $manager, $url, $title, $headLines, $bottomLines, $names, $events, $choices, $participations)
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

        $participantsObj = [];
        foreach ($names as $name)
        {
            $participant = (new Participant)->setName($name)->setPoll($poll);
            $manager->persist($participant);
            $participantsObj[$name] = $participant;
        }

        $eventsObj = [];
        foreach ($events as $event)
        {
            $eventObj = (new Event)
                ->setName(isset($event['name']) ? $event['name'] : null)
                ->setPlace(isset($event['place']) ? $event['place'] : null)
                ->setDate(isset($event['date']) ? $event['date'] : null)
                ->setTime(isset($event['time']) ? $event['time'] : null)
                ->setPoll($poll);
            $manager->persist($eventObj);
            $eventsObj[$event['name']] = $eventObj;
        }

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

        foreach ($participations as $row)
        {
            $participation = (new Participation)
                ->setParticipant($participantsObj[$row['who']])
                ->setEvent($eventsObj[$row['when']])
                ->setChoice($choicesObj[$row['choice']]);
            $manager->persist($participation);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->loadConcert($manager);
        $this->loadPicnic($manager);
        $this->loadHolidays($manager);
        $this->loadVolley($manager);
        $manager->flush();
    }
}
