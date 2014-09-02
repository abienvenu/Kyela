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
class EventController extends AbstractController
{
	protected $entityName = 'KyelaBundle:Event';
	protected $cancelRoute = 'index';
	protected $successRoute = 'index';
	protected $deleteRoute = 'event_delete';
	protected $createRoute = 'event_create';
	protected $updateRoute = 'event_update';

    /**
     * Displays a form to create a new Event entity.
     *
     * @Route("/new", name="event_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
    	return $this->doCreateorNewAction(new EventType(), new Event());
    }

    /**
     * Creates a new Event entity.
     *
     * @Route("/", name="event_create")
     * @Method("POST")
     * @Template("KyelaBundle:Event:new.html.twig")
     */
    public function createAction(Request $request)
    {
    	return $this->doCreateorNewAction(new EventType(), new Event(), $request);
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
    	return $this->doEditOrUpdateAction(new EventType(), $id);
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
    	return $this->doEditOrUpdateAction(new EventType(), $id, $request);
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
