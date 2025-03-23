<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Poll;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ParticipantController extends AbstractController
{
	#[Route('/{url:poll}/participants')]
	public function edit(Poll $poll): Response
	{
		return $this->render('participant/edit.html.twig', ['poll' => $poll]);
	}

	#[Route('/{url:poll}/participants/update', methods: ['POST'])]
	public function update(Poll $poll, Request $request, EntityManagerInterface $em): Response
	{
		$data = $request->request->all();

		// Suppression des participants marqués
		if (!empty($data['deleted_participants'])) {
			$deletedIds = json_decode($data['deleted_participants'], true);
			foreach ($deletedIds as $id) {
				$participant = $em->getRepository(Participant::class)->find($id);
				if ($participant) {
					$em->remove($participant);
				}
			}
		}

		$priority = 1;
		// Mise à jour des participants
		if (isset($data['participants_name'])) {
			foreach ($data['participants_name'] as $id => $participantName) {
				if (isset($data['participants_id'][$id]) && $data['participants_id'][$id]) {
					// Participant existant
					$participant = $em->getRepository(Participant::class)->find($data['participants_id'][$id]);
					if ($participant && $participant->getPoll()->getId() === $poll->getId()) {
						$participant->setName($participantName);
						$participant->setPriority($priority++);
					}
				} else {
					// Nouveau participant
					$participant = (new Participant())
						->setName($participantName)
						->setPriority($priority++)
						->setPoll($poll);
					$em->persist($participant);
				}
			}
		}

		$em->flush();

		return $this->redirectToRoute('app_poll_show', ['url' => $poll->getUrl()]);
	}
}
