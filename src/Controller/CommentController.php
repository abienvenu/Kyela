<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Participant;
use App\Entity\Poll;
use App\Form\Type\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class  CommentController extends AbstractController
{
	/**
	 * Show comments (not editable)
	 */
	#[Route('/{url:poll}/comment/show')]
	public function show(Poll $poll): Response
	{
		return $this->render('comment/show.html.twig', ['poll' => $poll]);
	}

	/**
	 * Display a list to edit or delete comments
	 */
	#[Route('/{url:poll}/comment/list')]
	public function list(Poll $poll): Response
	{
		return $this->render('comment/list.html.twig', ['poll' => $poll]);
	}

	protected function getAuthors(Poll $poll): array
	{
		$participants = $poll->getParticipants()->map(fn(Participant $p) => $p->getName())->toArray();
		return array_combine($participants, $participants);
	}

	/**
	 * Edit a comment
	 */
	#[Route('/{url:poll}/comment/edit/{id:comment}')]
	public function edit(Poll $poll, Comment $comment, Request $request, EntityManagerInterface $em): Response
	{
		$form = $this->createForm(CommentType::class, $comment, ['authors' => $this->getAuthors($poll)]);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$comment->setDatetime(new \DateTime());
			$em->persist($comment);
			$em->flush();

			return $this->redirectToRoute('app_comment_list', ['url' => $poll->getUrl()]);
		}

		return $this->render('comment/edit.html.twig', ['form' => $form, 'comment' => $comment]);
	}

	/**
	 * Add a new comment
	 */
	#[Route('/{url:poll}/comment/new')]
	public function new(Poll $poll, Request $request, EntityManagerInterface $em): Response
	{
		$comment = (new Comment())
			->setPoll($poll)
			->setDatetime(new \DateTime());
		$form = $this->createForm(CommentType::class, $comment, ['authors' => $this->getAuthors($poll)]);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$em->persist($comment);
			$em->flush();

			return $this->redirectToRoute('app_comment_list', ['url' => $poll->getUrl()]);
		}

		return $this->render('comment/edit.html.twig', ['form' => $form, 'comment' => $comment]);
	}

	/**
	 * Delete a comment
	 */
	#[Route('/{url:poll}/comment/delete/{id:comment}')]
	public function delete(
		Poll $poll,
		Comment $comment,
		EntityManagerInterface $em,
		TranslatorInterface $translator
	): Response {
		if ($comment->getPoll()->getId() === $poll->getId()) {
			$em->remove($comment);
			$em->flush();
			$this->addFlash('success', $translator->trans('deleted'));
		}

		return $this->redirectToRoute('app_comment_list', ['url' => $poll->getUrl()]);
	}
}
