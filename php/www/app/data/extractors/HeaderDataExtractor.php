<?php

namespace app\data\extractors;

use app\adapter\epfStringParser;
use epf\epfDateTime;
use epf\epfString;

class HeaderDataExtractor extends AbstractDataExtractor
{
    protected const TYPE = 'Header';

    private $extractorName = 'header';
    private $autoApprove   = '6 years';

    protected function createAttributesComponents(): void
    {
        $extractorName = $this->extractorName();

        $objectParserWriter = new epfStringParser($this->data->$extractorName->attributes[0]);

        $attributes = $objectParserWriter->toArray();

        $attributesArray = $this->applyBusinessRuleToAttributes($attributes);

        $this->data->$extractorName->attributes = (object) $attributesArray;
    }

    public function extractorName(): string
    {
        return $this->extractorName;
    }

    public function extractData(): object
    {
        return $this->data;
    }

    private function applyBusinessRuleToAttributes(array $attributes): array
    {
        $array = [];
        foreach($attributes as $key => $value) {

            if (!isset($value)) {
                continue;
            }

            $epfStringKey = new epfString($key);
            $epfStringKey->toLower();
            $newKey = $epfStringKey->__toString();

            $epfStringValue = new epfString($value);

            if ($newKey !== 'author') {
                $epfStringValue->toLower();
            }

            $newValue = $epfStringValue->__toString();

            if ($newKey === 'timestamp') {

                $epfDateTime = new epfDateTime($newValue);
                $epfDateTime->format('Y-m-d H:i:s');

                $array[$newKey] = $epfDateTime->format('Y-m-d H:i:s');

                continue;
            }

            if ($newKey === 'autoapprove') {

                $timestamp = new epfDateTime($array['timestamp']);

                $array['approved'] = $timestamp->isOlderThan($this->autoApprove) ? 'no' : 'yes';

                continue;
            }

            $array[$newKey] = $newValue;
        }

        if (!isset($array['signed']))
        {
            $array['signed'] = 'no';
        }

        return $array;
    }
}