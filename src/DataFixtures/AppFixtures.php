<?php

namespace App\DataFixtures;

use App\Entity\Choice;
use App\Entity\Event;
use App\Entity\Participant;
use App\Entity\Participation;
use App\Entity\Poll;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
	protected function loadConcert(ObjectManager $manager): void
	{
		$names = ['-- Voix --', 'Aretha', 'Édith', 'Michael', '-- Guitare --', 'Jimi', 'Eric', 'Carlos'];
		$events = [
			[
				'name' => 'Répétition',
				'place' => 'Studio',
				'date' => new \DateTime('+10 year'),
				'time' => new \DateTime('15:00'),
			],
			[
				'name' => 'Générale',
				'place' => 'Olympia',
				'date' => new \DateTime('+10 year +2 days'),
				'time' => new \DateTime('20:30'),
			],
			[
				'name' => 'Concert',
				'place' => 'Olympia',
				'date' => new \DateTime('+10 year +7 days'),
				'time' => new \DateTime('20:00'),
			],
			[
				'name' => 'Concert 2',
				'place' => 'New-York',
				'date' => new \DateTime('+10 year + 10 days'),
				'time' => new \DateTime('20:30'),
			],
		];
		$choices = [
			['name' => 'Oui', 'value' => 1, 'color' => 'green', 'priority' => 0, 'icon' => 'check'],
			['name' => 'Non', 'value' => 0, 'color' => 'red', 'priority' => 1, 'icon' => 'x'],
			['name' => 'Peut-être', 'value' => 0, 'color' => 'gray', 'priority' => 2, 'icon' => 'clock'],
		];
		$participations = [
			['who' => 'Aretha', 'when' => 'Répétition', 'choice' => 'Non'],
			['who' => 'Édith', 'when' => 'Répétition', 'choice' => 'Oui'],
			['who' => 'Michael', 'when' => 'Répétition', 'choice' => 'Non'],
			['who' => 'Jimi', 'when' => 'Répétition', 'choice' => 'Oui'],
			['who' => 'Eric', 'when' => 'Répétition', 'choice' => 'Oui'],
			['who' => 'Carlos', 'when' => 'Répétition', 'choice' => 'Non'],

			['who' => 'Aretha', 'when' => 'Générale', 'choice' => 'Oui'],
			['who' => 'Édith', 'when' => 'Générale', 'choice' => 'Oui'],
			['who' => 'Michael', 'when' => 'Générale', 'choice' => 'Non'],
			['who' => 'Jimi', 'when' => 'Générale', 'choice' => 'Non'],
			['who' => 'Eric', 'when' => 'Générale', 'choice' => 'Oui'],
			['who' => 'Carlos', 'when' => 'Générale', 'choice' => 'Oui'],

			['who' => 'Aretha', 'when' => 'Concert', 'choice' => 'Oui'],
			['who' => 'Édith', 'when' => 'Concert', 'choice' => 'Oui'],
			['who' => 'Michael', 'when' => 'Concert', 'choice' => 'Oui'],
			['who' => 'Jimi', 'when' => 'Concert', 'choice' => 'Oui'],
			['who' => 'Eric', 'when' => 'Concert', 'choice' => 'Oui'],
			['who' => 'Carlos', 'when' => 'Concert', 'choice' => 'Oui'],

			['who' => 'Aretha', 'when' => 'Concert 2', 'choice' => 'Oui'],
			['who' => 'Édith', 'when' => 'Concert 2', 'choice' => 'Non'],
			['who' => 'Michael', 'when' => 'Concert 2', 'choice' => 'Oui'],
			['who' => 'Jimi', 'when' => 'Concert 2', 'choice' => 'Oui'],
			['who' => 'Eric', 'when' => 'Concert 2', 'choice' => 'Oui'],
			['who' => 'Carlos', 'when' => 'Concert 2', 'choice' => 'Oui'],
		];
		$this->resetPoll(
			$manager,
			'concert',
			'Prochaines répétitions',
			"Ceci est un sondage d'exemple pour l'organisation d'un concert.
            N'hésitez pas à l'essayer, mais les données seront automatiquement réinitialisées chaque matin.
            Pour créer votre propre sondage, retournez sur la page d'accueil en cliquant sur \"Kyélà\" en haut à gauche.",
			'',
			$names,
			$events,
			$choices,
			$participations
		);
	}

	protected function loadPicnic(ObjectManager $manager): void
	{
		$names = ['Élise', 'Jules', 'Marie', 'Romain', 'Margaux'];
		$events = [
			[
				'name' => 'Date 1',
				'place' => 'Central Park',
				'date' => new \DateTime('+10 year'),
				'time' => new \DateTime('12:00'),
			],
			[
				'name' => 'Date 2',
				'place' => 'Central Park',
				'date' => new \DateTime('+10 year +1 days'),
				'time' => new \DateTime('12:00'),
			],
		];
		$choices = [
			['name' => 'Sucré', 'value' => 1, 'color' => 'blue', 'priority' => 0, 'icon' => 'cake'],
			['name' => 'Salé', 'value' => 1, 'color' => 'cyan', 'priority' => 1, 'icon' => 'cake'],
			['name' => 'Boisson', 'value' => 1, 'color' => 'purple', 'priority' => 2, 'icon' => 'cup-straw'],
			['name' => 'Absent', 'value' => 0, 'color' => 'gray', 'priority' => 3, 'icon' => 'airplane'],
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
		$this->resetPoll(
			$manager,
			'picnic',
			'Pique-nique',
			"Ceci est un sondage d'exemple pour l'organisation d'un pique-nique.
            N'hésitez pas à l'essayer, mais les données seront automatiquement réinitialisées chaque matin.
            Pour créer votre propre sondage, retournez sur la page d'accueil en cliquant sur \"Kyélà\" en haut à gauche.",
			'',
			$names,
			$events,
			$choices,
			$participations
		);
	}

	protected function loadHolidays(ObjectManager $manager): void
	{
		$names = ['Jean', 'Lisa', 'Étienne', 'Martine', 'Lilou'];
		$events = [
			['name' => 'Vacances de février'],
			['name' => 'Vacances de Pâques'],
			['name' => 'Vacances d\'été'],
		];
		$choices = [
			['name' => 'Les Alpes', 'value' => 1, 'color' => 'cyan', 'priority' => 0, 'icon' => 'tree'],
			['name' => 'Les Pyrénées', 'value' => 1, 'color' => 'blue', 'priority' => 1, 'icon' => 'tree'],
			['name' => 'Les Canaries', 'value' => 1, 'color' => 'orange', 'priority' => 2, 'icon' => 'airplane'],
			['name' => 'Peu importe', 'value' => 1, 'color' => 'purple', 'priority' => 2, 'icon' => 'globe'],
			['name' => 'Je ne pars pas', 'value' => 0, 'color' => 'gray', 'priority' => 3, 'icon' => 'house'],
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
		$this->resetPoll(
			$manager,
			'holidays',
			'Vacances',
			"Ceci est un sondage d'exemple pour l'organisation de vacances entre amis ou en famille.
            N'hésitez pas à l'essayer, mais les données seront automatiquement réinitialisées chaque matin.
            Pour créer votre propre sondage, retournez sur la page d'accueil en cliquant sur \"Kyélà\" en haut à gauche.",
			'Où souhaitez-vous partir ?',
			$names,
			$events,
			$choices,
			$participations
		);
	}

	protected function resetPoll(
		ObjectManager $manager,
		string $url,
		string $title,
		string $headLines,
		string $bottomLines,
		array $names,
		array $events,
		array $choices,
		array $participations
	): void {
		$entity = $manager->getRepository(Poll::class)->findOneBy(['url' => $url]);
		if ($entity) {
			$manager->remove($entity);
			$manager->flush();
		}

		$poll = (new Poll())
			->setUrl($url)
			->setTitle($title)
			->setHeadLines($headLines)
			->setBottomLines($bottomLines)
			->setAccessCode('kode');
		$manager->persist($poll);

		$participantsObj = [];
		$priority = 0;
		foreach ($names as $name) {
			$participant = (new Participant())->setName($name)->setPriority($priority++)->setPoll($poll);
			$manager->persist($participant);
			$participantsObj[$name] = $participant;
		}

		$eventsObj = [];
		foreach ($events as $event) {
			$eventObj = (new Event())
				->setName($event['name'] ?? null)
				->setPlace($event['place'] ?? null)
				->setDate($event['date'] ?? null)
				->setTime($event['time'] ?? null)
				->setPoll($poll);
			$manager->persist($eventObj);
			$eventsObj[$event['name']] = $eventObj;
		}

		$choicesObj = [];
		foreach ($choices as $choice) {
			$choiceObj = (new Choice())
				->setName($choice['name'])
				->setValue($choice['value'])
				->setColor($choice['color'])
				->setPriority($choice['priority'])
				->setIcon($choice['icon'])
				->setPoll($poll);
			$manager->persist($choiceObj);
			$choicesObj[$choice['name']] = $choiceObj;
		}

		foreach ($participations as $row) {
			$participation = (new Participation())
				->setParticipant($participantsObj[$row['who']])
				->setEvent($eventsObj[$row['when']])
				->setChoice($choicesObj[$row['choice']]);
			$manager->persist($participation);
		}
	}

	public function load(ObjectManager $manager): void
	{
		$this->loadConcert($manager);
		$this->loadPicnic($manager);
		$this->loadHolidays($manager);

		$manager->flush();
	}
}
