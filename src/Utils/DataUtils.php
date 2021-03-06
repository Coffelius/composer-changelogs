<?php
/**
 * Copyright © Vaimo Group. All rights reserved.
 * See LICENSE_VAIMO.txt for license details.
 */
namespace Vaimo\ComposerChangelogs\Utils;

class DataUtils
{
    public function removeKeysByPrefix(array $data, $prefix)
    {
        return array_intersect_key(
            $data,
            array_flip(
                array_filter(
                    array_keys($data),
                    function ($key) use ($prefix) {
                        return strpos($key, $prefix) !== 0;
                    }
                )
            )
        );
    }

    public function walkArrayNodes(array $list, \Closure $callback)
    {
        $list = $callback($list);

        foreach ($list as $key => $value) {
            if (!is_array($value)) {
                continue;
            }

            $list[$key] = $this->walkArrayNodes($value, $callback);
        }

        return $list;
    }

    public function renderConstant(array $data, array $keys, $constant, $default = '')
    {
        if (!array_intersect_key($data, array_flip($keys))) {
            return $default;
        }

        return $constant;
    }

    public function renderValue(array $data, $key, $format, $default = '')
    {
        if (!isset($data[$key])) {
            return $default;
        }

        return sprintf($format, $data[$key]);
    }
    
    public function extractValue(array $data, $key, $default = '')
    {
        if (!isset($data[$key])) {
            return $default;
        }
        
        return $data[$key];
    }
    
    public function assureArrayValue($value)
    {
        if (is_array($value)) {
            return $value;
        }

        return array($value);
    }
}
