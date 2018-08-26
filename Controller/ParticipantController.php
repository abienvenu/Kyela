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

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Abienvenu\KyelaBundle\Entity\Participant;
use Abienvenu\KyelaBundle\Form\Type\ParticipantType;

/**
 * Participant controller.
 *
 * @Route("/{pollUrl}/participant")
 */
class ParticipantController extends CRUDController
{
    protected $entityName = 'KyelaBundle:Participant';
    protected $cancelRoute = 'poll_show';
    protected $successRoute = 'poll_show';
    protected $deleteRoute = 'participant_delete';
    protected $deleteSuccessRoute = 'poll_show';

    /**
     * Displays a form to create a new Participant entity.
     *
     * @Route("/new", name="participant_new", methods={"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        return $this->doNewAction(ParticipantType::class, new Participant(), $request);
    }

    /**
     * Displays a form to edit an existing Participant entity.
     *
     * @Route("/{id}/edit", name="participant_edit", methods={"GET", "PUT"})
     * @Template()
     */
    public function editAction(Request $request, $id)
    {
        return $this->doEditAction(ParticipantType::class, $id, $request);
    }

    /**
     * Deletes a Participant entity.
     *
     * @Route("/{id}", name="participant_delete", methods="DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        return $this->doDeleteAction($request, $id);
    }

    /**
     * Reorder participant
     *
     * @Route("/order", name="participant_order", methods="POST")
     */
    public function orderAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("KyelaBundle:Participant");
        $order = $request->request->get('participant');
        foreach ($order as $priority => $participantId)
        {
            /** @var Participant $choice */
            $participant = $repository->find($participantId);
            $participant->setPriority($priority);
        }
        $em->flush();

        $response = new JsonResponse();
        $response->setData(["code" => 100, "success" => true]);
        return $response;
    }
}
