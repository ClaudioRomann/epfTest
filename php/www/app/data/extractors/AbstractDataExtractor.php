<?php

namespace app\data\extractors;

use epf\epfString;

abstract class AbstractDataExtractor
{
    protected string $inputTxt;
    protected array $data;

    /**
     * @param $inputTxt string plain text
     */
    public function __construct(string $inputTxt)
    {
        $this->inputTxt = $inputTxt;
    }

    abstract public function extractData(): array;
    abstract public function extractorName(): string;

    protected function createAttributesComponents(): void {
        // TODO: nothing
    }
    protected function createNodeComponents(): void
    {
        $epfStringInputTxt = new epfString($this->inputTxt);
        $array = $epfStringInputTxt->split("\n");
        foreach ($array as $element) {
            $epfStringElement = new epfString($element);
            $arrayElement = $epfStringElement->split('|');

            if (static::NODE_TYPES[$arrayElement[0]->__toString()]) {
                $this->data[$this->extractorName()][] = $arrayElement[1]->__toString();
            }
        }

    }

    public function init(): void
    {
        $this->createNodeComponents();
        $this->createAttributesComponents();
    }


}