# MonthCalendar
Base class for rendering calendar from PHP scripts.    

![](https://github.com/zdenekgebauer/monthcalendar/workflows/build/badge.svg)

## Installation 
`composer require zdenekgebauer/month-calendar`

## Usage
```php
$date = new DateTime();
$calendar = new Calendar($date);
$calendar->firstDayOfWeek(0); // 0-6, 0=Sunday
$calendar->showOtherMonths = true; // show days from previous and next month 
echo $calendar->render(); 
```

### Customization
```php
class CustomCalendar extends ZdenekGebauer\MonthCalendar\Calendar
{
    /**
     * @return array<string>
     */
    protected function weekdays(): array
    {
        // own weekdays names from Sunday
        return ['Ne', 'Po', 'Út', 'St', 'Čt', 'Pá', 'So'];
    }

    protected function renderCaption(): string
    {
        // own table caption
        return '<caption>' . $this->date->format('m/Y') . '</caption>';
    }

    protected function renderDay(DateTimeInterface $currentDate, bool $isOtherMonth = false): string
    {
        // own content of day, ie. info "occupied"  
        $day = (int)$currentDate->format('j');        
        $cssClass = '';
        $content =  '';
        if ($day === 10 || $day === 15) {
            $cssClass = 'occupied';
            $content =  'occupied';                   
        }
        if ($isOtherMonth) {
            $cssClass .= ' other-month';
        }       
        
        return '<td data-date="' . $currentDate->format('Y-m-d') . '" class=' . $cssClass . '">'
            . $currentDate->format('j')
            . '<hr>' . ($content)
            . '</td>';
    }

    protected function renderFooter(): string
    {
        // own table footer
        return '<tfoot><tr><td colspan="7">occupied days: 2</td></tr></tfoot>';
    }
}
```


