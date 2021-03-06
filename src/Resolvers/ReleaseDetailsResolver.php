<?php
/**
 * Copyright © Vaimo Group. All rights reserved.
 * See LICENSE_VAIMO.txt for license details.
 */
namespace Vaimo\ComposerChangelogs\Resolvers;

class ReleaseDetailsResolver
{
    /**
     * @var \Vaimo\ComposerChangelogs\Utils\DataUtils
     */
    private $dataUtils;

    /**
     * @var string[]
     */
    private $reservedKeys = array(
        'overview',
        'version',
        'branch',
        'summary',
        'overview-reduced',
        'link',
        'diff',
        'date',
        'time'
    );
    
    public function __construct()
    {
        $this->dataUtils = new \Vaimo\ComposerChangelogs\Utils\DataUtils();
    }

    public function resolveOverview(array $release)
    {
        $overviewLines = isset($release['overview'])
            ? $this->dataUtils->assureArrayValue($release['overview'])
            : array();

        $reducedLines = explode(PHP_EOL, implode(
            ' ',
            array_map(function ($line) {
                return !trim($line) ? PHP_EOL . PHP_EOL : $line;
            }, $overviewLines)
        ));

        $reducedLines = array_map('trim', $reducedLines);
        
        return array(
            'version' => isset($release['version']) ? $release['version'] : '',
            'overview' => $overviewLines,
            'summary' => isset($release['summary']) ? $release['summary'] : '',
            'overview-reduced' => $reducedLines,
        );
    }
    
    public function resolveChangeGroups(array $release)
    {
        return array_diff_key(
            $release,
            array_fill_keys($this->reservedKeys, true)
        );
    }

    public function resolveBranch(array $release)
    {
        if (!isset($release['branch'])) {
            return '';
        }

        return $release['branch'];
    }
}
