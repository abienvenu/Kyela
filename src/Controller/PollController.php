<?php

namespace App\Controller;

use App\Entity\Poll;
use App\Form\Type\PollType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PollController extends AbstractController
{
	/**
	 * Home page
	 */
	#[Route('/')]
	public function index(): Response
	{
		return $this->render('poll/index.html.twig');
	}

	/**
	 * Shows the poll
	 */
	#[Route('/{url:poll}/')]
	public function show(Poll $poll): Response
	{
		return $this->render('poll/show.html.twig', ['poll' => $poll]);
	}

	/**
	 * Shows a simplified poll to be used in iframe
	 */
	#[Route('/{url:poll}/frame')]
	public function frame(Poll $poll): Response
	{
		return $this->render('poll/frame.html.twig', ['poll' => $poll]);
	}

	/**
	 * Shows participations only (to use withing iframes)
	 */
	#[Route('/{url:poll}/participations')]
	public function participations(Poll $poll): Response
	{
		return $this->render('poll/participations.html.twig', ['poll' => $poll]);
	}

	/**
	 * Displays a form to edit an existing Poll entity.
	 */
	#[Route('/{url:poll}/edit')]
	public function edit(Poll $poll, Request $request, EntityManagerInterface $em): Response
	{
		$form = $this->createForm(PollType::class, $poll);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$em->flush();
			$this->addFlash('success', 'Le sondage a été modifié avec succès.');

			return $this->redirectToRoute('app_poll_show', ['url' => $poll->getUrl()]);
		}

		return $this->render('poll/edit.html.twig', ['pollForm' => $form->createView(), 'poll' => $poll]);
	}
}
