<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	#[ORM\Column(length: 255)]
	private ?string $author = null;

	#[ORM\Column(length: 5000)]
	private ?string $content = null;

	#[ORM\Column(type: Types::DATETIME_MUTABLE)]
	private ?\DateTimeInterface $datetime = null;

	#[ORM\ManyToOne(inversedBy: 'comments')]
	#[ORM\JoinColumn(nullable: false)]
	private ?Poll $poll = null;

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getAuthor(): ?string
	{
		return $this->author;
	}

	public function setAuthor(string $author): static
	{
		$this->author = $author;

		return $this;
	}

	public function getContent(): ?string
	{
		return $this->content;
	}

	public function setContent(string $content): static
	{
		$this->content = $content;

		return $this;
	}

	public function getDatetime(): ?\DateTimeInterface
	{
		return $this->datetime;
	}

	public function setDatetime(\DateTimeInterface $datetime): static
	{
		$this->datetime = $datetime;

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
}
