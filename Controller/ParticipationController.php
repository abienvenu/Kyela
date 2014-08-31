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
     * Creates a new Participation on the fly.
     *
     * @Route("/new/{event}/{participant}/{choice}", name="participation_new")
     * @Method("GET")
     */
    public function newAction($event, $participant, $choice)
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
     * Edits a Participation on the fly
     *
     * @Route("/{id}/edit/{event}/{participant}/{choice}", name="participation_edit")
     * @Method("GET")
     */
    public function editAction($id, $event, $participant, $choice)
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
     * Removes a Participation on the fly
     *
     * @Route("/{id}/delete", name="participation_delete")
     * @Method("GET")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('KyelaBundle:Participation')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Participation entity.');
        }
        $em->remove($entity);
        $em->flush();
        return $this->redirect($this->generateUrl('index'));
    }
}
