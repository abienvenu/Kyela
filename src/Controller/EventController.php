<?php

namespace App\Controller;

use App\Entity\Poll;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Event;

class EventController extends AbstractController
{
	public function __construct(protected EntityManagerInterface $em)
	{
	}

	/**
	 * Displays poll events
	 */
	#[Route('/{url:poll}/event/show')]
	public function show(Poll $poll): Response
	{
		$events = $this->em->getRepository(Event::class)->getFutureEvents($poll);

		return $this->render('event/show.html.twig', ['events' => $events]);
	}
}
