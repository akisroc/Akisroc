<?php

namespace App\Utils;

/**
 * Class FixturesService
 * @package App\Utils
 */
class FixturesService
{
    /**
     * Filters given references to return only objects from given class.
     *
     * @param array $references
     * @param string $class
     * @return object[]
     */
    static public function filterReferences(array $references, string $class): array
    {
        if (!class_exists($class)) {
            throw new \UnexpectedValueException("Class \"$class\" not found.");
        }

        return array_filter($references, function(object $entity) use (&$class): bool {
            return get_class($entity) === $class;
        });
    }
}