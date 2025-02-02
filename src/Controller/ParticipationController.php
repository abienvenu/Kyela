<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Poll;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ParticipationController extends AbstractController
{
	public function __construct(protected EntityManagerInterface $em)
	{
	}

	public function show(Poll $poll): Response
	{
		$events = $this->em->getRepository(Event::class)->getFutureEvents($poll);

		return $this->render('participation/show.html.twig', ['poll' => $poll, 'events' => $events]);
	}
}
