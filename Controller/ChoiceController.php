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
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Abienvenu\KyelaBundle\Entity\Choice;
use Abienvenu\KyelaBundle\Form\Type\ChoiceType;

/**
 * Choice controller.
 *
 * @Route("/{pollUrl}/choice")
 */
class ChoiceController extends CRUDController
{
    protected $entityName = 'KyelaBundle:Choice';
    protected $cancelRoute = 'choice';
    protected $successRoute = 'choice';
    protected $deleteRoute = 'choice_delete';
    protected $deleteSuccessRoute = 'choice';

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
        $choices = $em->getRepository("KyelaBundle:Choice")->getOrderedChoices($this->poll);
        return ['poll' => $this->poll, 'choices' => $choices];
    }

    /**
     * Displays a form to create a new Choice entity.
     *
     * @Route("/new", name="choice_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $choice = new Choice();
        // By default, this new choice will be added at the end
        $choice->setPriority(count($this->poll->getChoices()));
        return $this->doNewAction(ChoiceType::class, $choice, $request);
    }

    /**
     * Displays a form to edit an existing Choice entity.
     *
     * @Route("/{id}/edit", name="choice_edit")
     * @Method({"GET", "PUT"})
     * @Template()
     */
    public function editAction(Request $request, $id)
    {
        return $this->doEditAction(ChoiceType::class, $id, $request);
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

    /**
     * Reorder choices
     *
     * @Route("/order", name="choice_order")
     * @Method("POST")
     */
    public function orderAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("KyelaBundle:Choice");
        $order = $request->request->get('choice');
        foreach ($order as $priority => $choiceId)
        {
            $choice = $repository->find($choiceId);
            $choice->setPriority($priority);
        }
        $em->flush();

        $response = new JsonResponse();
        $response->setData(array("code" => 100, "success" => true));
        return $response;
    }
}
