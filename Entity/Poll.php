<?php
/*
 * Copyright 2014-2016 Arnaud Bienvenu
 *
 * This file is part of Kyela.

 * Kyela is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * Kyela is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.

 * You should have received a copy of the GNU Affero General Public License
 * along with Kyela.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace Abienvenu\KyelaBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Poll
 *
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="url_idx", columns={"url"})})
 * @UniqueEntity(fields={"url"})
 * @ORM\Entity
 */
class Poll extends Entity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="headLines", type="text", nullable=true)
     */
    private $headLines;

    /**
     * @var string
     *
     * @ORM\Column(name="bottomLines", type="text", nullable=true)
     */
    private $bottomLines;

    /**
     * @var string
     *
     * @ORM\Column(name="accessCode", type="string", length=255)
     */
    private $accessCode;

    /**
     * @ORM\OneToMany(targetEntity="Participant", mappedBy="poll", cascade={"remove"})
     * @ORM\OrderBy({"priority" = "ASC"})
     */
    private $participants;

    /**
     * @ORM\OneToMany(targetEntity="Event", mappedBy="poll", cascade={"remove"})
     */
    private $events;

    /**
     * @ORM\OneToMany(targetEntity="Choice", mappedBy="poll", cascade={"remove", "persist"})
     */
    private $choices;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="poll", cascade={"remove"})
     */
    private $comments;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Poll
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Poll
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->choices = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    /**
     * Add participants
     *
     * @param Participant $participants
     * @return Poll
     */
    public function addParticipant(Participant $participants)
    {
        $this->participants[] = $participants;

        return $this;
    }

    /**
     * Remove participants
     *
     * @param Participant $participants
     */
    public function removeParticipant(Participant $participants)
    {
        $this->participants->removeElement($participants);
    }

    /**
     * Get participants
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParticipants()
    {
        return $this->participants;
    }

    /**
     * Add events
     *
     * @param Event $events
     * @return Poll
     */
    public function addEvent(Event $events)
    {
        $this->events[] = $events;

        return $this;
    }

    /**
     * Remove events
     *
     * @param Event $events
     */
    public function removeEvent(Event $events)
    {
        $this->events->removeElement($events);
    }

    /**
     * Get events
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Add choices
     *
     * @param Choice $choices
     * @return Poll
     */
    public function addChoice(Choice $choices)
    {
        $this->choices[] = $choices;

        return $this;
    }

    /**
     * Remove choices
     *
     * @param Choice $choices
     */
    public function removeChoice(Choice $choices)
    {
        $this->choices->removeElement($choices);
    }

    /**
     * Get choices
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChoices()
    {
        return $this->choices;
    }

    /**
     * Set headLines
     *
     * @param string $headLines
     * @return Poll
     */
    public function setHeadLines($headLines)
    {
        $this->headLines = $headLines;

        return $this;
    }

    /**
     * Get headLines
     *
     * @return string
     */
    public function getHeadLines()
    {
        return $this->headLines;
    }

    /**
     * Set bottomLines
     *
     * @param string $bottomLines
     * @return Poll
     */
    public function setBottomLines($bottomLines)
    {
        $this->bottomLines = $bottomLines;

        return $this;
    }

    /**
     * Get bottomLines
     *
     * @return string
     */
    public function getBottomLines()
    {
        return $this->bottomLines;
    }

    /**
     * Set accessCode
     *
     * @param string $accessCode
     * @return Poll
     */
    public function setAccessCode($accessCode)
    {
        $this->accessCode = $accessCode;

        return $this;
    }

    /**
     * Get accessCode
     *
     * @return string
     */
    public function getAccessCode()
    {
        return $this->accessCode;
    }

    /**
     * Add comments
     *
     * @param Comment $comments
     * @return Poll
     */
    public function addComment(Comment $comments)
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param Comment $comments
     */
    public function removeComment(Comment $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    public function getParticipantsAsArrayOfNames()
    {
        $participants = [];
        foreach ($this->getParticipants() as $participant)
        {
            $participants[$participant->getName()] = $participant->getName();
        }
        return $participants;
    }
}
