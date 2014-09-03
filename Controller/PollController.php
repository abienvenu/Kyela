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

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Abienvenu\KyelaBundle\AbstractController;
use Abienvenu\KyelaBundle\Entity\Poll;
use Abienvenu\KyelaBundle\Form\PollType;

/**
 * Poll controller.
 *
 * @Route("/")
 */
class PollController extends AbstractController
{
	protected $entityName = 'KyelaBundle:Poll';
	protected $cancelRoute = 'poll_new';
	protected $successRoute = 'poll_show';
	protected $deleteRoute = 'poll_delete';
	protected $createRoute = 'poll_create';
	protected $updateRoute = 'poll_update';

    /**
     * Displays a form to create a new Poll entity.
     *
     * @Route("/", name="poll_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
    	return $this->doCreateorNewAction(new PollType(), new Poll());
    }

    /**
     * Creates a new Poll entity.
     *
     * @Route("/", name="poll_create")
     * @Method("POST")
     * @Template("KyelaBundle:Poll:new.html.twig")
     */
    public function createAction(Request $request)
    {
    	return $this->doCreateorNewAction(new PollType(), new Poll(), $request);
    }

    /**
     * Displays a form to edit an existing Poll entity.
     *
     * @Route("/{pollUrl}/", name="poll_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($pollUrl)
    {
        $em = $this->getDoctrine()->getManager();
        $participants = $em->getRepository('KyelaBundle:Participant')->findAll();
        $events = $em->getRepository('KyelaBundle:Event')->findAll();
        $choices = $em->getRepository('KyelaBundle:Choice')->findAll();
        $participations = $em->getRepository('KyelaBundle:Participation')->findAll();
        $participationsArray = [];
        foreach ($participations as $participation)
        {
        	$accessKey = "{$participation->getEvent()->getId()}-{$participation->getParticipant()->getId()}";
        	$participationsArray[$accessKey] = $participation;
        }
        return [
        	'poll' => $this->poll,
        	'participants' => $participants,
        	'events' => $events,
        	'choices' => $choices,
        	'participations' => $participationsArray,
		];
    }

    /**
     * Displays a form to edit an existing Poll entity.
     *
     * @Route("/{pollUrl}/edit", name="poll_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($pollUrl)
    {
    	return $this->doEditOrUpdateAction(new PollType(), $id);
    }

    /**
     * Edits an existing Poll entity.
     *
     * @Route("/{pollUrl}/", name="poll_update")
     * @Method("PUT")
     * @Template("KyelaBundle:Poll:edit.html.twig")
     */
    public function updateAction(Request $request, $pollUrl)
    {
    	return $this->doEditOrUpdateAction(new PollType(), $id, $request);
    }

    /**
     * Deletes a Poll entity.
     *
     * @Route("/{pollUrl}/", name="poll_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $pollUrl)
    {
    	return $this->doDeleteAction($request, $id);
    }
}
