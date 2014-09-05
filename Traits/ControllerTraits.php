<?php
/**
 * Copyright 2014 Arnaud Bienvenu
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

namespace Abienvenu\KyelaBundle\Traits;

use Abienvenu\KyelaBundle\Entity\Entity;
use Abienvenu\KyelaBundle\Entity\Poll;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\AbstractType;

trait ControllerTraits
{
	protected $poll = null;

	abstract public function newAction();
	abstract public function createAction(Request $request);
	abstract public function editAction($id);
	abstract public function updateAction(Request $request, $id);
	abstract public function deleteAction(Request $request, $id);

	/**
	 * Create a form to create a new entity, and create it when the form is submited
	 *
	 * @param AbstractType $formType
	 * @param Entity $entity
	 * @param Request $request
	 */
    protected function doCreateorNewAction(AbstractType $formType, Entity $entity, Request $request = null)
    {
    	$form = $this->doCreateCreateForm($formType, $entity, $this->createRoute);
        if ($request)
        {
	        $form->handleRequest($request);

	        if ($entity instanceof Poll)
	        {
	        	$this->poll = $entity;
	        }
	        else
	        {
	        	$entity->setPoll($this->poll);
	        }

	        if ($form->get('actions')->get('cancel')->isClicked()) {
	        	return $this->redirect($this->generateUrl($this->cancelRoute, ['pollUrl' => $this->poll->getUrl()]));
	        }

	        if ($form->isValid()) {
	            $em = $this->getDoctrine()->getManager();
	            $em->persist($entity);
	            $em->flush();

	            return $this->redirect($this->generateUrl($this->successRoute, ['pollUrl' => $this->poll->getUrl()]));
	        }
        }

        return [
        	'poll'   => $this->poll,
            'entity' => $entity,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Create a form to edit an entity, and update it when the form is submited
     *
     * @param AbstractType $formType
     * @param int $id The entity id
     * @param Request $request
     */
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
	        	return $this->redirect($this->generateUrl($this->cancelRoute, ['pollUrl' => $this->poll->getUrl()]));
	        }

	        if ($editForm->isValid()) {
	            $em->flush();

	            return $this->redirect($this->generateUrl($this->successRoute, ['pollUrl' => $this->poll->getUrl()]));
	        }
        }

        return array(
        	'poll'        => $this->poll,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes an entity
     *
     * @param Request $request
     * @param mixed $id The entity id
     *
     */
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
        return $this->redirect($this->generateUrl($this->successRoute, ['pollUrl' => $this->poll->getUrl()]));
    }

    /**
     * Creates a form to create an entity
     *
     * @param AbstractType $formType The form builder
     * @param Entity $entity The new entity
     * @param string $action The name of the route to the action
     *
     * @return \Symfony\Component\Form\Form The form
     */
    protected function doCreateCreateForm(AbstractType $formType, Entity $entity, $action)
    {
        $form = $this->createForm($formType, $entity, array(
            'action' => $this->generateUrl($action, ['pollUrl' => $this->poll->getUrl()]),
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
     * @param Entity $entity The entity to edit
     * @param string $action The name of the route to the action
     *
     * @return \Symfony\Component\Form\Form The form
     */
    protected function doCreateEditForm(AbstractType $formType, Entity $entity, $action)
    {
        $form = $this->createForm($formType, $entity, array(
            'action' => $this->generateUrl($action, ['pollUrl' => $this->poll->getUrl(), 'id' => $entity->getId()]),
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
     * @param int $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl($this->deleteRoute, ['pollUrl' => $this->poll->getUrl(), 'id' => $id]))
            ->setMethod('DELETE')
            ->add('submit', 'submit', ['label' => 'delete', 'attr' => ['type' => 'danger']])
            ->getForm();
    }

    /**
     * Set poll from Url
     */
    public function setPollFromRequest(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
    	$pollUrl = $request->get('pollUrl');
    	$polls = $em->getRepository('KyelaBundle:Poll')->findByUrl($pollUrl);
    	if ($polls)
    	{
    		$this->poll = $em->getRepository('KyelaBundle:Poll')->findByUrl($pollUrl)[0];
    	}
    	else
    	{
    		$this->poll = new Poll();
    		$this->poll->setUrl("-");
    	}
    }
}
