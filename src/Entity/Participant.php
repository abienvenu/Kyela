<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParticipantRepository::class)]
class Participant
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	#[ORM\Column(length: 100)]
	private ?string $name = null;

	#[ORM\Column]
	private ?int $priority = null;

	/**
	 * @var Collection<int, Participation>
	 */
	#[ORM\OneToMany(targetEntity: Participation::class, mappedBy: 'participant', cascade: ['persist', 'remove'])]
	private Collection $participations;

	#[ORM\ManyToOne(inversedBy: 'participants')]
	#[ORM\JoinColumn(nullable: false)]
	private ?Poll $poll = null;

	public function __construct()
	{
		$this->participations = new ArrayCollection();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getName(): ?string
	{
		return $this->name;
	}

	public function setName(string $name): static
	{
		$this->name = $name;

		return $this;
	}

	public function getPriority(): ?int
	{
		return $this->priority;
	}

	public function setPriority(int $priority): static
	{
		$this->priority = $priority;

		return $this;
	}

	/**
	 * @return Collection<int, Participation>
	 */
	public function getParticipations(): Collection
	{
		return $this->participations;
	}

	public function addParticipation(Participation $participation): static
	{
		if (!$this->participations->contains($participation)) {
			$this->participations->add($participation);
			$participation->setParticipant($this);
		}

		return $this;
	}

	public function removeParticipation(Participation $participation): static
	{
		if ($this->participations->removeElement($participation)) {
			// set the owning side to null (unless already changed)
			if ($participation->getParticipant() === $this) {
				$participation->setParticipant(null);
			}
		}

		return $this;
	}

	public function getPoll(): ?Poll
	{
		return $this->poll;
	}

	public function setPoll(?Poll $poll): static
	{
		$this->poll = $poll;

		return $this;
	}

	public function isSeparator(): bool
	{
		return preg_match('/^(==|--|@@).*\1$/', $this->name) === 1;
	}

	public function getSeparatorName(): string
	{
		return preg_replace('/^(==|--|@@)(.*)(==|--|@@)$/', '$2', $this->name);
	}
}
