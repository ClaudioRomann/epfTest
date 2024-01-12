<?php

namespace app\data\extractors;

use epf\epfDateTime;
use epf\epfString;

class HeaderDataExtractor extends AbstractDataExtractor
{

    protected const HEADER_TYPE = 'Header';
    protected const NODE_TYPES = [
        self::HEADER_TYPE => 'header'
    ];

    private $extractorName = 'header';
    private $autoApprove   = '6 years';

    protected function createAttributesComponents(): void
    {
        $nodeType = $this->extractorName;

        $data = new epfString($this->data[$nodeType][0]);

        $pairs = $data->split(';');

        $array = [];

        foreach($pairs as $pair) {

            [$key, $value] = (new epfString($pair->__toString()))->split('=');

            $key->toLower();

            $key = $key->__toString();

            if (!isset($value)) {
                continue;
            }

            if ($key !== 'author') {
                $value->toLower();
            }

            $value = $value->__toString();

            if ($key === 'timestamp') {

                $epfDateTime = new epfDateTime($value);
                $epfDateTime->format('Y-m-d H:i:s');

                $array[$key] = $epfDateTime->format('Y-m-d H:i:s');

                continue;
            }

            if ($key === 'autoapprove') {

                $timestamp = new epfDateTime($array['timestamp']);

                $array['approved'] = $timestamp->isOlderThan($this->autoApprove) ? 'no' : 'yes';

                continue;
            }

            $array[$key] = $value;
        }

        if (!isset($array['signed']))
        {
            $array['signed'] = 'no';
        }

        $this->data[$nodeType] = $array;
    }

    public function extractorName(): string
    {
        return $this->extractorName;
    }

    /**
     * return ['Author=Bill Gates, Timestamp=853082238, signed=Yes, AutoApprove=12 hours]
     */
    public function extractData(): array
    {
        return $this->data[$this->extractorName];
    }
}