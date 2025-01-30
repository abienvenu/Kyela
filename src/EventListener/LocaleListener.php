<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleListener implements EventSubscriberInterface
{
	public function onKernelRequest(RequestEvent $event): void
	{
		$request = $event->getRequest();
		$locale = $request->getPreferredLanguage(['fr', 'en', 'de']);
		$request->setLocale($locale);
	}

	public static function getSubscribedEvents(): array
	{
		return [
			KernelEvents::REQUEST => [['onKernelRequest', 20]],
		];
	}
}
