<?php

namespace App\Twig;

use App\Utils\DateFormatter;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class DateExtension
 * @package App\Twig
 */
class DateExtension extends AbstractExtension
{
    /**
     * @return array
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('date_format', [$this, 'dateFormat'])
        ];
    }

    /**
     * @param \DateTime $date
     * @return string
     */
    public function dateFormat(\DateTime $date): string
    {
        return DateFormatter::format($date);
    }
}