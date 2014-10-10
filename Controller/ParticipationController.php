<?php
/**
 * Copyright 2014 Arnaud Bienvenu
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

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Abienvenu\KyelaBundle\Entity\Participation;

/**
 * Participation controller.
 *
 * @Route("/{pollUrl}/participation")
 */
class ParticipationController extends Controller
{
    /**
     * Creates a new Participation on the fly.
     *
     * @Route("/new/{event}/{participant}/{choice}", name="participation_new")
     * @Method("GET")
     */
    public function newAction($pollUrl, $event, $participant, $choice)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new Participation();
        $eventObj = $em->getRepository('KyelaBundle:Event')->find($event);
        $entity->setEvent($eventObj);
        $participantObj = $em->getRepository('KyelaBundle:Participant')->find($participant);
        $entity->setParticipant($participantObj);
        $choiceObj = $em->getRepository('KyelaBundle:Choice')->find($choice);
        $entity->setChoice($choiceObj);
        $em->persist($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('poll_show', ['pollUrl' => $pollUrl]));
    }

    /**
     * Edits a Participation on the fly
     *
     * @Route("/{id}/edit/{event}/{participant}/{choice}", name="participation_edit")
     * @Method("GET")
     */
    public function editAction($pollUrl, $id, $event, $participant, $choice)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('KyelaBundle:Participation')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Participation entity.');
        }

        $eventObj = $em->getRepository('KyelaBundle:Event')->find($event);
        $entity->setEvent($eventObj);
        $participantObj = $em->getRepository('KyelaBundle:Participant')->find($participant);
        $entity->setParticipant($participantObj);
        $choiceObj = $em->getRepository('KyelaBundle:Choice')->find($choice);
           $entity->setChoice($choiceObj);
           $em->persist($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('poll_show', ['pollUrl' => $pollUrl]));
    }

    /**
     * Removes a Participation on the fly
     *
     * @Route("/{id}/delete", name="participation_delete")
     * @Method("GET")
     */
    public function deleteAction($pollUrl, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('KyelaBundle:Participation')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Participation entity.');
        }
        $em->remove($entity);
        $em->flush();
        return $this->redirect($this->generateUrl('poll_show', ['pollUrl' => $pollUrl]));
    }
}
