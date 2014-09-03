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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Abienvenu\KyelaBundle\Traits\ControllerTraits;
use Abienvenu\KyelaBundle\Entity\Participant;
use Abienvenu\KyelaBundle\Form\ParticipantType;

/**
 * Participant controller.
 *
 * @Route("/{pollUrl}/participant")
 */
class ParticipantController extends Controller
{
	use ControllerTraits;

	protected $entityName = 'KyelaBundle:Participant';
	protected $cancelRoute = 'poll_show';
	protected $successRoute = 'poll_show';
	protected $deleteRoute = 'participant_delete';
	protected $createRoute = 'participant_create';
	protected $updateRoute = 'participant_update';

    /**
     * Displays a form to create a new Participant entity.
     *
     * @Route("/new", name="participant_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
    	return $this->doCreateorNewAction(new ParticipantType(), new Participant());
    }

	/**
     * Creates a new Participant entity.
     *
     * @Route("/", name="participant_create")
     * @Method("POST")
     * @Template("KyelaBundle:Participant:new.html.twig")
     */
    public function createAction(Request $request)
    {
    	return $this->doCreateorNewAction(new ParticipantType(), new Participant(), $request);
    }

    /**
     * Displays a form to edit an existing Participant entity.
     *
     * @Route("/{id}/edit", name="participant_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
    	return $this->doEditOrUpdateAction(new ParticipantType(), $id);
    }

    /**
     * Edits an existing Participant entity.
     *
     * @Route("/{id}", name="participant_update")
     * @Method("PUT")
     * @Template("KyelaBundle:Participant:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
    	return $this->doEditOrUpdateAction(new ParticipantType(), $id, $request);
    }
    /**
     * Deletes a Participant entity.
     *
     * @Route("/{id}", name="participant_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
    	return $this->doDeleteAction($request, $id);
    }
}
