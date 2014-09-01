<?php

namespace Abienvenu\KyelaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SuperController extends Controller
{
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository($this->entityName)->find($id);

        if (!$entity) {
            throw $this->createNotFoundException("Unable to find entity.");
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->get('actions')->get('cancel')->isClicked()) {
        	return $this->redirect($this->generateUrl($this->updateCancelUrl));
        }

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl($this->updateSuccessUrl));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
}
