<?php

declare(strict_types=1);

namespace ZdenekGebauer\MonthCalendar;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;

use function array_slice;

class Calendar
{

    protected DateTimeInterface $date;

    protected int $firstDayOfWeek = 0;

    /**
     * show days from previous and next month
     */
    public bool $showOtherMonths = false;

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

        $html = '<tbody><tr>';

        $blankDays = $this->calculateBlankDays($currentDate);
        if ($blankDays > 0) {
            if ($this->showOtherMonths) {
                $prevMonth = new DateTime();
                $prevMonth->setDate($year, $month - 1, 1);
                $daysInPrevMonth = (int)$prevMonth->format('t');
                //$start = (int)$prevMonth->format('t') - - $blankDays + 1;
                //$daysInPrevMonth = $prevMonth->format('t');
                for ($day = $daysInPrevMonth - $blankDays + 1; $day <= $daysInPrevMonth; $day++) {
                    $currentDate->setDate((int)$prevMonth->format('Y'), (int)$prevMonth->format('n'), $day);
                    $html .= $this->renderDay($currentDate, true);
                }
            } else {
                $html .= str_repeat('<td></td>', $blankDays);
            }
        }

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
        if ($blankDays > 0) {
            if ($this->showOtherMonths) {
                $nextMonth = new DateTime();
                $nextMonth->setDate($year, $month + 1, 1);
                for ($day = 1; $day <= $blankDays; $day++) {
                    $currentDate->setDate((int)$nextMonth->format('Y'), (int)$nextMonth->format('n'), $day);
                    $html .= $this->renderDay($currentDate, true);
                }
            } else {
                $html .= str_repeat('<td></td>', $blankDays);
            }
        }
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

    #[Pure]
    protected function renderWeekDays(): string
    {
        $columns = $this->columnNames();
        return '<thead><tr><th>' . implode('</th><th>', $columns) . '</th></tr></thead>';
    }

    protected function renderDay(DateTimeInterface $currentDate, bool $isOtherMonth = false): string
    {
        return '<td data-date="' . $currentDate->format('Y-m-d') . '"'
            . ($isOtherMonth ? ' data-other-month' : '') . '>' . $currentDate->format('j') . '</td>';
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
    #[Pure]
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
