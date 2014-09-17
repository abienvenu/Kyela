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

namespace Abienvenu\KyelaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Abienvenu\KyelaBundle\Entity\Poll;
use Abienvenu\KyelaBundle\Entity\Choice;
use Abienvenu\KyelaBundle\Form\PollType;
use Abienvenu\KyelaBundle\Form\NewPollType;

/**
 * Poll controller.
 *
 * @Route("/")
 */
class PollController extends Controller
{
	use \Abienvenu\KyelaBundle\Traits\CRUDTrait;
	use \Abienvenu\KyelaBundle\Traits\PollSetterTrait;

	protected $entityName = 'KyelaBundle:Poll';
	protected $cancelRoute = 'poll_show';
	protected $successRoute = 'poll_show';
	protected $deleteRoute = 'poll_delete';
	protected $deleteSuccessRoute = 'poll_new';

    /**
     * Displays a form to create a new Poll entity.
     *
     * @Route("/", name="poll_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
    	$poll = new Poll();
    	if ($request->isMethod('POST'))
    	{
    		// Setup default values
	    	$poll->setUrl(uniqid());
	    	$t = $this->get('translator');
	    	$poll->addChoice((new Choice)->setName($t->trans("yes"))->setValue(1)->setColor("green")->setPriority(0)->setPoll($poll));
	    	$poll->addChoice((new Choice)->setName($t->trans("maybe"))->setValue(0)->setColor("orange")->setPriority(1)->setPoll($poll));
	    	$poll->addChoice((new Choice)->setName($t->trans("no"))->setValue(0)->setColor("red")->setPriority(2)->setPoll($poll));
	    	$baseUrl = $this->generateUrl('poll_show', ['pollUrl' => $poll->getUrl()], true);
	    	$message = $this->get('translator')->trans('poll.created %url%', ['%url%' => $baseUrl]);
	    	$request->getSession()->getFlashBag()->add('success', $message);
    	}
    	return $this->doNewAction(new NewPollType(), $poll, $request);
    }

    /**
     * Displays a form to edit an existing Poll entity.
     *
     * @Route("/{pollUrl}/", name="poll_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($pollUrl)
    {
    	$em = $this->getDoctrine()->getManager();
    	$events = $em->getRepository("KyelaBundle:Event")->getFutureEvents($this->poll);
    	$participationsArray = [];
    	foreach ($events as $event)
    	{
    		foreach ($event->getParticipations() as $participation)
    		{
    			$accessKey = "{$event->getId()}-{$participation->getParticipant()->getId()}";
    			$participationsArray[$accessKey] = $participation;
    		}
    	}
        return [
        	'poll' => $this->poll,
        	'events' => $events,
        	'participations' => $participationsArray,
		];
    }

    /**
     * Displays a form to edit an existing Poll entity.
     *
     * @Route("/{pollUrl}/edit", name="poll_edit")
     * @Method({"GET", "PUT"})
     * @Template()
     */
    public function editAction(Request $request)
    {
    	$response = $this->doEditAction(new PollType(), $this->poll->getId(), $request);
    	if ($request->isMethod('PUT'))
    	{
	        $baseUrl = $this->generateUrl('poll_show', ['pollUrl' => $this->poll->getUrl()], true);
		    $message = $this->get('translator')->trans('poll.modified %url%', ['%url%' => $baseUrl]);
		    $request->getSession()->getFlashBag()->add('success', $message);
    	}
    	return $response;
    }

    /**
     * Deletes a Poll entity.
     *
     * @Route("/{pollUrl}/", name="poll_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $pollUrl)
    {
    	return $this->doDeleteAction($request, $this->poll->getId());
    }
}
