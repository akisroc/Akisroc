<?php

namespace App\Tests\Utils;

use App\Kernel;
use App\Utils\BBPlus;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class BBPlusTest
 * @package App\Tests\Utils
 */
class BBPlusTest extends WebTestCase
{
    /** @var BBPlus $kernel */
    private $bbplus;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->bbplus = new BBPlus($kernel);
    }

    /**
     * Must convert BBCode to valid html.
     * @dataProvider parseCaseProvider
     * @param string $input
     * @param string $expected
     */
    public function testParse(string $input, string $expected): void
    {
        $output = $this->bbplus->parse($input);
        $this->assertEquals($expected, $output);
    }

    /**
     * @return \Generator
     */
    public function parseCaseProvider(): \Generator
    {
        yield ['[b]Hello[/b]', '<strong class="bb bb-bold">Hello</strong>'];
        yield ['[i]Hello[/i]', '<em class="bb bb-italic">Hello</em>'];
        yield ['[url]http://www.startpage.com[/url]', '<a href="http://www.startpage.com" class="bb bb-link">http://www.startpage.com</a>'];
        yield ['[url=\"http://www.startpage.com\"]Startpage[/url]', '<a href="http://www.startpage.com" class="bb bb-labelled-link">Startpage</a>'];
    }
}