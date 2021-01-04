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

    public function testOtherMonths(): void
    {
        $obj = new Calendar(new \DateTimeImmutable('2020-12-10'));
        $obj->showOtherMonths = true;
        $output = $obj->render();

        $this->tester->assertStringContainsString('<tbody><tr><td data-date="2020-11-29" data-other-month>29</td><td data-date="2020-11-30" data-other-month>30</td><td data-date="2020-12-01">1</td><td data-date="2020-12-02">2</td><td data-date="2020-12-03">3</td><td data-date="2020-12-04">4</td><td data-date="2020-12-05">5</td></tr>', $output);
        $this->tester->assertStringContainsString('<tr><td data-date="2020-12-27">27</td><td data-date="2020-12-28">28</td><td data-date="2020-12-29">29</td><td data-date="2020-12-30">30</td><td data-date="2020-12-31">31</td><td data-date="2021-01-01" data-other-month>1</td><td data-date="2021-01-02" data-other-month>2</td></tr></tbody>', $output);
    }
}
