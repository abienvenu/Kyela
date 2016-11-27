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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Abienvenu\KyelaBundle\Entity\Poll;
use Abienvenu\KyelaBundle\Entity\Choice;
use Abienvenu\KyelaBundle\Entity\Participant;
use Abienvenu\KyelaBundle\Form\Type\PollType;
use Abienvenu\KyelaBundle\Form\Type\NewPollType;
use Abienvenu\KyelaBundle\Form\Type\LockPollType;
use Abienvenu\KyelaBundle\Form\Type\ParticipantType;

/**
 * Poll controller.
 *
 * @Route("/")
 */
class PollController extends CRUDController
{
    protected $entityName = 'KyelaBundle:Poll';
    protected $cancelRoute = 'poll_show';
    protected $successRoute = 'poll_show';
    protected $deleteRoute = 'poll_delete';
    protected $deleteSuccessRoute = 'poll_new';

    /**
     * Displays a form to create a new Poll entity.
     *
     * @Route("/", name="poll_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $poll = new Poll();

	    // Setup default (and hidden) values
        $poll->setUrl(uniqid());
        $poll->setHeadLines('');
        $poll->setBottomLines('');
        $poll->setAccessCode('');
        $t = $this->get('translator');
        $poll->addChoice((new Choice)->setName($t->trans('yes'))->setValue(1)->setColor('green')->setPriority(0)->setPoll($poll)->setIcon('ok'));
        $poll->addChoice((new Choice)->setName($t->trans('maybe'))->setValue(0)->setColor('orange')->setPriority(1)->setPoll($poll)->setIcon('time'));
        $poll->addChoice((new Choice)->setName($t->trans('no'))->setValue(0)->setColor('red')->setPriority(2)->setPoll($poll)->setIcon('remove'));

        $baseUrl = $this->generateUrl('poll_show', ['pollUrl' => $poll->getUrl()], true);
        $successMessage = $this->get('translator')->trans('poll.created %url%', ['%url%' => $baseUrl]);

        return $this->doNewAction(new NewPollType(), $poll, $request, $successMessage);
    }

    /**
     * Shows the poll
     *
     * @Route("/{pollUrl}/", name="poll_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction()
    {
        $em = $this->getDoctrine()->getManager();
        $hasPastEvents = count($em->getRepository('KyelaBundle:Event')->getFutureOrPastEvents($this->poll, false));

        $participant_form = $this->createForm(ParticipantType::class, new Participant(), [
            'action' => $this->generateUrl('participant_new'),
            'method' => 'POST'
        ]);

        return [
            'poll' => $this->poll,
            'hasPastEvents' => $hasPastEvents,
            'participant_form' => $participant_form->createView(),
        ];
    }

    /**
     * Shows the poll with past events only
     *
     * @Route("/{pollUrl}/archive", name="poll_archive")
     * @Method("GET")
     * @Template()
     */
    public function archiveAction()
    {
        return ['poll' => $this->poll];
    }

    /**
     * Displays poll events
     *
     * @Method("GET")
     * @Template()
     */
    public function eventsAction($isFuture)
    {
        $em = $this->getDoctrine()->getManager();
        $events = $em->getRepository('KyelaBundle:Event')->getFutureOrPastEvents($this->poll, $isFuture);
        return [
            'poll' => $this->poll,
            'events' => $events,
        ];
    }

    /**
     * Displays interative participation table
     *
     * @Method("GET")
     * @Template()
     */
    public function participationsAction($isFuture)
    {
        $em = $this->getDoctrine()->getManager();
        $events = $em->getRepository('KyelaBundle:Event')->getFutureOrPastEvents($this->poll, $isFuture);
        $choices = $em->getRepository("KyelaBundle:Choice")->getOrderedChoices($this->poll);
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
            'poll' => $this->poll,
            'choices' => $choices,
            'events' => $events,
            'participations' => $participationsArray,
        ];
    }

    /**
     * Displays latest comments
     *
     * @Method("GET")
     * @Template()
     */
    public function commentsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $comments = $em->getRepository('KyelaBundle:Comment')->getLatestComments($this->poll);
        return ['poll' => $this->poll, 'comments' => $comments];
    }

    /**
     * Displays a form to edit an existing Poll entity.
     *
     * @Route("/{pollUrl}/edit", name="poll_edit")
     * @Method({"GET", "PUT"})
     * @Template()
     */
    public function editAction(Request $request, $pollUrl)
    {
        if ($this->poll->getAccessCode()) {
            return $this->redirect($this->generateUrl('poll_unlock', ['pollUrl' => $this->poll->getUrl()]));
        }
        $oldUrl = $this->poll->getUrl();
        $response = $this->doEditAction(new PollType(), $this->poll->getId(), $request);
        if ($request->isMethod('PUT') && $oldUrl != $this->poll->getUrl())
        {
            $baseUrl = $this->generateUrl('poll_show', ['pollUrl' => $this->poll->getUrl()], true);
            $flashMessage = $this->get('translator')->trans('poll.modified %url%', ['%url%' => $baseUrl]);
            $request->getSession()->getFlashBag()->add('success', $flashMessage);
        }
        return $response;
    }

    /**
     * Deletes a Poll entity.
     *
     * @Route("/{pollUrl}/", name="poll_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $pollUrl)
    {
        return $this->doDeleteAction($request, $this->poll->getId());
    }

    /**
     * Display a form to setup a lock on the Poll
     *
     * @Route("/{pollUrl}/lock", name="poll_lock")
     * @Method({"GET", "PUT"})
     * @Template()
     */
    public function lockAction(Request $request)
    {
        $form = $this->createForm(LockPollType::class, $this->poll, array(
            'method' => 'PUT',
        ));

        $form->add('actions', 'form_actions', [
            'buttons' => [
                'save' => ['type' => 'submit', 'options' => ['label' => 'save']],
                'cancel' => ['type' => 'submit', 'options' => ['label' => 'cancel', 'attr' => ['type' => 'default', 'novalidate' => true]]],
            ]
        ]);

        if ($request->isMethod('PUT') && $this->poll)
        {
            $em = $this->getDoctrine()->getManager();
            $form->handleRequest($request);
            if ($form->get('actions')->get('cancel')->isClicked()) {
                $em->refresh($this->poll);
                return $this->redirect($this->generateUrl('poll_edit', ['pollUrl' => $this->poll->getUrl()]));
            }
            if ($form->isValid()) {
                $em->flush();
                $flashMessage = $this->get('translator')->trans('poll.locked %lock%', ['%lock%' => $this->poll->getAccessCode()]);
                $request->getSession()->getFlashBag()->add('success', $flashMessage);
                return $this->redirect($this->generateUrl('poll_show', ['pollUrl' => $this->poll->getUrl()]));
            }
            else {
                $em->refresh($this->poll);
            }
        }

        return [
            'poll'   => $this->poll,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Display a form to unlock the Poll
     *
     * @Route("/{pollUrl}/unlock", name="poll_unlock")
     * @Method({"GET", "PUT"})
     * @Template()
     */
    public function unlockAction(Request $request)
    {
        $poll = (new Poll)->setTitle("dummy")->setUrl("dummy");
        $form = $this->createForm(LockPollType::class, $poll, array(
            'method' => 'PUT',
        ));
        $form->add('actions', 'form_actions', [
            'buttons' => [
                'save' => ['type' => 'submit', 'options' => ['label' => 'save']],
                'cancel' => ['type' => 'submit', 'options' => ['label' => 'cancel', 'attr' => ['type' => 'default', 'novalidate' => true]]],
            ]
        ]);

        if ($request->isMethod('PUT'))
        {
            $form->handleRequest($request);
            if ($form->get('actions')->get('cancel')->isClicked()) {
                return $this->redirect($this->generateUrl('poll_show', ['pollUrl' => $this->poll->getUrl()]));
            }
            if ($form->isValid()) {
                if ($poll->getAccessCode() == $this->poll->getAccessCode()) {
                    $this->poll->setAccessCode('');
                    $em = $this->getDoctrine()->getManager();
                    $em->flush();
                    $flashMessage = $this->get('translator')->trans('poll.unlocked');
                    $request->getSession()->getFlashBag()->add('success', $flashMessage);
                    return $this->redirect($this->generateUrl('poll_edit', ['pollUrl' => $this->poll->getUrl()]));
                }
                else {
                    $flashMessage = $this->get('translator')->trans('unlock.failed');
                    $request->getSession()->getFlashBag()->add('success', $flashMessage);
                }
            }
        }

        return [
            'poll'   => $this->poll,
            'form'   => $form->createView(),
        ];
    }
}
