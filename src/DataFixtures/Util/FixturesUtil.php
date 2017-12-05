<?php

namespace App\DataFixtures\Util;

/**
 * Class FixturesUtil
 * @package App\DataFixtures\Util
 */
class FixturesUtil
{
    /**
     * @param array $references
     * @param string $class
     * @return array
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