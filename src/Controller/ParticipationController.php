<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Poll;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ParticipationController extends AbstractController
{
	#[Route('/participation', name: 'kyela_participation')]
	public function index(EntityManagerInterface $em): Response	{
		$poll = $em->find(Poll::class, 1);
		$events = $em->getRepository(Event::class)->getFutureEvents($poll);
		return $this->render('participation/index.html.twig', ['poll' => $poll, 'events' => $events]);
	}
}
