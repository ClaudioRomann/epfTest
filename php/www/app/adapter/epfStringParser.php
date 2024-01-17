<?php

namespace app\adapter;

use epf\epfConfigParser;

class epfStringParser extends epfConfigParser
{
    public function toArray():array
    {
        $keys  = $this->parsedConfigKeys();
        $array = [];

        foreach ($keys as $key) {

            if (!$key) {
                continue;
            }

            $value = $this->getEntry($key);
            $array[$key] = $value;
        }

        return $array;
    }
}