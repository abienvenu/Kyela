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
use Abienvenu\KyelaBundle\Entity\Choice;
use Abienvenu\KyelaBundle\Form\ChoiceType;

/**
 * Choice controller.
 *
 * @Route("/{pollUrl}/choice")
 */
class ChoiceController extends AbstractController
{
	protected $entityName = 'KyelaBundle:Choice';
	protected $cancelRoute = 'choice';
	protected $successRoute = 'choice';
	protected $deleteRoute = 'choice_delete';
	protected $createRoute = 'choice_create';
	protected $updateRoute = 'choice_update';

    /**
     * Lists all Choice entities.
     *
     * @Route("/", name="choice")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository($this->entityName)->findAll();

        return array(
        	'poll'     => $this->poll,
            'entities' => $entities,
        );
    }

    /**
     * Displays a form to create a new Choice entity.
     *
     * @Route("/new", name="choice_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
    	return $this->doCreateorNewAction(new ChoiceType(), new Choice());
    }

    /**
     * Creates a new Choice entity.
     *
     * @Route("/", name="choice_create")
     * @Method("POST")
     * @Template("KyelaBundle:Choice:new.html.twig")
     */
    public function createAction(Request $request)
    {
    	return $this->doCreateorNewAction(new ChoiceType(), new Choice(), $request);
    }

    /**
     * Displays a form to edit an existing Choice entity.
     *
     * @Route("/{id}/edit", name="choice_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
    	return $this->doEditOrUpdateAction(new ChoiceType(), $id);
    }

    /**
     * Edits an existing Choice entity.
     *
     * @Route("/{id}", name="choice_update")
     * @Method("PUT")
     * @Template("KyelaBundle:Choice:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
    	return $this->doEditOrUpdateAction(new ChoiceType(), $id, $request);
    }

    /**
     * Deletes a Choice entity.
     *
     * @Route("/{id}", name="choice_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
    	return $this->doDeleteAction($request, $id);
    }
}
