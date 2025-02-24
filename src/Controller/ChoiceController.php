<?php

namespace App\Controller;

use App\Entity\Choice;
use App\Entity\Poll;
use App\Form\Type\ChoiceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ChoiceController extends AbstractController
{
    /**
     * Lists all choices
     */
	#[Route('/{url:poll}/choice')]
    public function index(Poll $poll): Response
    {
		return $this->render('choice/index.html.twig', ['poll' => $poll]);
    }

	/**
	 * Edits a choice
	 */
	#[Route('/{url:poll}/choice/{id:choice}/edit')]
	public function edit(Poll $poll, Choice $choice, Request $request, EntityManagerInterface $em): Response
	{
		$form = $this->createForm(ChoiceType::class, $choice);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid() && $choice->getPoll()->getId() === $poll->getId()) {
			$em->flush();

			return $this->redirectToRoute('app_choice_index', ['url' => $choice->getPoll()->getUrl()]);
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

			return $this->redirectToRoute('app_choice_index', ['url' => $choice->getPoll()->getUrl()]);
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
}
