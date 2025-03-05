<?php

namespace App\Controller;

use App\Entity\Choice;
use App\Entity\Poll;
use App\Form\Type\ChoiceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ChoiceController extends AbstractController
{
	/**
	 * Lists all choices
	 */
	#[Route('/{url:poll}/choice')]
	public function list(Poll $poll): Response
	{
		return $this->render('choice/list.html.twig', ['poll' => $poll]);
	}

	/**
	 * Edits a choice
	 */
	#[Route('/{url:poll}/choice/edit/{id:choice}')]
	public function edit(Poll $poll, Choice $choice, Request $request, EntityManagerInterface $em): Response
	{
		$form = $this->createForm(ChoiceType::class, $choice);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid() && $choice->getPoll()->getId() === $poll->getId()) {
			$em->flush();

			return $this->redirectToRoute('app_choice_list', ['url' => $choice->getPoll()->getUrl()]);
		}

		return $this->render('choice/edit.html.twig', ['form' => $form, 'choice' => $choice]);
	}

	/**
	 * Add a choice
	 */
	#[Route('/{url:poll}/choice/new')]
	public function new(Poll $poll, Request $request, EntityManagerInterface $em): Response
	{
		$choice = (new Choice())
			->setPoll($poll)
			->setPriority(count($poll->getChoices()));
		$form = $this->createForm(ChoiceType::class, $choice);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$em->persist($choice);
			$em->flush();

			return $this->redirectToRoute('app_choice_list', ['url' => $choice->getPoll()->getUrl()]);
		}

		return $this->render('choice/edit.html.twig', ['form' => $form, 'choice' => $choice]);
	}

	/**
	 * Change order priority of choices
	 */
	#[Route('/{url:poll}/choice/reorder')]
	public function reorder(Poll $poll, Request $request, EntityManagerInterface $em): JsonResponse
	{
		$data = json_decode($request->getContent(), true);

		foreach ($data as $item) {
			$choice = $em->getRepository(Choice::class)->find($item['id']);
			if ($choice && $choice->getPoll()->getId() === $poll->getId()) {
				$choice->setPriority($item['priority']);
			}
		}
		$em->flush();

		return new JsonResponse(['status' => 'success']);
	}

	/**
	 * Delete a choice
	 */
	#[Route('/{url:poll}/choice/delete/{id:choice}')]
	public function delete(
		Poll $poll,
		Choice $choice,
		EntityManagerInterface $em,
		TranslatorInterface $translator
	): RedirectResponse {
		if ($choice->getPoll()->getId() === $poll->getId()) {
			$em->remove($choice);
			$em->flush();
			$this->addFlash('success', $translator->trans('deleted'));
		}

		$url = $this->generateUrl('app_choice_list', ['url' => $poll->getUrl()], UrlGeneratorInterface::ABSOLUTE_URL);

		return new RedirectResponse($url);
	}
}
