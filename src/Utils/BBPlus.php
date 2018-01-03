<?php

namespace App\Utils;

use App\Kernel;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class BBPlus
 * @package App\Utils
 */
class BBPlus
{
    /** @var Kernel $kernel */
    protected $kernel;

    /**
     * BBPlus constructor.
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * Converts BBCode to HTML.
     * @param string $input
     * @return string
     */
    public function parse(string $input): string
    {
        $dir = realpath(
            $this->kernel->getProjectDir() . DIRECTORY_SEPARATOR . 'submodules' . DIRECTORY_SEPARATOR . 'bbplus'
        );
        $bin = $dir . DIRECTORY_SEPARATOR . 'bbplus';

        return shell_exec("$bin parse \"$input\"");
    }
}