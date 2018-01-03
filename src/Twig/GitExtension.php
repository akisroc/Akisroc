<?php

namespace App\Twig;

use App\Utils\Git;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class GitExtension
 * @package App\Twig
 */
class GitExtension extends AbstractExtension
{
    /**
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('app_version', [$this, 'appVersion'])
        ];
    }

    /**
     * Gets application version, based upon Git commit tag
     * (or short commit hash if no tag exists).
     * @return string
     */
    public function appVersion(): string
    {
        return Git::getCurrentVersion();
    }
}