<?php

namespace Abienvenu\KyelaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Abienvenu\KyelaBundle\Entity\Event;
use Abienvenu\KyelaBundle\Form\EventType;

/**
 * Event controller.
 *
 * @Route("/event")
 */
class EventController extends SuperController
{
	protected $entityName = 'KyelaBundle:Event';
	protected $cancelUrl = 'index';
	protected $successUrl = 'index';
	protected $deleteAction = 'event_delete';

    /**
     * Creates a new Event entity.
     *
     * @Route("/", name="event_create")
     * @Method("POST")
     * @Template("KyelaBundle:Event:new.html.twig")
     */
    public function createAction(Request $request)
    {
    	return $this->doCreateAction($request, new Event());
    }

    /**
     * Creates a form to create a Event entity.
     *
     * @param Event $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createCreateForm(Event $entity)
    {
    	return $this->doCreateCreateForm(new EventType(), $entity, 'event_create');
    }

    /**
     * Displays a form to create a new Event entity.
     *
     * @Route("/new", name="event_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
    	return $this->doNewAction(new Event());
    }

    /**
     * Displays a form to edit an existing Event entity.
     *
     * @Route("/{id}/edit", name="event_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
    	return $this->doEditAction($id);
    }

    /**
    * Creates a form to edit a Event entity.
    *
    * @param Event $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    protected function createEditForm(Event $entity)
    {
    	return $this->doCreateEditForm(new EventType(), $entity, 'event_update');
    }
    /**
     * Edits an existing Event entity.
     *
     * @Route("/{id}", name="event_update")
     * @Method("PUT")
     * @Template("KyelaBundle:Event:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
    	return $this->doUpdateAction($request, $id);
    }

    /**
     * Deletes a Event entity.
     *
     * @Route("/{id}", name="event_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
    	return $this->doDeleteAction($request, $id);
    }
}
