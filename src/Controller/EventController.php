<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
	#[Route('/{pollUrl}/event/show')]
    public function show(Request $request): Response
    {
        $events = $this->em->getRepository(Event::class)->getFutureEvents($request->get('poll'));
		return $this->render('event/show.html.twig', ['events' => $events]);
    }
}
