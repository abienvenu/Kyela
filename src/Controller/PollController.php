<?php

namespace App\Controller;

use App\Entity\Choice;
use App\Entity\Poll;
use App\Form\Type\PollType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

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
	 * Create a new poll
	 */
	#[Route('/new')]
	public function new(EntityManagerInterface $em, TranslatorInterface $translator): Response
	{
		$poll = (new Poll())
			->setUrl(uniqid())
			->setTitle($translator->trans('new.poll'))
			->setHeadLines('')
			->setBottomLines('')
			->setAccessCode('');
		$yes = (new Choice())
			->setName($translator->trans('yes'))
			->setValue(1)
			->setColor('green')
			->setIcon('ok')
			->setPriority(0)
			->setPoll($poll);
		$maybe = (new Choice())
			->setName($translator->trans('maybe'))
			->setValue(0)
			->setColor('orange')
			->setIcon('time')
			->setPriority(0)
			->setPoll($poll);
		$no = (new Choice())
			->setName($translator->trans('no'))
			->setValue(0)
			->setColor('red')
			->setIcon('remove')
			->setPriority(0)
			->setPoll($poll);
		$poll
			->addChoice($yes)
			->addChoice($maybe)
			->addChoice($no);

		$em->persist($poll);
		$em->flush();

		$url = $this->generateUrl('app_poll_show', ['url' => $poll->getUrl()], UrlGeneratorInterface::ABSOLUTE_URL);
		$this->addFlash('success', $translator->trans('poll.created %url%', ['%url%' => $url]));

		return new RedirectResponse($url);
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
	public function edit(
		Poll $poll,
		Request $request,
		EntityManagerInterface $em,
		TranslatorInterface $translator
	): Response {
		$form = $this->createForm(PollType::class, $poll);
		$oldUrl = $poll->getUrl();
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$em->flush();

			if ($oldUrl !== $poll->getUrl()) {
				$url = $this->generateUrl(
					'app_poll_show',
					['url' => $poll->getUrl()],
					UrlGeneratorInterface::ABSOLUTE_URL
				);
				$this->addFlash('success', $translator->trans('poll.modified %url%', ['%url%' => $url]));
			}

			return $this->redirectToRoute('app_poll_show', ['url' => $poll->getUrl()]);
		}

		return $this->render('poll/edit.html.twig', ['form' => $form->createView(), 'poll' => $poll]);
	}
}
