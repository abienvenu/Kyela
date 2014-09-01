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
	protected $updateCancelUrl = 'index';
	protected $updateSuccessUrl = 'index';

    /**
     * Creates a new Participant entity.
     *
     * @Route("/", name="participant_create")
     * @Method("POST")
     * @Template("KyelaBundle:Participant:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Participant();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->get('actions')->get('cancel')->isClicked()) {
        	return $this->redirect($this->generateUrl('index'));
        }

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('index'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
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
        $form = $this->createForm(new ParticipantType(), $entity, array(
            'action' => $this->generateUrl('participant_create'),
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
     * Displays a form to create a new Participant entity.
     *
     * @Route("/new", name="participant_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Participant();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
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
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository($this->entityName)->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
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
    * Creates a form to edit a Participant entity.
    *
    * @param Participant $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    protected function createEditForm(Participant $entity)
    {
        $form = $this->createForm(new ParticipantType(), $entity, array(
            'action' => $this->generateUrl('participant_update', array('id' => $entity->getId())),
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
     * Edits an existing Participant entity.
     *
     * @Route("/{id}", name="participant_update")
     * @Method("PUT")
     * @Template("KyelaBundle:Participant:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
    	return parent::updateAction($request, $id);
    }
    /**
     * Deletes a Participant entity.
     *
     * @Route("/{id}", name="participant_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
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

        return $this->redirect($this->generateUrl('index'));
    }

    /**
     * Creates a form to delete a Participant entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('participant_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', ['label' => 'delete', 'attr' => ['type' => 'danger']])
            ->getForm();
    }
}
