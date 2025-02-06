<?php

namespace App\Controller;

use App\Entity\Poll;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
