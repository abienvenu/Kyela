<?php

namespace Abienvenu\KyelaBundle\Controller;

use Abienvenu\KyelaBundle\Entity\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\AbstractType;

abstract class AbstractController extends Controller
{
	abstract public function newAction();
	abstract public function createAction(Request $request);
	abstract public function editAction($id);
	abstract public function updateAction(Request $request, $id);
	abstract public function deleteAction(Request $request, $id);

    protected function doCreateorNewAction(AbstractType $formType, Entity $entity, Request $request = null)
    {
    	$form = $this->doCreateCreateForm($formType, $entity, $this->createRoute);
        if ($request)
        {
	        $form->handleRequest($request);

	        if ($form->get('actions')->get('cancel')->isClicked()) {
	        	return $this->redirect($this->generateUrl($this->cancelRoute));
	        }

	        if ($form->isValid()) {
	            $em = $this->getDoctrine()->getManager();
	            $em->persist($entity);
	            $em->flush();

	            return $this->redirect($this->generateUrl($this->successRoute));
	        }
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    protected function doEditOrUpdateAction(AbstractType $formType, $id, Request $request = null)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository($this->entityName)->find($id);

        if (!$entity) {
            throw $this->createNotFoundException("Unable to find entity.");
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->doCreateEditForm($formType, $entity, $this->updateRoute);
        if ($request)
        {
	        $editForm->handleRequest($request);

	        if ($editForm->get('actions')->get('cancel')->isClicked()) {
	        	return $this->redirect($this->generateUrl($this->cancelRoute));
	        }

	        if ($editForm->isValid()) {
	            $em->flush();

	            return $this->redirect($this->generateUrl($this->successRoute));
	        }
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    public function doDeleteAction(Request $request, $id)
    {
    	$form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
        	$em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository($this->entityName)->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find entity.');
            }

            $em->remove($entity);
            $em->flush();
        }
        return $this->redirect($this->generateUrl($this->successRoute));
    }

    protected function doCreateCreateForm(AbstractType $formType, Entity $entity, $action)
    {
        $form = $this->createForm($formType, $entity, array(
            'action' => $this->generateUrl($action),
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
     * Creates a form to edit an entity
     *
     * @param AbstractType $formType The form builder
     * @param Entity $entity The entity id
     * @param string $action The route to the action
     *
     * @return \Symfony\Component\Form\Form The form
     */
    protected function doCreateEditForm(AbstractType $formType, Entity $entity, $action)
    {
        $form = $this->createForm($formType, $entity, array(
            'action' => $this->generateUrl($action, array('id' => $entity->getId())),
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
     * Creates a form to delete an entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl($this->deleteRoute, array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', ['label' => 'delete', 'attr' => ['type' => 'danger']])
            ->getForm();
    }
}
