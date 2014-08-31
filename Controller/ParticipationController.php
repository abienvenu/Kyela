<?php

namespace Abienvenu\KyelaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Abienvenu\KyelaBundle\Entity\Participation;
use Abienvenu\KyelaBundle\Form\ParticipationType;

/**
 * Participation controller.
 *
 * @Route("/participation")
 */
class ParticipationController extends Controller
{

    /**
     * Creates a new Participation entity.
     *
     * @Route("/", name="participation_create")
     * @Method("POST")
     * @Template("KyelaBundle:Participation:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Participation();
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
     * Creates a form to create a Participation entity.
     *
     * @param Participation $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Participation $entity)
    {
        $form = $this->createForm(new ParticipationType(), $entity, array(
            'action' => $this->generateUrl('participation_create'),
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
     * Displays a form to create a new Participation entity.
     *
     * @Route("/new", name="participation_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Participation();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Participation on the fly.
     *
     * @Route("/new/{event}/{participant}/{choice}", name="participation_new_onthefly")
     * @Method("GET")
     */
    public function newActionOnTheFly($event, $participant, $choice)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new Participation();
        $eventObj = $em->getRepository('KyelaBundle:Event')->find($event);
        $entity->setEvent($eventObj);
        $participantObj = $em->getRepository('KyelaBundle:Participant')->find($participant);
        $entity->setParticipant($participantObj);
        $choiceObj = $em->getRepository('KyelaBundle:Choice')->find($choice);
        $entity->setChoice($choiceObj);
        $em->persist($entity);
        $em->flush();

    	return $this->redirect($this->generateUrl('index'));
    }

    /**
     * Displays a form to edit an existing Participation entity.
     *
     * @Route("/{id}/edit", name="participation_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('KyelaBundle:Participation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Participation entity.');
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
     * Edits a Participation on the fly
     *
     * @Route("/{id}/edit/{event}/{participant}/{choice}", name="participation_edit_onthefly")
     * @Method("GET")
     */
    public function editActionOnTheFly($id, $event, $participant, $choice)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('KyelaBundle:Participation')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Participation entity.');
        }

        $eventObj = $em->getRepository('KyelaBundle:Event')->find($event);
        $entity->setEvent($eventObj);
        $participantObj = $em->getRepository('KyelaBundle:Participant')->find($participant);
        $entity->setParticipant($participantObj);
        $choiceObj = $em->getRepository('KyelaBundle:Choice')->find($choice);
        $entity->setChoice($choiceObj);
        $em->persist($entity);
        $em->flush();

    	return $this->redirect($this->generateUrl('index'));
    }

    /**
    * Creates a form to edit a Participation entity.
    *
    * @param Participation $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Participation $entity)
    {
        $form = $this->createForm(new ParticipationType(), $entity, array(
            'action' => $this->generateUrl('participation_update', array('id' => $entity->getId())),
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
     * Edits an existing Participation entity.
     *
     * @Route("/{id}", name="participation_update")
     * @Method("PUT")
     * @Template("KyelaBundle:Participation:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('KyelaBundle:Participation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Participation entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->get('actions')->get('cancel')->isClicked()) {
        	return $this->redirect($this->generateUrl('index'));
        }

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('index', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Participation entity.
     *
     * @Route("/{id}", name="participation_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('KyelaBundle:Participation')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Participation entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('index'));
    }

    /**
     * Creates a form to delete a Participation entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('participation_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', ['label' => 'delete', 'attr' => ['type' => 'danger']])
            ->getForm()
        ;
    }
}
