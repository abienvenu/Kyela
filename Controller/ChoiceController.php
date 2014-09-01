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
        $form = $this->createForm(new ChoiceType(), $entity, array(
            'action' => $this->generateUrl('choice_create'),
            'method' => 'POST',
        ));

        $form->add('actions', 'form_actions', [
        	'buttons' => [
        		'save' => ['type' => 'submit', 'options' => ['label' => 'create']],
        		'cancel' => ['type' => 'submit', 'options' => ['label' => 'cancel', 'attr' => ['type' => 'default', 'novalidate' => true]]],
        	]
        ]);

        return $form;
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
        $form = $this->createForm(new ChoiceType(), $entity, array(
            'action' => $this->generateUrl('choice_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('actions', 'form_actions', [
        	'buttons' => [
        		'save' => ['type' => 'submit', 'options' => ['label' => 'save']],
        		'cancel' => ['type' => 'submit', 'options' => ['label' => 'cancel', 'attr' => ['type' => 'default', 'novalidate' => true]]],
        	]
        ]);
        return $form;
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

    /**
     * Creates a form to delete a Choice entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('choice_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', ['label' => 'delete', 'attr' => ['type' => 'danger']])
            ->getForm();
    }
}
