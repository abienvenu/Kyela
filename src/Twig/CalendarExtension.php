<?php

namespace App\Twig;

use App\Entity\Event;
use App\Service\EventCalendarService;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Attribute\AsTwigFunction;

final class CalendarExtension
{
	public function __construct(
		private EventCalendarService $eventCalendarService,
		private RequestStack $requestStack,
	) {
	}

	#[AsTwigFunction('event_calendar_url')]
	public function eventCalendarUrl(Event $event): string
	{
		return $this->eventCalendarService->getAddEventUrl(
			$event,
			$this->requestStack->getCurrentRequest(),
		);
	}

	#[AsTwigFunction('is_apple_mobile_device')]
	public function isAppleMobileDevice(): bool
	{
		return $this->eventCalendarService->isAppleMobileDevice(
			$this->requestStack->getCurrentRequest(),
		);
	}
}
