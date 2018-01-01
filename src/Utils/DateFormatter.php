<?php

namespace App\Utils;

/**
 * Class DateFormatter
 * @package App\Utils
 */
class DateFormatter
{
    const DAYS_OF_WEEK = [
        1 => 'lundi',     2 => 'mardi',   3 => 'mercredi',  4 => 'jeudi',
        5 => 'vendredi',  6 => 'samedi',  7 => 'dimanche'
    ];
    const MONTHS = [
        1 => 'janvier',  2 => 'février',  3 => 'mars',       4 => 'avril',     5 => 'mai',        6 => 'juin',
        7 => 'juillet',  8 => 'août',     9 => 'septembre',  10 => 'octobre',  11 => 'novembre',  12 => 'décembre'
    ];

    /**
     * @param \DateTime $date
     * @return string
     */
    static public function format(\DateTime $date): string
    {
        $fragments = explode('_', $date->format('N_j_n_Y_H_i_s'));
        $formattedDate = sprintf('%s %s %s %s à %s:%s:%s',
            self::DAYS_OF_WEEK[$fragments[0]],              // Day of week
            $fragments[1] !== '1' ? $fragments[1] : '1er',  // Day of month
            self::MONTHS[$fragments[2]],                    // Month
            $fragments[3],                                  // Year
            $fragments[4],                                  // Hour
            $fragments[5],                                  // Minute
            $fragments[6]                                   // Second
        );

        return $formattedDate;
    }
}