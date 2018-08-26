<?php
/*
 * Copyright 2014-2018 Arnaud Bienvenu
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

use Abienvenu\KyelaBundle\Form\Type\FormActionsType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Abienvenu\KyelaBundle\Entity\Poll;
use Abienvenu\KyelaBundle\Entity\Choice;
use Abienvenu\KyelaBundle\Entity\Participant;
use Abienvenu\KyelaBundle\Form\Type\PollType;
use Abienvenu\KyelaBundle\Form\Type\NewPollType;
use Abienvenu\KyelaBundle\Form\Type\LockPollType;
use Abienvenu\KyelaBundle\Form\Type\ParticipantType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
     * @Route("", name="poll_new", methods={"GET", "POST"})
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

        $baseUrl = $this->generateUrl('poll_show', ['pollUrl' => $poll->getUrl()], UrlGeneratorInterface::ABSOLUTE_URL);
        $successMessage = $this->get('translator')->trans('poll.created %url%', ['%url%' => $baseUrl]);

        return $this->doNewAction(NewPollType::class, $poll, $request, $successMessage);
    }

    /**
     * Shows the poll
     *
     * @Route("/{pollUrl}/", name="poll_show", methods="GET")
     * @Template()
     */
    public function showAction()
    {
        $em = $this->getDoctrine()->getManager();
        $hasPastEvents = count($em->getRepository('KyelaBundle:Event')->getFutureOrPastEvents($this->poll, false));

        $participant_form = $this->createForm(ParticipantType::class, new Participant(), [
            'action' => $this->generateUrlWithPoll('participant_new'),
            'method' => 'POST'
        ]);

        return [
            'poll' => $this->poll,
            'hasPastEvents' => $hasPastEvents,
            'participant_form' => $participant_form->createView(),
        ];
    }

    /**
     * Shows a simplified poll to be used in iframe
     *
     * @Route("/{pollUrl}/frame", name="poll_showframe", methods="GET")
     * @Template()
     */
    public function frameAction()
    {
        return ['poll' => $this->poll];
    }

    /**
     * Shows events only (to use withing iframes)
     *
     * @Route("/{pollUrl}/events", methods="GET")
     * @Template()
     */
    public function eventsAction()
    {
        return ['poll' => $this->poll];
    }

    /**
     * Shows participations only (to use withing iframes)
     *
     * @Route("/{pollUrl}/participations", methods="GET")
     * @Template()
     */
    public function participationsAction()
    {
        return ['poll' => $this->poll];
    }

    /**
     * Shows the poll with past events only
     *
     * @Route("/{pollUrl}/archive", name="poll_archive", methods="GET")
     * @Template()
     */
    public function archiveAction()
    {
        return ['poll' => $this->poll];
    }

    /**
     * Displays a form to edit an existing Poll entity.
     *
     * @Route("/{pollUrl}/edit", name="poll_edit", methods={"GET", "PUT"})
     * @Template()
     */
    public function editAction(Request $request, $pollUrl)
    {
        if ($this->poll->getAccessCode()) {
            return $this->redirect($this->generateUrl('poll_unlock', ['pollUrl' => $this->poll->getUrl()]));
        }
        $oldUrl = $this->poll->getUrl();
        $response = $this->doEditAction(PollType::class, $this->poll->getId(), $request);
        if ($request->isMethod('PUT') && $oldUrl != $this->poll->getUrl())
        {
            $baseUrl = $this->generateUrl('poll_show', ['pollUrl' => $this->poll->getUrl()], UrlGeneratorInterface::ABSOLUTE_URL);
            $flashMessage = $this->get('translator')->trans('poll.modified %url%', ['%url%' => $baseUrl]);
            $request->getSession()->getFlashBag()->add('success', $flashMessage);
        }
        return $response;
    }

    /**
     * Deletes a Poll entity.
     *
     * @Route("/{pollUrl}/", name="poll_delete", methods="DELETE")
     */
    public function deleteAction(Request $request, $pollUrl)
    {
        return $this->doDeleteAction($request, $this->poll->getId());
    }

    /**
     * Display a form to setup a lock on the Poll
     *
     * @Route("/{pollUrl}/lock", name="poll_lock", methods={"GET", "PUT"})
     * @Template()
     */
    public function lockAction(Request $request)
    {
        $form = $this->createForm(LockPollType::class, $this->poll, [
            'method' => 'PUT',
        ]);

        $form->add('actions', FormActionsType::class, [
            'buttons' => [
                'save' => ['type' => SubmitType::class, 'options' => ['label' => 'save']],
                'cancel' => ['type' => SubmitType::class, 'options' => ['label' => 'cancel', 'attr' => ['type' => 'default', 'novalidate' => true]]],
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
     * @Route("/{pollUrl}/unlock", name="poll_unlock", methods={"GET", "PUT"})
     * @Template()
     */
    public function unlockAction(Request $request)
    {
        $poll = (new Poll)->setTitle("dummy")->setUrl("dummy");
        $form = $this->createForm(LockPollType::class, $poll, [
            'method' => 'PUT',
        ]);
        $form->add('actions', FormActionsType::class, [
            'buttons' => [
                'save' => ['type' => SubmitType::class, 'options' => ['label' => 'save']],
                'cancel' => ['type' => SubmitType::class, 'options' => ['label' => 'cancel', 'attr' => ['type' => 'default', 'novalidate' => true]]],
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
