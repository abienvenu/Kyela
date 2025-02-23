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
		// Mise à jour des participants existants
		if (isset($data['participants'])) {
			foreach ($data['participants'] as $id => $participantData) {
				$participant = $em->getRepository(Participant::class)->find($id);
				if ($participant) {
					$participant->setName($participantData['name']);
					$participant->setPriority($priority++);
				}
			}
		}

		// Ajout de nouveaux participants
		if (isset($data['new_participants'])) {
			foreach ($data['new_participants'] as $name) {
				$participant = new Participant();
				$participant->setName($name);
				$participant->setPriority($priority++);
				$participant->setPoll($poll);
				$em->persist($participant);
			}
		}

		$em->flush();

		return $this->redirectToRoute('app_poll_show', ['url' => $poll->getUrl()]);
	}
}
