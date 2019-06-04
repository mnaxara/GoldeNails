<?php

namespace App\Test\Service;


use App\Service\Month;
use PHPUnit\Framework\TestCase;

class MonthTest extends TestCase
{
    public function getMonthData()
    {
        $month = new Month();
        $testDate = new \DateTime('now');
        $monthData = $month->getMonthData($testDate);
        $this->assertEquals(42, $monthData);
    }
}
