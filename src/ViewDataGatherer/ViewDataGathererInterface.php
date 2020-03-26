<?php

/**
 * Not used for now.
 */

declare(strict_types=1);

namespace App\ViewDataGatherer;

/**
 * Interface ViewDataGathererInterface
 *
 * The role of a PageDataGatherer is to fetch and organize data for
 * the specific needs of a specific page.
 *
 * While Repositories are Entity-centric, PageDataGatherers build
 * results with user-end template in mind.
 *
 * This is the place for pragmatic tricky-oily SQL optimizations, avoiding
 * for example Doctrine's lazy loading problematics (400 requests on a single
 * page leading to slow user navigation).
 *
 * @package App\ViewDataGatherer
 */
interface ViewDataGathererInterface
{
    /**
     * Gets array of data needed for handled page.
     * This is generally expected to be passed to the template.
     *
     * Example:
     *     [
     *         'title' => 'Hello Planet',
     *         'user' => [UserObject]
     *     ]
     *
     * @return array
     */
    public function gatherData(): array;
}
