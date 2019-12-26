<?php

declare(strict_types=1);

namespace ZdenekGebauer\MonthCalendar;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use InvalidArgumentException;

use function array_slice;

class Calendar
{

    /**
     * @var DateTimeInterface
     */
    protected $date;

    /**
     * @var int
     */
    protected $firstDayOfWeek = 0;

    public function __construct(DateTimeInterface $date)
    {
        $this->date = $date;
    }

    public function render(): string
    {
        return '<table>'
            . $this->renderCaption()
            . $this->renderWeekDays()
            . $this->renderFooter()
            . $this->renderDays()
            . '</table>';
    }

    protected function renderDays(): string
    {
        $daysInMonth = $this->date->format('t');

        /** @var DateTime $currentDate */
        $currentDate = $this->date instanceof DateTimeImmutable ?
            DateTime::createFromImmutable($this->date) : clone $this->date;
        $year = (int)$this->date->format('Y');
        $month = (int)$this->date->format('n');
        $currentDate->setDate($year, $month, 1);

        $blankDays = $this->calculateBlankDays($currentDate);
        $html = '<tbody><tr>' .  str_repeat('<td></td>', $blankDays);

        $daysCounter = $blankDays;
        /** @var DateTimeInterface $currentDate */
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $currentDate->setDate($year, $month, $day);
            $html .= $this->renderDay($currentDate);

            $daysCounter++;
            if ($daysCounter === 7) {
                $html .= '</tr>' . (($day + 1) <= $daysInMonth ? '<tr>' : '');
                $daysCounter = 0;
            }
        }

        $blankDays = $daysCounter < 7 && $daysCounter > 0 ? 7 - $daysCounter : 0;
        $html .= str_repeat('<td></td>', $blankDays);
        return $html . '</tr></tbody>';
    }

    public function firstDayOfWeek(int $firstDayOfWeek): void
    {
        if ($firstDayOfWeek < 0 || $firstDayOfWeek > 6) {
            throw new InvalidArgumentException('expect parameter in range 0 - 6 (Sunday - Saturday)');
        }
        $this->firstDayOfWeek = $firstDayOfWeek;
    }

    private function calculateBlankDays(DateTimeInterface $firstDayOfMonth): int
    {
        $weekDayOfFirstDay = (int)$firstDayOfMonth->format('w');
        $blankDays = $weekDayOfFirstDay - $this->firstDayOfWeek;
        return $blankDays < 0 ? $blankDays = 7 - abs($blankDays) : $blankDays;
    }

    protected function renderWeekDays(): string
    {
        $columns = $this->columnNames();
        return '<thead><tr><th>' . implode('</th><th>', $columns) . '</th></tr></thead>';
    }

    protected function renderDay(DateTimeInterface $currentDate): string
    {
        return '<td data-date="' . $currentDate->format('Y-m-d') . '">'
            . $currentDate->format('j') . '</td>';
    }

    /**
     * headers of columns, starting from Sunday
     *
     * @return array<string>
     */
    protected function weekdays(): array
    {
        return ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    }

    /**
     * @return array<string>
     */
    protected function columnNames(): array
    {
        $columns = array_slice($this->weekdays(), $this->firstDayOfWeek);
        $rest = array_slice($this->weekdays(), 0, $this->firstDayOfWeek);
        return array_merge($columns, $rest);
    }

    protected function renderCaption(): string
    {
        return '<caption>' . $this->date->format('n/Y') . '</caption>';
    }

    protected function renderFooter(): string
    {
        return '';
    }
}
