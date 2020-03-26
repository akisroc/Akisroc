<?php

declare(strict_types=1);

namespace App\Entity;

/**
 * Interface EntityInterface
 * @package App\Entity
 */
interface EntityInterface
{
    /**
     * @return string|null
     */
    public function getId(): ?string;

    /**
     * @return \DateTime|null
     */
    public function getCreatedAt(): ?\DateTime;

    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt(): ?\DateTime;
}
