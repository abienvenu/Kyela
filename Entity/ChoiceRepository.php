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

use Doctrine\ORM\EntityRepository;

/**
 * EventRepository
 *
 */
class ChoiceRepository extends EntityRepository
{
    /**
     * Get future events
     */
    public function getOrderedChoices(Poll $poll)
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT choice
            FROM KyelaBundle:Choice choice
            WHERE choice.poll = :poll
            ORDER BY choice.priority'
        );
        $query->setParameter('poll', $poll->getId());
        return $query->getResult();
    }
}
