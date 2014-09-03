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

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class MainController extends Controller
{
	/**
	 * @Route("/oldmain", name="index")
	 * @Template()
	 */
	public function indexAction(Request $request)
	{
        $em = $this->getDoctrine()->getManager();
        $participants = $em->getRepository('KyelaBundle:Participant')->findAll();
        $events = $em->getRepository('KyelaBundle:Event')->findAll();
        $choices = $em->getRepository('KyelaBundle:Choice')->findAll();
        $participations = $em->getRepository('KyelaBundle:Participation')->findAll();
        $participationsArray = [];
        foreach ($participations as $participation)
        {
        	$accessKey = "{$participation->getEvent()->getId()}-{$participation->getParticipant()->getId()}";
        	$participationsArray[$accessKey] = $participation;
        }
        return [
        	'participants' => $participants,
        	'events' => $events,
        	'choices' => $choices,
        	'participations' => $participationsArray,
		];
	}
}
