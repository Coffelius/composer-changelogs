<?php
/**
 * Copyright © Vaimo Group. All rights reserved.
 * See LICENSE_VAIMO.txt for license details.
 */
namespace Vaimo\ComposerChangelogs\Utils;

class FileSystemUtils
{
    public function collectPathsRecursively($rootPath, $pattern)
    {
        if (!is_dir($rootPath)) {
            return array();
        }
        
        $directoryIterator = new \RecursiveDirectoryIterator($rootPath);
        $recursiveIterator = new \RecursiveIteratorIterator($directoryIterator);

        $filesIterator = new \RegexIterator(
            $recursiveIterator,
            $pattern,
            \RecursiveRegexIterator::GET_MATCH
        );

        $files = array();

        foreach ($filesIterator as $info) {
            $files[] = reset($info);
        }

        return $files;
    }
}
