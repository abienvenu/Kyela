<?php

namespace App\Controller;

use App\Entity\Choice;
use App\Entity\Event;
use App\Entity\Participant;
use App\Entity\Participation;
use App\Entity\Poll;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ParticipationController extends AbstractController
{
	/**
	 * Displays all Participations of a poll
	 */
	public function show(Poll $poll, EntityManagerInterface $em, $isArchive = false, $isFramed = false): Response
	{
		$events = $isArchive
			? $em->getRepository(Event::class)->getPastEvents($poll)
			: $em->getRepository(Event::class)->getFutureEvents($poll);
		$participationsArray = [];
		foreach ($events as $event) {
			foreach ($event->getParticipations() as $participation) {
				$participationsArray[$event->getId()][$participation->getParticipant()->getId()] = $participation;
			}
		}

		return $this->render('participation/show.html.twig', [
				'poll' => $poll,
				'events' => $events,
				'participationsArray' => $participationsArray,
				'isArchive' => $isArchive,
				'isFramed' => $isFramed,
			]
		);
	}

	/**
	 * Displays all Participations of a past polls
	 */
	public function archive(Poll $poll, EntityManagerInterface $em): Response
	{
		return $this->show($poll, $em, true);
	}

	/**
	 * Displays participations in a view adapted to be in frame
	 */
	public function frame(Poll $poll, EntityManagerInterface $em): Response
	{
		return $this->show($poll, $em, false, true);
	}

	/**
	 * Create or update a new Participation on the fly
	 */
	#[Route('/participation/update/{event}/{participant}/{choice}')]
	public function update(
		Event $event,
		Participant $participant,
		Choice $choice,
		EntityManagerInterface $em
	): JsonResponse {
		$participation = $em
			->getRepository(Participation::class)
			->findOneBy(['participant' => $participant->getId(), 'event' => $event->getId()]);
		if (!$participation) {
			$participation = new Participation();
			$em->persist($participation);
		}
		$participation->setEvent($event);
		$participation->setParticipant($participant);
		$participation->setChoice($choice);

		$em->flush();

		return new JsonResponse([
			'success' => true,
			'icon' => $choice->getIcon(),
			'name' => $choice->getName(),
			'color' => $choice->getColor(),
			'score' => $event->getParticipationsScore(),
			'groupScore' => $event->getParticipationsSubScore($participant->getGroup()),
		]);
	}

	/**
	 * Removes a Participation on the fly
	 */
	#[Route('/participation/delete/{event}/{participant}')]
	public function delete(Event $event, Participant $participant, EntityManagerInterface $em): JsonResponse
	{
		$participations = $em
			->getRepository(Participation::class)
			->findBy(['participant' => $participant->getId(), 'event' => $event->getId()]);
		foreach ($participations as $participation) {
			$em->remove($participation);
		}
		$em->flush();

		return new JsonResponse([
			'success' => true,
			'score' => $event->getParticipationsScore(),
			'groupScore' => $event->getParticipationsSubScore($participant->getGroup()),
		]);
	}
}
