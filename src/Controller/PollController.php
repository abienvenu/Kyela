<?php

namespace App\Controller;

use App\Entity\Poll;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PollController extends AbstractController
{
	/**
	 * Shows the poll
	 */
	#[Route('/{url:poll}/')]
	public function show(Poll $poll): Response
	{
		return $this->render('poll/show.html.twig', ['poll' => $poll]);
	}
}
