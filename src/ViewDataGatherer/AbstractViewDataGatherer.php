<?php

declare(strict_types=1);

namespace App\ViewDataGatherer;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Class AbstractViewDataGatherer
 * @package App\ViewDataGatherer
 */
abstract class AbstractViewDataGatherer
{
    /** @var EntityManagerInterface $em */
    protected EntityManagerInterface $em;

    /**
     * AbstractPageQueryHandler constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
}
