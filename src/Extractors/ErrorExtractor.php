<?php
/**
 * Copyright © Vaimo Group. All rights reserved.
 * See LICENSE_VAIMO.txt for license details.
 */
namespace Vaimo\ComposerChangelogs\Extractors;

class ErrorExtractor
{
    public function extractMessages(\Exception $exception)
    {
        $messages = array();

        do {
            $messages[] = $exception->getMessage();
        } while ($exception = $exception->getPrevious());

        array_walk($messages, function (&$message, $index) {
            $message = sprintf('#%s %s', $index, $message);
        });

        return $messages;
    }
}
