<?php
/**
 * Copyright © Vaimo Group. All rights reserved.
 * See LICENSE_VAIMO.txt for license details.
 */
namespace Vaimo\ComposerChangelogs\Decoders;

class JsonDecoder
{
    public function decode($json)
    {
        $decodedJson = json_decode($json, true);
        $errorCode = json_last_error();

        if ($errorCode != 0) {
            $errorMessage = 'Unknown error';

            $jsonErrorMessages = array(
                JSON_ERROR_DEPTH => 'Maximum stack depth exceeded',
                JSON_ERROR_STATE_MISMATCH => 'Underflow or the modes mismatch',
                JSON_ERROR_CTRL_CHAR => 'Unexpected control character found',
                JSON_ERROR_SYNTAX => 'Syntax error, malformed JSON',
                JSON_ERROR_UTF8 => 'Malformed UTF-8 characters, possibly incorrectly encoded'
            );

            if (isset($jsonErrorMessages[$errorCode])) {
                $errorMessage = $jsonErrorMessages[$errorCode];
            }

            throw new \Vaimo\ComposerChangelogs\Exceptions\DecoderException(
                sprintf('Error encountered while decoding JSON: - %s', $errorMessage)
            );
        }

        return $decodedJson;
    }
}
