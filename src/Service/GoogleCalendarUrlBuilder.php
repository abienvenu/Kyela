<?php

namespace App\Service;

use App\Entity\Event;

class GoogleCalendarUrlBuilder
{
	private const CALENDAR_URL = 'https://calendar.google.com/calendar/render';
	private const TIMEZONE = 'Europe/Paris';
	private const DEFAULT_DURATION_MINUTES = 60;

	public function buildAddEventUrl(Event $event): string
	{
		$params = [
			'action' => 'TEMPLATE',
			'text' => $this->buildSummary($event),
		];

		$dates = $this->buildDatesParameter($event);
		if ($dates !== null) {
			$params['dates'] = $dates['value'];
			if (!$dates['allDay']) {
				$params['ctz'] = self::TIMEZONE;
			}
		}

		if ($place = $event->getPlace()) {
			$params['location'] = $place;
		}

		$description = $this->buildDescription($event);
		if ($description !== '') {
			$params['details'] = $description;
		}

		return self::CALENDAR_URL.'?'.http_build_query($params, '', '&', PHP_QUERY_RFC3986);
	}

	/**
	 * @return array{value: string, allDay: bool}|null
	 */
	private function buildDatesParameter(Event $event): ?array
	{
		$date = $event->getDate();
		if (!$date) {
			return null;
		}

		$tz = new \DateTimeZone(self::TIMEZONE);
		$start = new \DateTimeImmutable($date->format('Y-m-d'), $tz);

		$time = $event->getTime();
		if ($time) {
			$start = $start->setTime(
				(int) $time->format('H'),
				(int) $time->format('i'),
				0,
			);
			$end = $start->modify('+'.self::DEFAULT_DURATION_MINUTES.' minutes');

			return [
				'value' => $start->format('Ymd\THis').'/'.$end->format('Ymd\THis'),
				'allDay' => false,
			];
		}

		$end = $start->modify('+1 day');

		return [
			'value' => $start->format('Ymd').'/'.$end->format('Ymd'),
			'allDay' => true,
		];
	}

	private function buildSummary(Event $event): string
	{
		$parts = array_filter([$event->getName(), $event->getPlace()]);

		return $parts !== []
			? implode(' — ', $parts)
			: ($event->getPoll()?->getTitle() ?? 'Event');
	}

	private function buildDescription(Event $event): string
	{
		$parts = [];
		if ($event->getSubtitle()) {
			$parts[] = $event->getSubtitle();
		}
		if ($poll = $event->getPoll()) {
			$parts[] = $poll->getTitle();
		}

		return implode("\n", $parts);
	}
}
