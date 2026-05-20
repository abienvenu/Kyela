<?php

namespace App\Service;

use App\Entity\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class EventCalendarService
{
	private const GOOGLE_CALENDAR_URL = 'https://calendar.google.com/calendar/render';
	private const TIMEZONE = 'Europe/Paris';
	private const DEFAULT_DURATION_MINUTES = 60;

	public function __construct(
		private UrlGeneratorInterface $urlGenerator,
	) {
	}

	public function isAppleMobileDevice(?Request $request): bool
	{
		if (!$request) {
			return false;
		}

		return (bool) preg_match('/iPhone|iPad|iPod/i', $request->headers->get('User-Agent', ''));
	}

	public function getAddEventUrl(Event $event, ?Request $request): string
	{
		if ($this->isAppleMobileDevice($request)) {
			return $this->urlGenerator->generate('app_event_ics', [
				'url' => $event->getPoll()->getUrl(),
				'id' => $event->getId(),
			], UrlGeneratorInterface::ABSOLUTE_URL);
		}

		return $this->buildGoogleCalendarUrl($event);
	}

	public function buildGoogleCalendarUrl(Event $event): string
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

		return self::GOOGLE_CALENDAR_URL.'?'.http_build_query($params, '', '&', PHP_QUERY_RFC3986);
	}

	public function exportIcs(Event $event): string
	{
		$now = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
		$lines = [
			'BEGIN:VCALENDAR',
			'VERSION:2.0',
			'PRODID:-//Kyela//FR',
			'CALSCALE:GREGORIAN',
			'METHOD:PUBLISH',
			'BEGIN:VEVENT',
			'UID:kyela-event-'.$event->getId().'@kyela',
			'DTSTAMP:'.$now->format('Ymd\THis\Z'),
		];

		$dates = $this->buildDatesParameter($event);
		if ($dates !== null) {
			if ($dates['allDay']) {
				$lines[] = 'DTSTART;VALUE=DATE:'.$dates['start'];
				$lines[] = 'DTEND;VALUE=DATE:'.$dates['end'];
			} else {
				$lines[] = 'DTSTART;TZID='.self::TIMEZONE.':'.$dates['start'];
				$lines[] = 'DTEND;TZID='.self::TIMEZONE.':'.$dates['end'];
			}
		}

		$lines[] = 'SUMMARY:'.$this->escapeIcs($this->buildSummary($event));

		if ($place = $event->getPlace()) {
			$lines[] = 'LOCATION:'.$this->escapeIcs($place);
		}

		$description = $this->buildDescription($event);
		if ($description !== '') {
			$lines[] = 'DESCRIPTION:'.$this->escapeIcs($description);
		}

		$lines[] = 'END:VEVENT';
		$lines[] = 'END:VCALENDAR';

		return $this->foldIcsLines(implode("\r\n", $lines)."\r\n");
	}

	public function suggestIcsFilename(Event $event): string
	{
		$base = $event->getName() ?: $event->getPlace() ?: 'event-'.$event->getId();
		$base = preg_replace('/[^a-zA-Z0-9_-]+/u', '-', $base) ?? 'event';
		$base = trim($base, '-');

		return ($base !== '' ? $base : 'event').'.ics';
	}

	/**
	 * @return array{value: string, allDay: bool, start?: string, end?: string}|null
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
				'start' => $start->format('Ymd\THis'),
				'end' => $end->format('Ymd\THis'),
				'allDay' => false,
			];
		}

		$end = $start->modify('+1 day');

		return [
			'value' => $start->format('Ymd').'/'.$end->format('Ymd'),
			'start' => $start->format('Ymd'),
			'end' => $end->format('Ymd'),
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

	private function escapeIcs(string $value): string
	{
		return str_replace(
			['\\', ';', ',', "\r\n", "\n", "\r"],
			['\\\\', '\;', '\,', '\n', '\n', ''],
			$value,
		);
	}

	private function foldIcsLines(string $content): string
	{
		$folded = [];
		foreach (explode("\r\n", rtrim($content, "\r\n")) as $line) {
			while (strlen($line) > 75) {
				$folded[] = substr($line, 0, 75);
				$line = ' '.substr($line, 75);
			}
			$folded[] = $line;
		}

		return implode("\r\n", $folded)."\r\n";
	}
}
