<?php

namespace App\Twig;

use App\Entity\Event;
use App\Service\GoogleCalendarUrlBuilder;
use Twig\Attribute\AsTwigFunction;

final class CalendarExtension
{
	public function __construct(
		private GoogleCalendarUrlBuilder $googleCalendarUrlBuilder,
	) {
	}

	#[AsTwigFunction('google_calendar_url')]
	public function googleCalendarUrl(Event $event): string
	{
		return $this->googleCalendarUrlBuilder->buildAddEventUrl($event);
	}
}
