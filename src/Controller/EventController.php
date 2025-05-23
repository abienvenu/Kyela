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

	private function handleForm(
		Event $event,
		Request $request,
		EntityManagerInterface $em,
		string $message,
	): Response {
		$form = $this->createForm(EventType::class, $event);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em->persist($event);
			$em->flush();
			$this->addFlash('success', $message.' "'.$event->getName().'"');

			return $this->redirectToRoute('app_event_list', ['url' => $event->getPoll()->getUrl()]);
		}

		return $this->render('event/edit.html.twig', [
			'form' => $form,
			'event' => $event,
		]);
	}

	/**
	 * Edit an event
	 */
	#[Route('/{url:poll}/event/{id:event}/edit')]
	public function edit(
		Poll $poll,
		Event $event,
		Request $request,
		EntityManagerInterface $em,
		TranslatorInterface $translator
	): Response {
		if ($event->getPoll()->getId() !== $poll->getId()) {
			throw $this->createNotFoundException();
		}

		return $this->handleForm($event, $request, $em, $translator->trans('event.modified'));
	}

	/**
	 * Add an event
	 */
	#[Route('/{url:poll}/event/new')]
	public function new(
		Poll $poll,
		Request $request,
		EntityManagerInterface $em,
		TranslatorInterface $translator
	): Response {
		$event = (new Event())->setPoll($poll);

		return $this->handleForm($event, $request, $em, $translator->trans('event.created'));
	}

	#[Route('/{url:poll}/event/{id:event}/duplicate')]
	public function duplicate(
		Poll $poll,
		Event $event,
		Request $request,
		EntityManagerInterface $em,
		TranslatorInterface $translator,
	): Response {
		$newEvent = (new Event())
			->setPoll($poll)
			->setName($translator->trans('copy.of').' '.$event->getName())
			->setPlace($event->getPlace())
			->setDate($event->getDate())
			->setTime($event->getTime())
			->setSubtitle($event->getSubtitle());

		return $this->handleForm($newEvent, $request, $em, $translator->trans('event.duplicated'));
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
			$this->addFlash('success', $translator->trans('event.deleted').' "'.$event->getName().'"');
		}

		return $this->redirectToRoute('app_event_list', ['url' => $poll->getUrl()]);
	}
}
