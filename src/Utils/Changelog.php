<?php

namespace App\Utils;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpKernel\KernelInterface;

class Changelog
{
    protected const FILE_NAME = 'CHANGELOG';
    protected const FILE_EXTENSION = 'md';

    /** @var KernelInterface $kernel */
    protected $kernel;

    /** @var string $changelog */
    protected $changelog;

    /**
     * Changelog constructor.
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @param string|null $langCode
     * @return string
     */
    public function parseRootChangelog(string $langCode = null): string
    {
        return $this->parseChangelog(null, $langCode);
    }

    /**
     * If Changelog is provided (content, not file), returns parsed given Changelog.
     * Else, returns parsed root Changelog (CHANGELOG.md).
     *
     * @param string|null $changelog
     * @param string|null $langCode
     * @return string
     */
    public function parseChangelog(string $changelog = null, string $langCode = null): string
    {
        $parser = new \Parsedown();
        if ($changelog) {
            return $parser->parse($changelog);
        }

        $filePath = (function() use (&$langCode): string {
            $dir = $this->kernel->getRootDir();
            $s = DIRECTORY_SEPARATOR;
            if (!$langCode) {
                return realpath(
                    $dir . $s . '..' . $s . self::FILE_NAME . '.' . self::FILE_EXTENSION
                );
            }
            return realpath(
                $dir . $s . '..' . $s . self::FILE_NAME . '.' . $langCode . '.' . self::FILE_EXTENSION
            );
        })();

        if (!file_exists($filePath)) {
            if ($langCode) {
                return $this->parseChangelog(null, null);
            }
            throw new FileNotFoundException("Could not find file \"$filePath\".");
        }

        return $parser->parse(file_get_contents($filePath));
    }
}