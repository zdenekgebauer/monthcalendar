<?php

declare(strict_types=1);

namespace ZdenekGebauer\MonthCalendar;

class CalendarTest extends \Codeception\Test\Unit
{

    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function testSunday(): void
    {
        $obj = new Calendar(new \DateTime('2019-09-01'));
        $output = $obj->render();

        $this->tester->assertStringContainsString('<table>', $output);
        $this->tester->assertStringContainsString('<caption>9/2019</caption>', $output);
        $this->tester->assertStringContainsString('<thead><tr><th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th></tr></thead>', $output);
        $this->tester->assertStringContainsString('<tr><td data-date="2019-09-29">29</td><td data-date="2019-09-30">30</td><td></td><td></td><td></td><td></td><td></td></tr></tbody>', $output);
        $this->tester->assertStringNotContainsString('<tfoot>', $output);
        $this->tester->assertStringContainsString('</table>', $output);
    }

    public function testMonday(): void
    {
        $obj = new Calendar(new \DateTimeImmutable('2019-10-01'));
        $obj->firstDayOfWeek(1);
        $output = $obj->render();

        $this->tester->assertStringContainsString('<caption>10/2019</caption>', $output);
        $this->tester->assertStringContainsString('<thead><tr><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th><th>Sun</th></tr></thead>', $output);
        $this->tester->assertStringContainsString('<tbody><tr><td></td><td data-date="2019-10-01">1</td><td data-date="2019-10-02">2</td><td data-date="2019-10-03">3</td><td data-date="2019-10-04">4</td><td data-date="2019-10-05">5</td><td data-date="2019-10-06">6</td></tr>', $output);
        $this->tester->assertStringContainsString('<tr><td data-date="2019-10-28">28</td><td data-date="2019-10-29">29</td><td data-date="2019-10-30">30</td><td data-date="2019-10-31">31</td><td></td><td></td><td></td></tr></tbody>', $output);
        $this->tester->assertStringNotContainsString('<tfoot>', $output);
    }

    public function testFirstDayOfWeekWithInvalidDate(): void
    {

        $this->tester->expectThrowable(new \InvalidArgumentException('expect parameter in range 0 - 6 (Sunday - Saturday)'), static function () {
            $obj = new Calendar(new \DateTime());
            $obj->firstDayOfWeek(7);
        });
    }

}
