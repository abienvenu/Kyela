<?php

namespace Abienvenu\KyelaBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * EventRepository
 *
 */
class EventRepository extends EntityRepository
{
	/**
     * Get future events
     *
	 * @param Poll $poll
     * @return \Doctrine\Common\Collections\Collection
	 */
	public function getFutureEvents(Poll $poll)
	{
		$query = $this->getEntityManager()->createQuery(
			'SELECT event
			FROM KyelaBundle:Event event
			WHERE event.poll = :poll
				AND event.date >= :date
			ORDER BY event.date'
		);
		$query->setParameter('poll', $poll->getId());
		$query->setParameter('date', new \DateTime("yesterday"));
		return $query->getResult();
	}
}
