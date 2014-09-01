<?php

namespace Abienvenu\KyelaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Abienvenu\KyelaBundle\Entity\Choice;
use Abienvenu\KyelaBundle\Form\ChoiceType;

/**
 * Choice controller.
 *
 * @Route("/choice")
 */
class ChoiceController extends SuperController
{
	protected $entityName = 'KyelaBundle:Choice';
	protected $cancelUrl = 'choice';
	protected $successUrl = 'choice';
	protected $deleteAction = 'choice_delete';

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
            'entities' => $entities,
        );
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
    	return $this->doCreateAction($request, new Choice());
    }

    /**
     * Creates a form to create a Choice entity.
     *
     * @param Choice $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createCreateForm(Choice $entity)
    {
    	return $this->doCreateCreateForm(new ChoiceType(), $entity, 'choice_create');
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
    	return $this->doNewAction(new Choice());
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
    	return $this->doEditAction($id);
    }

    /**
    * Creates a form to edit a Choice entity.
    *
    * @param Choice $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    protected function createEditForm(Choice $entity)
    {
    	return $this->doCreateEditForm(new ChoiceType(), $entity, 'choice_update');
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
    	return $this->doUpdateAction($request, $id);
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
