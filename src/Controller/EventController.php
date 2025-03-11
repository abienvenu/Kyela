<?php

namespace App\Controller;

use App\Entity\Poll;
use App\Form\Type\EventType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Event;
use Symfony\Contracts\Translation\TranslatorInterface;

class EventController extends AbstractController
{
	/**
	 * Displays poll events
	 */
	#[Route('/{url:poll}/event/show')]
	public function show(Poll $poll, EntityManagerInterface $em): Response
	{
		$events = $em->getRepository(Event::class)->getFutureEvents($poll);

		return $this->render('event/show.html.twig', ['poll' => $poll, 'events' => $events, 'isArchive' => false]);
	}

	/**
	 * Displays past events
	 */
	#[Route('/{url:poll}/event/archive')]
	public function archive(Poll $poll, EntityManagerInterface $em): Response
	{
		$events = $em->getRepository(Event::class)->getPastEvents($poll);

		return $this->render('event/show.html.twig', ['poll' => $poll, 'events' => $events, 'isArchive' => true]);
	}

	/**
	 * Displays list of events
	 */
	#[Route('/{url:poll}/event/list')]
	public function list(Poll $poll, EntityManagerInterface $em): Response
	{
		$events = $em->getRepository(Event::class)->getFutureEvents($poll);

		return $this->render('event/list.html.twig', ['poll' => $poll, 'events' => $events]);
	}

	/**
	 * Edit an event
	 */
	#[Route('/{url:poll}/event/{id:event}/edit')]
	public function edit(Poll $poll, Event $event, Request $request, EntityManagerInterface $em): Response
	{
		$form = $this->createForm(EventType::class, $event);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid() && $event->getPoll()->getId() === $poll->getId()) {
			$em->flush();

			return $this->redirectToRoute('app_event_list', ['url' => $event->getPoll()->getUrl()]);
		}

		return $this->render('event/edit.html.twig', ['form' => $form, 'event' => $event]);
	}

	/**
	 * Add an event
	 */
	#[Route('/{url:poll}/event/new')]
	public function new(Poll $poll, Request $request, EntityManagerInterface $em): Response
	{
		$event = (new Event())
			->setPoll($poll);
		$form = $this->createForm(EventType::class, $event);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$em->persist($event);
			$em->flush();

			return $this->redirectToRoute('app_event_list', ['url' => $event->getPoll()->getUrl()]);
		}

		return $this->render('event/edit.html.twig', ['form' => $form, 'event' => $event]);
	}

	/**
	 * Delete an event
	 */
	#[Route('/{url:poll}/event/delete/{id:event}')]
	public function delete(
		Poll $poll,
		Event $event,
		EntityManagerInterface $em,
		TranslatorInterface $translator
	): RedirectResponse {
		if ($event->getPoll()->getId() === $poll->getId()) {
			$em->remove($event);
			$em->flush();
			$this->addFlash('success', $translator->trans('deleted'));
		}

		return $this->redirectToRoute('app_event_list', ['url' => $poll->getUrl()]);
	}
}
