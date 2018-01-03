<?php

namespace App\Tests\Utils;

use App\Utils\Git;
use PHPUnit\Framework\TestCase;

/**
 * Class GitTest
 * @package App\Tests\Utils
 */
class GitTest extends TestCase
{
    /**
     * Must be a valid commit short hash or a valid tag vX.x.x
     */
    public function testGetVersion(): void
    {
        $version = Git::getCurrentVersion();
        $this->assertRegExp("~^([0-9a-f]{7})$|^([vV]?(?:[0-9]+\.[0-9]+){1}(?:\.[0-9]+)?)$~", $version);
    }
}