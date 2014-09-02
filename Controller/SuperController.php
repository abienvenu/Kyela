<?php

namespace Abienvenu\KyelaBundle\Controller;

use Abienvenu\KyelaBundle\Entity\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\AbstractType;

abstract class SuperController extends Controller
{
    private function doCreateorNewAction(Entity $entity, Request $request = null)
    {
        $form = $this->createCreateForm($entity);
        if ($request)
        {
	        $form->handleRequest($request);

	        if ($form->get('actions')->get('cancel')->isClicked()) {
	        	return $this->redirect($this->generateUrl($this->cancelUrl));
	        }

	        if ($form->isValid()) {
	            $em = $this->getDoctrine()->getManager();
	            $em->persist($entity);
	            $em->flush();

	            return $this->redirect($this->generateUrl($this->successUrl));
	        }
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    public function doCreateAction(Request $request, Entity $entity)
    {
    	return $this->doCreateorNewAction($entity, $request);
    }

    public function doNewAction(Entity $entity)
    {
    	return $this->doCreateorNewAction($entity);
    }


    private function doEditOrUpdateAction($id, Request $request = null)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository($this->entityName)->find($id);

        if (!$entity) {
            throw $this->createNotFoundException("Unable to find entity.");
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        if ($request)
        {
	        $editForm->handleRequest($request);

	        if ($editForm->get('actions')->get('cancel')->isClicked()) {
	        	return $this->redirect($this->generateUrl($this->cancelUrl));
	        }

	        if ($editForm->isValid()) {
	            $em->flush();

	            return $this->redirect($this->generateUrl($this->successUrl));
	        }
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    public function doUpdateAction(Request $request, $id)
    {
    	return $this->doEditOrUpdateAction($id, $request);
    }

    public function doEditAction($id)
    {
    	return $this->doEditOrUpdateAction($id);
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
        return $this->redirect($this->generateUrl($this->successUrl));
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
            ->setAction($this->generateUrl($this->deleteAction, array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', ['label' => 'delete', 'attr' => ['type' => 'danger']])
            ->getForm();
    }
}
