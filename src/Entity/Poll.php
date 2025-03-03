<?php

namespace App\Entity;

use App\Repository\PollRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;

#[ORM\Entity(repositoryClass: PollRepository::class)]
class Poll
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	#[ORM\Column(length: 255, unique: true)]
	private ?string $url = null;

	#[ORM\Column(length: 255)]
	private ?string $title = null;

	#[ORM\Column(type: Types::TEXT, nullable: true, name: 'headLines')]
	private ?string $headLines = null;

	#[ORM\Column(type: Types::TEXT, nullable: true, name: 'bottomLines')]
	private ?string $bottomLines = null;

	#[ORM\Column(length: 255, nullable: true, name: 'accessCode')]
	private ?string $accessCode = null;

	/**
	 * @var Collection<int, Choice>
	 */
	#[ORM\OneToMany(targetEntity: Choice::class, mappedBy: 'poll', cascade: ['persist', 'remove'])]
	#[OrderBy(['priority' => 'ASC'])]
	private Collection $choices;

	/**
	 * @var Collection<int, Comment>
	 */
	#[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'poll', cascade: ['persist', 'remove'])]
	private Collection $comments;

	/**
	 * @var Collection<int, Event>
	 */
	#[ORM\OneToMany(targetEntity: Event::class, mappedBy: 'poll', cascade: ['persist', 'remove'])]
	private Collection $events;

	/**
	 * @var Collection<int, Participant>
	 */
	#[ORM\OneToMany(targetEntity: Participant::class, mappedBy: 'poll', cascade: ['persist', 'remove'])]
	#[OrderBy(['priority' => 'ASC'])]
	private Collection $participants;

	public function __construct()
	{
		$this->choices = new ArrayCollection();
		$this->comments = new ArrayCollection();
		$this->events = new ArrayCollection();
		$this->participants = new ArrayCollection();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getUrl(): ?string
	{
		return $this->url;
	}

	public function setUrl(string $url): static
	{
		$this->url = $url;

		return $this;
	}

	public function getTitle(): ?string
	{
		return $this->title;
	}

	public function setTitle(string $title): static
	{
		$this->title = $title;

		return $this;
	}

	public function getHeadLines(): ?string
	{
		return $this->headLines;
	}

	public function setHeadLines(?string $headLines): static
	{
		$this->headLines = $headLines;

		return $this;
	}

	public function getBottomLines(): ?string
	{
		return $this->bottomLines;
	}

	public function setBottomLines(?string $bottomLines): static
	{
		$this->bottomLines = $bottomLines;

		return $this;
	}

	public function getAccessCode(): ?string
	{
		return $this->accessCode;
	}

	public function setAccessCode(?string $accessCode): static
	{
		$this->accessCode = $accessCode;

		return $this;
	}

	/**
	 * @return Collection<int, Choice>
	 */
	public function getChoices(): Collection
	{
		return $this->choices;
	}

	public function addChoice(Choice $choice): static
	{
		if (!$this->choices->contains($choice)) {
			$this->choices->add($choice);
			$choice->setPoll($this);
		}

		return $this;
	}

	public function removeChoice(Choice $choice): static
	{
		if ($this->choices->removeElement($choice)) {
			// set the owning side to null (unless already changed)
			if ($choice->getPoll() === $this) {
				$choice->setPoll(null);
			}
		}

		return $this;
	}

	/**
	 * @return Collection<int, Comment>
	 */
	public function getComments(): Collection
	{
		return $this->comments;
	}

	public function addComment(Comment $comment): static
	{
		if (!$this->comments->contains($comment)) {
			$this->comments->add($comment);
			$comment->setPoll($this);
		}

		return $this;
	}

	public function removeComment(Comment $comment): static
	{
		if ($this->comments->removeElement($comment)) {
			// set the owning side to null (unless already changed)
			if ($comment->getPoll() === $this) {
				$comment->setPoll(null);
			}
		}

		return $this;
	}

	/**
	 * @return Collection<int, Event>
	 */
	public function getEvents(): Collection
	{
		return $this->events;
	}

	public function addEvent(Event $event): static
	{
		if (!$this->events->contains($event)) {
			$this->events->add($event);
			$event->setPoll($this);
		}

		return $this;
	}

	public function removeEvent(Event $event): static
	{
		if ($this->events->removeElement($event)) {
			// set the owning side to null (unless already changed)
			if ($event->getPoll() === $this) {
				$event->setPoll(null);
			}
		}

		return $this;
	}

	/**
	 * @return Collection<int, Participant>
	 */
	public function getParticipants(): Collection
	{
		return $this->participants;
	}

	public function addParticipant(Participant $participant): static
	{
		if (!$this->participants->contains($participant)) {
			$this->participants->add($participant);
			$participant->setPoll($this);
		}

		return $this;
	}

	public function removeParticipant(Participant $participant): static
	{
		if ($this->participants->removeElement($participant)) {
			// set the owning side to null (unless already changed)
			if ($participant->getPoll() === $this) {
				$participant->setPoll(null);
			}
		}

		return $this;
	}
}
