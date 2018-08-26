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

namespace Abienvenu\KyelaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Abienvenu\KyelaBundle\Entity\Event;
use Abienvenu\KyelaBundle\Form\Type\EventType;

/**
 * Event controller.
 *
 * @Route("/{pollUrl}/event")
 */
class EventController extends CRUDController
{
    protected $entityName = 'KyelaBundle:Event';
    protected $cancelRoute = 'poll_show';
    protected $successRoute = 'poll_show';
    protected $deleteRoute = 'event_delete';
    protected $deleteSuccessRoute = 'poll_show';

    /**
     * Displays poll events
     *
     * @Route("", methods="GET")
     * @Template()
     */
    public function showAction($isFuture)
    {
        $em = $this->getDoctrine()->getManager();
        $events = $em->getRepository('KyelaBundle:Event')->getFutureOrPastEvents($this->poll, $isFuture);
        return [
            'poll' => $this->poll,
            'events' => $events,
        ];
    }

    /**
     * Displays a form to create a new Event entity.
     *
     * @Route("/new", name="event_new", methods={"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        return $this->doNewAction(EventType::class, new Event(), $request);
    }

    /**
     * Displays a form to edit an existing Event entity.
     *
     * @Route("/{id}/edit", name="event_edit", methods={"GET", "PUT"})
     * @Template()
     */
    public function editAction(Request $request, $id)
    {
        return $this->doEditAction(EventType::class, $id, $request);
    }

    /**
     * Deletes a Event entity.
     *
     * @Route("/{id}", name="event_delete", methods="DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        return $this->doDeleteAction($request, $id);
    }
}
