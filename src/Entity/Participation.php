<?php

namespace App\Entity;

use App\Repository\ParticipationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParticipationRepository::class)]
class Participation
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	#[ORM\ManyToOne(inversedBy: 'participations')]
	#[ORM\JoinColumn(nullable: false)]
	private ?Choice $choice = null;

	#[ORM\ManyToOne(inversedBy: 'participations')]
	#[ORM\JoinColumn(nullable: false)]
	private ?Event $event = null;

	#[ORM\ManyToOne(inversedBy: 'participations')]
	#[ORM\JoinColumn(nullable: false)]
	private ?Participant $participant = null;

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getChoice(): ?Choice
	{
		return $this->choice;
	}

	public function setChoice(?Choice $choice): static
	{
		$this->choice = $choice;

		return $this;
	}

	public function getEvent(): ?Event
	{
		return $this->event;
	}

	public function setEvent(?Event $event): static
	{
		$this->event = $event;

		return $this;
	}

	public function getParticipant(): ?Participant
	{
		return $this->participant;
	}

	public function setParticipant(?Participant $participant): static
	{
		$this->participant = $participant;

		return $this;
	}
}
