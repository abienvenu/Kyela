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
class ParticipantController extends SuperController
{
	protected $entityName = 'KyelaBundle:Participant';
	protected $cancelUrl = 'index';
	protected $successUrl = 'index';
	protected $deleteAction = 'participant_delete';

    /**
     * Creates a new Participant entity.
     *
     * @Route("/", name="participant_create")
     * @Method("POST")
     * @Template("KyelaBundle:Participant:new.html.twig")
     */
    public function createAction(Request $request)
    {
    	return $this->doCreateAction($request, new Participant());
    }

    /**
     * Creates a form to create a Participant entity.
     *
     * @param Participant $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createCreateForm(Participant $entity)
    {
    	return $this->doCreateCreateForm(new ParticipantType(), $entity, 'participant_create');
    }

    /**
     * Displays a form to create a new Participant entity.
     *
     * @Route("/new", name="participant_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
    	return $this->doNewAction(new Participant());
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
    	return $this->doEditAction($id);
    }

    /**
    * Creates a form to edit a Participant entity.
    *
    * @param Participant $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    protected function createEditForm(Participant $entity)
    {
    	return $this->doCreateEditForm(new ParticipantType(), $entity, 'participant_update');
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
    	return $this->doUpdateAction($request, $id);
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
