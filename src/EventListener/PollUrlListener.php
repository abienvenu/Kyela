<?php
namespace App\EventListener;

use App\Controller\PollSetterController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class PollUrlListener implements EventSubscriberInterface
{
    public function onKernelController(ControllerEvent $event): void
    {
        $controller = $event->getController();
        if (!is_array($controller)) {
            return;
        }

        if ($controller[0] instanceof PollSetterController) {
            $controller[0]->setPollFromRequest($event->getRequest());
        }
    }

	public static function getSubscribedEvents(): array
	{
		return [
			KernelEvents::CONTROLLER => [['onKernelController', 20]],
		];
	}
}
