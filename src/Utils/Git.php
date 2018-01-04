<?php

namespace App\Utils;

/**
 * Class Git
 * @package App\Utils
 */
class Git
{
    /**
     * @return null|string
     */
    static public function getCurrentVersion(): string
    {
        $output = `git describe --abbrev=0 --tags` ?: `git rev-parse --short HEAD`;
        return trim($output);
    }
}