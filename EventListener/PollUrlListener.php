<?php
namespace Abienvenu\KyelaBundle\EventListener;

use Abienvenu\KyelaBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class PollUrlListener
{
	public function onKernelController(FilterControllerEvent $event)
	{
		$controller = $event->getController();
		if (!is_array($controller)) {
            return;
        }

        if ($controller[0] instanceof AbstractController) {
        	$controller[0]->setPollFromRequest($event->getRequest());
        }
	}
}