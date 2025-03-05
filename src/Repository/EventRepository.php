<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\Poll;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 */
class EventRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Event::class);
	}

	public function getFutureEvents(Poll $poll): array
	{
		return $this->getEntityManager()->createQuery(
			"
			SELECT event
			FROM App\Entity\Event event
			WHERE event.poll = :poll
				AND (event.date > :date OR event.date IS NULL)
			ORDER BY event.date ASC, event.time ASC"
		)->setParameter('poll', $poll)
					->setParameter('date', new \DateTime('yesterday'))
					->setMaxResults(30)
					->getResult();
	}

	public function getPastEvents(Poll $poll): array
	{
		return $this->getEntityManager()->createQuery(
			"
			SELECT event
			FROM App\Entity\Event event
			WHERE event.poll = :poll
				AND event.date <= :date
			ORDER BY event.date DESC, event.time DESC"
		)->setParameter('poll', $poll)
					->setParameter('date', new \DateTime('yesterday'))
					->setMaxResults(30)
					->getResult();
	}
}
