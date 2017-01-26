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

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Abienvenu\KyelaBundle\Entity\Participation;

/**
 * Participation controller.
 *
 * @Route("/participation")
 */
class ParticipationController extends Controller
{
    /**
     * Creates a new Participation on the fly.
     *
     * @Route("/new/{event}/{participant}/{choice}", name="participation_new")
     * @Method("GET")
     */
    public function newAction($event, $participant, $choice)
    {
        $em = $this->getDoctrine()->getManager();
        $eventObj = $em->getRepository('KyelaBundle:Event')->find($event);
        $participantObj = $em->getRepository('KyelaBundle:Participant')->find($participant);
        $choiceObj = $em->getRepository('KyelaBundle:Choice')->find($choice);

        // If participation exists, editAction should have been called, not newAction
        // But just in case, we look for an existing participation
        $participationObj = $em->getRepository('KyelaBundle:Participation')->findOneBy(['participant' => $participant, 'event' => $event]);
        if (!$participationObj)
        {
            $participationObj = new Participation();
        }
        $participationObj->setEvent($eventObj);
        $participationObj->setParticipant($participantObj);
        $participationObj->setChoice($choiceObj);

        $em->persist($participationObj);
        $em->flush();
	    return $this->render(
		    'KyelaBundle:Poll:_participation_cell.html.twig',
		    ['participation' => $participationObj, 'choices' => $eventObj->getPoll()->getChoices(), 'event' => $eventObj, 'participant' => $participantObj]);
    }

    /**
     * Edits a Participation on the fly
     *
     * @Route("/{id}/edit/{event}/{participant}/{choice}", name="participation_edit")
     * @Method("GET")
     */
    public function editAction($id, $event, $participant, $choice)
    {
        $em = $this->getDoctrine()->getManager();
        $participationObj = $em->getRepository('KyelaBundle:Participation')->find($id);
        if (!$participationObj) {
            throw $this->createNotFoundException('Unable to find Participation entity.');
        }

        $eventObj = $em->getRepository('KyelaBundle:Event')->find($event);
        $participationObj->setEvent($eventObj);
        $participantObj = $em->getRepository('KyelaBundle:Participant')->find($participant);
        $participationObj->setParticipant($participantObj);
        $choiceObj = $em->getRepository('KyelaBundle:Choice')->find($choice);
        $participationObj->setChoice($choiceObj);
        $em->persist($participationObj);
        $em->flush();
	    return $this->render(
	    	'KyelaBundle:Poll:_participation_cell.html.twig',
		    ['participation' => $participationObj, 'choices' => $eventObj->getPoll()->getChoices(), 'event' => $eventObj, 'participant' => $participantObj]);
    }

    /**
     * Removes a Participation on the fly
     *
     * @Route("/{id}/delete", name="participation_delete")
     * @Method("GET")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $participationObj = $em->getRepository('KyelaBundle:Participation')->find($id);
        if (!$participationObj) {
            throw $this->createNotFoundException('Unable to find Participation entity.');
        }
        $em->remove($participationObj);
        $em->flush();
	    return $this->render(
		    'KyelaBundle:Poll:_participation_cell.html.twig',
		    [
		    	'participation' => null,
		        'choices' => $participationObj->getEvent()->getPoll()->getChoices(),
			    'event' => $participationObj->getEvent(),
			    'participant' => $participationObj->getParticipant()]);
    }
}
