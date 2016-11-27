<?php
/*
 * Copyright 2016 Arnaud Bienvenu
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

use Abienvenu\KyelaBundle\Entity\Poll;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class PollSetterController extends Controller
{
	/** @var Poll $poll */
	protected $poll;

	/**
	 * Set poll from Url or session
	 */
	public function setPollFromRequest(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$pollUrl = $request->get('pollUrl');
		if ($pollUrl) {
			$request->getSession()->set('pollUrl', $pollUrl);
		}
		else {
			$pollUrl = $request->getSession()->get('pollUrl');
		}
		if ($pollUrl) {
			$repository = $em->getRepository('KyelaBundle:Poll');
			$this->poll = $repository->findOneByUrl($pollUrl);
			if (!$this->poll)
			{
				$this->unsetPoll($request);
				throw new NotFoundHttpException('Poll object not found.');
			}
		}
	}

	public function unsetPoll(Request $request)
	{
		$this->poll = null;
		$request->getSession()->remove('pollUrl');
	}
}
