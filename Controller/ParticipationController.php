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

use Abienvenu\KyelaBundle\Entity\Choice;
use Abienvenu\KyelaBundle\Entity\Event;
use Abienvenu\KyelaBundle\Entity\Participant;
use Abienvenu\KyelaBundle\Entity\Poll;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Abienvenu\KyelaBundle\Entity\Participation;

/**
 * Participation controller.
 *
 * @Route("/participation")
 */
class ParticipationController extends Controller
{
    /**
     * Displays interactive participation table
     *
     * @Route("", methods="GET")
     * @Template()
     */
    public function showAction(Poll $poll, $isFuture)
    {
        $em = $this->getDoctrine()->getManager();
        $events = $em->getRepository('KyelaBundle:Event')->getFutureOrPastEvents($poll, $isFuture);
        $choices = $em->getRepository("KyelaBundle:Choice")->getOrderedChoices($poll);
        $participationsArray = [];
        foreach ($events as $event)
        {
            foreach ($event->getParticipations() as $participation)
            {
                $accessKey = "{$event->getId()}-{$participation->getParticipant()->getId()}";
                $participationsArray[$accessKey] = $participation;
            }
        }
        return [
            'poll' => $poll,
            'choices' => $choices,
            'events' => $events,
            'participations' => $participationsArray,
        ];
    }

    /**
     * Creates a new Participation on the fly.
     *
     * @Route("/new/{event}/{participant}/{choice}", name="participation_new", methods="GET")
     */
    public function newAction(Event $event, Participant $participant, Choice $choice)
    {
        $em = $this->getDoctrine()->getManager();

        // If participation exists, editAction should have been called, not newAction
        // But just in case, we look for an existing participation
        $participation = $em->getRepository('KyelaBundle:Participation')->findOneBy(['participant' => $participant->getId(), 'event' => $event->getId()]);
        if (!$participation)
        {
            $participation = new Participation();
        }
        $participation->setEvent($event);
        $participation->setParticipant($participant);
        $participation->setChoice($choice);

        $em->persist($participation);
        $em->flush();
        return $this->render(
            'KyelaBundle:participation:_cell.html.twig',
            ['participation' => $participation, 'choices' => $event->getPoll()->getChoices(), 'event' => $event, 'participant' => $participant]);
    }

    /**
     * Edits a Participation on the fly
     *
     * @Route("/edit/{participation}/{newChoice}", name="participation_edit", methods="GET")
     */
    public function editAction(Participation $participation, Choice $newChoice)
    {
        $em = $this->getDoctrine()->getManager();

        $participation->setChoice($newChoice);
        $em->flush();
        return $this->render(
            'KyelaBundle:participation:_cell.html.twig',
            [
                'participation' => $participation,
                'choices' => $newChoice->getPoll()->getChoices(),
                'event' => $participation->getEvent(),
                'participant' => $participation->getParticipant()
            ]
        );
    }

    /**
     * Removes a Participation on the fly
     *
     * @Route("/delete/{participation}", name="participation_delete", methods="GET")
     */
    public function deleteAction(Participation $participation)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($participation);
        $em->flush();
        return $this->render(
            'KyelaBundle:participation:_cell.html.twig',
            [
                'participation' => null,
                'choices' => $participation->getEvent()->getPoll()->getChoices(),
                'event' => $participation->getEvent(),
                'participant' => $participation->getParticipant()
            ]
        );
    }
}
