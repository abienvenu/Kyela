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
	protected $updateCancelUrl = 'choice';
	protected $updateSuccessUrl = 'choice';

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

        $entities = $em->getRepository('KyelaBundle:Choice')->findAll();

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
        $entity = new Choice();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->get('actions')->get('cancel')->isClicked()) {
        	return $this->redirect($this->generateUrl('choice'));
        }

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('choice'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
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
        $entity = new Choice();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
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
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('KyelaBundle:Choice')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Choice entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
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
    	return parent::updateAction($request, $id);
    }
    /**
     * Deletes a Choice entity.
     *
     * @Route("/{id}", name="choice_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('KyelaBundle:Choice')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Choice entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('choice'));
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
