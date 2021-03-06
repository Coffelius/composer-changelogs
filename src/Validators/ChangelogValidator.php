<?php
/**
 * Copyright © Vaimo Group. All rights reserved.
 * See LICENSE_VAIMO.txt for license details.
 */
namespace Vaimo\ComposerChangelogs\Validators;

use Composer\Package\PackageInterface as Package;
use Symfony\Component\Console\Output\OutputInterface as Output;

use Vaimo\ComposerChangelogs\Results\ValidationResult as Result;

class ChangelogValidator
{
    /**
     * @var \Vaimo\ComposerChangelogs\Loaders\ChangelogLoader
     */
    private $changelogLoader;

    /**
     * @var string[]
     */
    private $messageFormats;

    /**
     * @var \Vaimo\ComposerChangelogs\Extractors\ErrorExtractor
     */
    private $errorExtractor;

    /**
     * @param \Vaimo\ComposerChangelogs\Loaders\ChangelogLoader $changelogLoader
     * @param array $messageFormats
     */
    public function __construct(
        \Vaimo\ComposerChangelogs\Loaders\ChangelogLoader $changelogLoader,
        array $messageFormats = array()
    ) {
        $this->changelogLoader = $changelogLoader;
        $this->messageFormats = $messageFormats;

        $this->errorExtractor = new \Vaimo\ComposerChangelogs\Extractors\ErrorExtractor();
    }

    public function validateForPackage(Package $package, $verbosity = Output::VERBOSITY_NORMAL)
    {
        $formats = array_replace(array(
            'failure' => '%s',
            'success' => '%s'
        ), $this->messageFormats);

        try {
            $this->changelogLoader->load($package);
        } catch (\Exception $exception) {
            if ($verbosity > Output::VERBOSITY_VERBOSE) {
                throw $exception;
            }

            $messages = array_merge(
                array(sprintf('The changelog of %s is invalid due to:', $package->getName())),
                $this->errorExtractor->extractMessages($exception)
            );

            return new Result(
                false,
                $this->formatMessages($messages, $formats['failure'])
            );
        }

        return new Result(
            true,
            $this->formatMessages(
                array(sprintf('The changelog of %s is valid', $package->getName())),
                $formats['success']
            )
        );
    }

    private function formatMessages(array $messages, $format)
    {
        return array_map(function ($message) use ($format) {
            return sprintf($format, $message);
        }, $messages);
    }
}
