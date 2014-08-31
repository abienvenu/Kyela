<?php

namespace Abienvenu\KyelaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Event
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Event
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datetime", type="datetime")
     */
    private $datetime;

	/**
	 * @ORM\OneToMany(targetEntity="Participation", mappedBy="event")
	 */
	protected $participations;

	public function __construct()
	{
		$this->participations = new ArrayCollection();
	}

	public function __toString()
	{
		return $this->name;
	}

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
     * Set name
     *
     * @param string $name
     * @return Event
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set datetime
     *
     * @param \DateTime $datetime
     * @return Event
     */
    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * Get datetime
     *
     * @return \DateTime
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * Add participations
     *
     * @param \Abienvenu\KyelaBundle\Entity\Participation $participations
     * @return Event
     */
    public function addParticipation(\Abienvenu\KyelaBundle\Entity\Participation $participations)
    {
        $this->participations[] = $participations;

        return $this;
    }

    /**
     * Remove participations
     *
     * @param \Abienvenu\KyelaBundle\Entity\Participation $participations
     */
    public function removeParticipation(\Abienvenu\KyelaBundle\Entity\Participation $participations)
    {
        $this->participations->removeElement($participations);
    }

    /**
     * Get participations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParticipations()
    {
        return $this->participations;
    }

    /**
     * Get participations score
     */
    public function getParticipationsScore()
    {
    	$score = 0;
    	foreach ($this->participations as $participation)
    	{
    		$score += $participation->getChoice()->getValue();
    	}
    	return $score;
    }
}
