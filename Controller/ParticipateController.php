<?php

namespace Abienvenu\KyelaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class ParticipateController extends Controller
{
	/**
	 * @Route("/", name="index")
	 * @Template()
	 */
	public function indexAction(Request $request)
	{
		return ['info' => 'glop'];
	}
}