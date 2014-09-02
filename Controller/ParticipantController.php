<?php

namespace Abienvenu\KyelaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Abienvenu\KyelaBundle\Entity\Participant;
use Abienvenu\KyelaBundle\Form\ParticipantType;

/**
 * Participant controller.
 *
 * @Route("/participant")
 */
class ParticipantController extends AbstractController
{
	protected $entityName = 'KyelaBundle:Participant';
	protected $cancelRoute = 'index';
	protected $successRoute = 'index';
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
