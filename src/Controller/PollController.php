<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PollController extends AbstractController
{
    /**
     * Shows the poll
     */
	#[Route('/{pollUrl}/')]
    public function show(): Response
    {
		return $this->render('poll/show.html.twig', []);
    }
}
