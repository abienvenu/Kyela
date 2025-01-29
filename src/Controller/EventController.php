<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Event;

class EventController extends PollSetterController
{
    protected $entityName = 'KyelaBundle:Event';
    protected $cancelRoute = 'poll_show';
    protected $successRoute = 'poll_show';
    protected $deleteRoute = 'event_delete';
    protected $deleteSuccessRoute = 'poll_show';

    /**
     * Displays poll events
     */
	#[Route('/{pollUrl}/event/show')]
    public function show(): Response
    {
        $events = $this->em->getRepository(Event::class)->getFutureEvents($this->poll);
		return $this->render('event/show.html.twig', ['poll' => $this->poll, 'events' => $events]);
    }
}
