<?php

namespace app\data\extractors;

use epf\epfString;
use stdClass;

abstract class AbstractDataExtractor
{
    protected $inputTxt;
    protected $data;

    /**
     * @param $inputTxt string plain text
     */
    public function __construct(string $inputTxt)
    {
        $this->inputTxt = $inputTxt;
    }

    abstract public function extractData();
    abstract public function extractorName(): string;

    protected function createNodeComponents(): void
    {
        $epfStringInputTxt = new epfString($this->inputTxt);
        $array = $epfStringInputTxt->split("\n");
        foreach ($array as $element) {
            $epfStringElement = new epfString($element);

            if (!$epfStringElement->contains(static::TYPE)) {
                continue;
            }

            $arrayElement  = $epfStringElement->split('|');
            $attributes    = $arrayElement[1]->__toString();
            $extractorName = $this->extractorName();

            $existAlreadyAttributes = $this->data->$extractorName->attributes;
            if ($existAlreadyAttributes) {
                $this->data->$extractorName->attributes[] = $attributes;

                continue;
            }

            $node = new stdClass();
            $node->$extractorName = new stdClass();
            $node->$extractorName->attributes = [$attributes];
            $this->data = $node;
        }
    }

    public function init(): void
    {
        $this->createNodeComponents();
        $this->createAttributesComponents();
    }


}