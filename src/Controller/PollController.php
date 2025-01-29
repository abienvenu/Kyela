<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PollController extends PollSetterController
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
