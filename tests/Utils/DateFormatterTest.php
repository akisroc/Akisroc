<?php

namespace App\Tests\Utils;

use App\Utils\DateFormatter;
use PHPUnit\Framework\TestCase;

/**
 * Class DateFormatterTest
 * @package App\Tests\Utils
 */
class DateFormatterTest extends TestCase
{
    /**
     * Must correctly format date
     * @dataProvider parseCaseProvider
     * @param string $input
     * @param string $expected
     * @param string|null $format
     */
    public function testFormat(string $input, string $expected, string $format = null): void
    {
        $date = new \DateTime($input);
        $res = DateFormatter::format($date, $format);
        $this->assertEquals($expected, $res);
    }

    /**
     * @return \Generator
     */
    public function parseCaseProvider(): \Generator
    {
        yield ['2017/12/31 23:23:10', 'dimanche 31 décembre 2017 à 23:23:10'];
        yield ['2018/01/01 08:00:09', 'lundi 1er janvier 2018 à 08:00:09'];
        yield ['2018/01/02 00:01:22', '20180102 000122', 'Ymd His'];
    }
}