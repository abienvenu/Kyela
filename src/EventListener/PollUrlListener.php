<?php

namespace App\EventListener;

use App\Entity\Poll;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class PollUrlListener implements EventSubscriberInterface
{
	public function __construct(private EntityManagerInterface $em)
	{
	}

	/**
	 * Stores the pollUrl in session
	 * If the pollUrl matches an existing poll, set this poll as a request attribute
	 */
	public function onKernelController(ControllerEvent $event): void
	{
		$request = $event->getRequest();

		$pollUrl = $request->get('pollUrl');
		if ($pollUrl) {
			$request->getSession()->set('pollUrl', $pollUrl);
		} else {
			$pollUrl = $request->getSession()->get('pollUrl');
		}
		if ($pollUrl) {
			$poll = $this->em->getRepository(Poll::class)->findOneBy(['url' => $pollUrl]);
			if ($poll) {
				$request->attributes->set('poll', $poll);
			} else {
				$request->getSession()->remove('pollUrl');
				throw new NotFoundHttpException('Poll object not found.');
			}
		}
	}

	public static function getSubscribedEvents(): array
	{
		return [
			KernelEvents::CONTROLLER => [['onKernelController', 20]],
		];
	}
}
