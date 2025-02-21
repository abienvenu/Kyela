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
	public function show(Poll $poll, EntityManagerInterface $em): Response
	{
		$events = $em->getRepository(Event::class)->getFutureEvents($poll);
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
			]
		);
	}

	/**
	 * Create a new Participation on the fly
	 */
	#[Route('/participation/new/{event}/{participant}/{choice}')]
	public function new(
		Event $event,
		Participant $participant,
		Choice $choice,
		EntityManagerInterface $em
	): JsonResponse {
		// If participation exists, editAction should have been called, not newAction
		// But just in case, we look for an existing participation
		$participation = $em
			->getRepository(Participation::class)
			->findOneBy(['participant' => $participant->getId(), 'event' => $event->getId()]);
		if (!$participation) {
			$participation = new Participation();
		}
		$participation->setEvent($event);
		$participation->setParticipant($participant);
		$participation->setChoice($choice);

		$em->persist($participation);
		$em->flush();

		return new JsonResponse([
			'success' => true,
			'icon' => $choice->getIcon(),
			'name' => $choice->getName(),
			'color' => $choice->getColor(),
		]);
	}

	/**
	 * Edits a Participation on the fly
	 */
	#[Route('/participation/edit/{participation}/{choice}')]
	public function edit(Participation $participation, Choice $choice, EntityManagerInterface $em): JsonResponse
	{
		$participation->setChoice($choice);
		$em->flush();

		return new JsonResponse([
			'success' => true,
			'icon' => $choice->getIcon(),
			'name' => $choice->getName(),
			'color' => $choice->getColor(),
		]);
	}

	/**
	 * Removes a Participation on the fly
	 */
	#[Route('/participation/delete/{participation}')]
	public function delete(Participation $participation, EntityManagerInterface $em): JsonResponse
	{
		$em->remove($participation);
		$em->flush();

		return new JsonResponse(['success' => true]);
	}
}
